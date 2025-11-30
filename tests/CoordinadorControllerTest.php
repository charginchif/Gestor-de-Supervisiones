<?php

namespace Tests;

use App\Models\User;
use App\Models\Carrera;
use App\Models\Coordinador;
use App\Models\Alumno;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Testing\DatabaseTransactions;

class CoordinadorControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_get_alumnos_coordinados_returns_students_for_coordinated_carreras()
    {
        // 1. Setup Careers
        $carreraCoordinada = Carrera::create(['nombre' => 'Coordinated Career', 'clave' => 'CC01']);
        $carreraNoCoordinada = Carrera::create(['nombre' => 'Another Career', 'clave' => 'AC01']);

        // 2. Setup Coordinator
        $coordinadorResult = Coordinador::crearCoordinador(
            'Test',
            'Coordinador',
            'User',
            'coordinador@test.com',
            Hash::make('password')
        );
        $coordinadorUser = User::find($coordinadorResult->id_usuario);

        // 3. Link Coordinator to Career
        DB::table('carrera_coordinador')->insert([
            'id_coordinador' => $coordinadorResult->id_coordinador,
            'id_carrera' => $carreraCoordinada->id_carrera,
        ]);

        // 4. Setup Students
        $alumnoCoordinadoResult = Alumno::crearAlumno(
            'Student',
            'In',
            'Coordinated',
            'student1@test.com',
            Hash::make('password'),
            'S001',
            $carreraCoordinada->id_carrera
        );

        $alumnoNoCoordinadoResult = Alumno::crearAlumno(
            'Student',
            'In',
            'Other',
            'student2@test.com',
            Hash::make('password'),
            'S002',
            $carreraNoCoordinada->id_carrera
        );
        
        // 5. Act as coordinator and call endpoint
        $this->actingAs($coordinadorUser);
        $response = $this->get('/coordinador/alumnos');
        
        // 6. Assertions
        $response->assertResponseStatus(200);

        $response->seeJson(['exito' => true]);
        $response->seeJson(['mensaje' => 'Lista de alumnos de las carreras coordinadas']);

        $responseData = json_decode($response->response->getContent(), true);
        $alumnosData = $responseData['datos'];

        $this->assertCount(1, $alumnosData, 'Should return only one student from the coordinated career.');

        $this->assertEquals('student1@test.com', $alumnosData[0]['correo']);
        $this->assertEquals($carreraCoordinada->nombre, $alumnosData[0]['carrera']);

        // Check that the other student is not in the list
        $studentEmails = array_column($alumnosData, 'correo');
        $this->assertNotContains('student2@test.com', $studentEmails);
    }
}