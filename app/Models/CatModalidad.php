<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatModalidad extends Model
{
    protected $table = 'cat_modalidad';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
    ];
}
