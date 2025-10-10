<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

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