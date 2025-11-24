<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

/**
 * @OA\Schema(
 *     title="Coordinador",
 *     description="Modelo de Coordinador",
 *     @OA\Xml(
 *         name="Coordinador"
 *     ),
 *     properties={
 *         @OA\Property(
 *             property="id_coordinador",
 *             title="ID del Coordinador",
 *             description="Identificador único del coordinador",
 *             type="integer",
 *             example=1,
 *             readOnly="true"
 *         ),
 *         @OA\Property(
 *             property="id_usuario",
 *             title="ID de Usuario",
 *             description="Identificador único del usuario asociado",
 *             type="integer",
 *             example=1
 *         ),
 *         @OA\Property(
 *             property="nombre",
 *             title="Nombre",
 *             description="Nombre del coordinador",
 *             type="string",
 *             example="Juan",
 *             maxLength=255
 *         ),
 *          @OA\Property(
 *             property="apellido_paterno",
 *             title="Apellido Paterno",
 *             description="Apellido paterno del coordinador",
 *             type="string",
 *             example="Pérez",
 *             maxLength=255
 *         ),
 *          @OA\Property(
 *             property="apellido_materno",
 *             title="Apellido Materno",
 *             description="Apellido materno del coordinador",
 *             type="string",
 *             example="García",
 *             maxLength=255
 *         ),
 *          @OA\Property(
 *             property="correo",
 *             title="Correo Electrónico",
 *             description="Correo electrónico del coordinador",
 *             type="string",
 *             format="email",
 *             example="juan.perez@example.com",
 *             maxLength=255
 *         ),
 *          @OA\Property(
 *             property="rol",
 *             title="Rol",
 *             description="Rol del usuario",
 *             type="string",
 *             example="Coordinador",
 *             maxLength=255
 *         )
 *     }
 * )
 */
class Coordinador extends User
{
    /**
     * La tabla de base de datos asociada con el modelo.
     *
     * @var string
     */
    protected $table = 'vw_coordinador_perfil';

    /**
     * La clave primaria asociada con la tabla.
     *
     * @var string
     */
    protected $primaryKey = 'id_coordinador';

    public static function crearCoordinador(string $p_nombre, string $p_apellido_paterno, string $p_apellido_materno, string $p_correo, string $p_contrasena_hash)
    {
        $sql = 'CALL sp_crear_coordinador(?, ?, ?, ?, ?, @p_out_id_usuario, @p_out_id_coordinador)';
        DB::select($sql, [$p_nombre, $p_apellido_paterno, $p_apellido_materno, $p_correo, $p_contrasena_hash]);
        $results = DB::select('SELECT @p_out_id_usuario as id_usuario, @p_out_id_coordinador as id_coordinador');
        return $results[0];
    }
}