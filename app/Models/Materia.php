<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    protected $table = 'materia';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        // Agrega aquí los campos de la tabla materia
    ];
}
