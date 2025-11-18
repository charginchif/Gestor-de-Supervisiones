<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InscripcionGrupo extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'inscripcion_grupo';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_inscripcion_grupo';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_alumno',
        'id_grupo',
    ];
}
