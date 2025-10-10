<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Docente extends Model
{
    /**
     * La tabla de base de datos asociada con el modelo.
     *
     * @var string
     */
    protected $table = 'vw_docente_perfil';

    /**
     * La clave primaria asociada con la tabla.
     *
     * @var string
     */
    protected $primaryKey = 'id_docente';

    /**
     * Indica si el modelo debe tener timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array
     */
    protected $fillable = [
        'id_docente',
        'grado_academico',
        'id_usuario',
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'correo',
        'fecha_registro',
        'ultimo_acceso',
        'id_rol'
    ];

    /**
     * Obtiene el usuario asociado con el docente.
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }

    public static function crearDocente(string $p_nombre, string $p_apellido_paterno, string $p_apellido_materno, string $p_correo, string $p_contrasena_hash, ?string $p_grado_academico)
    {
        $sql = 'CALL sp_crear_docente(?, ?, ?, ?, ?, ?, @p_out_id_usuario, @p_out_id_docente)';
        DB::select($sql, [$p_nombre, $p_apellido_paterno, $p_apellido_materno, $p_correo, $p_contrasena_hash, $p_grado_academico]);
        $results = DB::select('SELECT @p_out_id_usuario as id_usuario, @p_out_id_docente as id_docente');
        return $results[0];
    }
}