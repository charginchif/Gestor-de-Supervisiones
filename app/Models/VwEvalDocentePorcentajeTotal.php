<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VwEvalDocentePorcentajeTotal extends Model
{
    protected $table = 'vw_eval_docente_porcentaje_total';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_evaluacion',
        'id_grupo',
        'id_docente',
        'id_alumno',
        'id_agenda',
        'total_criterios',
        'cumplidos',
        'porcentaje_total',
    ];
}
