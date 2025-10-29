<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    protected $table = 'grupo';
    protected $primaryKey = 'id_grupo';
    public $timestamps = false;

    protected $fillable = [
        'acronimo',
        'id_ciclo',
        'id_turno',
        'id_modalidad',
        'id_nivel',
        'id_carrera',
    ];
}
