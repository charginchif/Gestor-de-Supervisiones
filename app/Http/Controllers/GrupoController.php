<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\VwCoordGrupo;
use App\Models\VwGrupoAlumnos;
use App\Models\PlanEstudio;
use App\Utils\RespuestaAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Utils\GeneradorCodigos;


class GrupoController extends Controller
{
    public function indexAdmin()
    {
        $grupos = VwGrupoAlumnos::select('*')
            ->get();
        return RespuestaAPI::exito('Lista de grupos', $grupos);
    }

    public function index(Request $request)
    {
        // This method will not work as expected because the coordinator information is not available in the new view.
        $grupos = VwGrupoAlumnos::select('id_grupo', 'grupo', 'id_modalidad', 'id_carrera')
            ->groupBy('id_grupo', 'grupo', 'id_modalidad', 'id_carrera')
            ->get();
        return RespuestaAPI::exito('Lista de grupos', $grupos);
    }

    public function show($id)
    {
        $grupo = Grupo::find($id);
        if (!$grupo) {
            return RespuestaAPI::error('Grupo no encontrado', 404);
        }
        return RespuestaAPI::exito('Grupo encontrado', $grupo);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'acronimo' => 'required|string|max:15',
            'id_ciclo' => 'required|integer',
            'id_turno' => 'required|integer',
            'id_nivel' => 'required|integer',
            'id_plan_estudio' => 'required|integer',
            'id_plantel' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return RespuestaAPI::error('Datos inválidos', 422, $validator->errors());
        }

        try {
        $codigoGenerado = GeneradorCodigos::generateRandomCode();
        $request->input('codigo', $codigoGenerado);

            DB::statement(
                'CALL sp_grupo_insertar(?, ?, ?, ?, ?, ?, ?)',
                [
                    $request->acronimo,
                    $request->id_ciclo,
                    $request->id_turno,
                    $request->id_nivel,
                    $request->id_plan_estudio,
                    $request->id_plantel,
                    $codigoGenerado,
                ]
            );
            return RespuestaAPI::exito('Grupo creado exitosamente', null, 201);
        } catch (\Exception $e) {
            // Extract the core error message from the exception
            $errorMessage = $e->getMessage();
            if (str_contains($errorMessage, 'SQLSTATE[45000]')) {
                preg_match('/1644 (.*)/', $errorMessage, $matches);
                $errorMessage = $matches[1] ?? 'Error en la operación.';
            }
            return RespuestaAPI::error('Error al crear el grupo: ' . $errorMessage, 500);
        }
    }

    public function update(Request $request, $id)
    {
        // La validación de existencia del grupo la hace el propio SP.
        $validator = Validator::make($request->all(), [
            'acronimo' => 'sometimes|string|max:15',
            'id_ciclo' => 'sometimes|integer',
            'id_turno' => 'sometimes|integer',
            'id_modalidad' => 'sometimes|integer',
            'id_nivel' => 'sometimes|integer',
            'id_plan_estudio' => 'sometimes|integer',
        ]);

        if ($validator->fails()) {
            return RespuestaAPI::error('Datos inválidos', 422, $validator->errors());
        }

        try {
            DB::statement(
                'CALL sp_grupo_actualizar(?, ?, ?, ?, ?, ?, ?)',
                [
                    $id,
                    $request->input('acronimo'),
                    $request->input('id_ciclo'),
                    $request->input('id_turno'),
                    $request->input('id_modalidad'),
                    $request->input('id_nivel'),
                    $request->input('id_plan_estudio'),
                ]
            );

            $updatedGrupo = Grupo::find($id);
            return RespuestaAPI::exito('Grupo actualizado exitosamente', $updatedGrupo);
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al actualizar el grupo: ' . $e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        $grupo = Grupo::find($id);
        if (!$grupo) {
            return RespuestaAPI::error('Grupo no encontrado', 404);
        }

        try {
            DB::statement('CALL sp_grupo_eliminar(?)', [$id]);
            return RespuestaAPI::exito('Grupo eliminado exitosamente', null, 200);
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al eliminar el grupo: ' . $e->getMessage(), 500);
        }
    }

    public function asignarPlan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_grupo' => 'required|integer',
            'id_plan_estudio' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return RespuestaAPI::error('Datos inválidos', 422, $validator->errors());
        }

        try {
            DB::statement(
                'CALL sp_grupo_asignar_plan(?, ?)',
                [
                    $request->id_grupo,
                    $request->id_plan_estudio,
                ]
            );
            return RespuestaAPI::exito('Plan de estudio asignado exitosamente', null, 200);
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al asignar el plan de estudio: ' . $e->getMessage(), 500);
        }
    }

    public function quitarPlan($id_grupo)
    {
        try {
            DB::statement('CALL sp_grupo_quitar_plan(?)', [$id_grupo]);
            return RespuestaAPI::exito('Plan de estudio quitado exitosamente', null, 200);
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al quitar el plan de estudio: ' . $e->getMessage(), 500);
        }
    }
}