<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CriteriosSupervicion extends Model
{
    protected $table = 'criterios_supervision';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        // Agrega aquí los campos de la tabla criterios_supervision
    ];
}
