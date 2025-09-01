<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CicloEscolar extends Model
{
    protected $table = 'ciclo_escolar';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        // Agrega aquí los campos de la tabla ciclo_escolar
    ];
}
