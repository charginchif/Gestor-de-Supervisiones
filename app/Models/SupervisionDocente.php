<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupervisionDocente extends Model
{
    protected $table = 'supervision_docente';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        // Agrega aquí los campos de la tabla supervision_docente
    ];
}
