<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Schema(
 *     title="Alumno",
 *     description="Modelo de Alumno basado en la vista de perfil",
 *     @OA\Xml(
 *         name="Alumno"
 *     ),
 *     properties={
 *         @OA\Property(
 *             property="id_alumno",
 *             title="ID del Alumno",
 *             description="Identificador único del alumno",
 *             type="integer",
 *             example=1,
 *             readOnly="true"
 *         ),
 *          @OA\Property(
 *             property="id_usuario",
 *             title="ID de Usuario",
 *             description="Identificador único del usuario asociado",
 *             type="integer",
 *             example=1
 *         ),
 *          @OA\Property(
 *             property="matricula",
 *             title="Matrícula",
 *             description="Matrícula del alumno",
 *             type="string",
 *             example="202012345"
 *         ),
 *          @OA\Property(
 *             property="nombre_completo",
 *             title="Nombre Completo",
 *             description="Nombre completo del alumno",
 *             type="string",
 *             example="Juan Pérez García"
 *         ),
 *           @OA\Property(
 *             property="correo",
 *             title="Correo Electrónico",
 *             description="Correo electrónico del alumno",
 *             type="string",
 *             format="email",
 *             example="juan.perez@example.com"
 *         ),
 *           @OA\Property(
 *             property="id_carrera",
 *             title="ID de Carrera",
 *             description="Identificador único de la carrera del alumno",
 *             type="integer",
 *             example=1
 *         ),
 *           @OA\Property(
 *             property="carrera",
 *             title="Carrera",
 *             description="Nombre de la carrera del alumno",
 *             type="string",
 *             example="Ingeniería en Sistemas Computacionales"
 *         )
 *     }
 * )
 */
class Alumno extends Model 
{
    // Nombre de la vista
    protected $table = 'vw_alumno_perfil';

    // Clave primaria presente en la vista
    protected $primaryKey = 'id_alumno';

    // La vista no tiene increment (evita intentos de insert/update)
    public $incrementing = false;

    // La vista no maneja created_at / updated_at
    public $timestamps = false;

    // Campos que corresponden a la vista
    protected $fillable = [
        'id_alumno',
        'id_usuario',
        'matricula',
        'nombre_completo',
        'correo',
        'id_carrera',
        'carrera',
    ];

    // --- Relaciones útiles (lectura) ---
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }

    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'id_carrera', 'id_carrera');
    }



    public static function crearAlumno(string $p_nombre, string $p_apellido_paterno, string $p_apellido_materno, string $p_correo, string $p_contrasena_hash, string $p_matricula, int $p_id_carrera)
    {
        $sql = 'CALL sp_crear_alumno(?, ?, ?, ?, ?, ?, ?, @p_out_id_usuario, @p_out_id_alumno)';
        $success = DB::statement($sql, [$p_nombre, $p_apellido_paterno, $p_apellido_materno, $p_correo, $p_contrasena_hash, $p_matricula, $p_id_carrera]);

        if (!$success) {
            return null; 
        }

        $results = DB::select('SELECT @p_out_id_usuario as id_usuario, @p_out_id_alumno as id_alumno');

        if (empty($results) || !isset($results[0]->id_usuario)) {
            return null; // Stored procedure executed, but didn't return expected IDs
        }

        return $results[0];
    }
}