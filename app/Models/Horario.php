<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    protected $table = 'horarios';
    protected $primaryKey = 'id_horario';
    public $timestamps = false;

    protected $fillable = [
        'id_ciclo',
        'id_dia',
        'hora_inicio',
        'hora_fin',
    ];
}