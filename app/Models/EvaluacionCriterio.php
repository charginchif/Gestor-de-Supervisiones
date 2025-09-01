<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluacionCriterio extends Model
{
    protected $table = 'evaluacion_criterios';
    public $timestamps = false;

    protected $fillable = [
        'id_evaluacion',
        'id_evacriterio',
        'estado',
    ];
}
