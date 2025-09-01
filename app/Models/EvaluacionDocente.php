<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluacionDocente extends Model
{
    protected $table = 'evaluacion_docente';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        // Agrega aquí los campos de la tabla evaluacion_docente
    ];
}
