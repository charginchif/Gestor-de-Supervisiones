<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CriteriosEvaluacion extends Model
{
    protected $table = 'criterios_evaluacion';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        // Agrega aquí los campos de la tabla criterios_evaluacion
    ];
}
