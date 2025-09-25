<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanEstudio extends Model
{
    protected $table = 'plan_estudio';
    public $timestamps = false;

    protected $fillable = [
        'id_carrera',
        'id_materia',
        'id_cat_nivel',
        'id_modalidad',
    ];
}
