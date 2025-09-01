<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}