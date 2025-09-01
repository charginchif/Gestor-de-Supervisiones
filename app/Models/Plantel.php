<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plantel extends Model
{
    protected $table = 'planteles';
    protected $primaryKey = 'id_plantel';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'ubicacion',
    ];
}