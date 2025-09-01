<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    protected $table = 'grupo';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        // Agrega aquí los campos de la tabla grupo
    ];
}
