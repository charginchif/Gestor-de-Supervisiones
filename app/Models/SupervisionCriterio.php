<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupervisionCriterio extends Model
{
    protected $table = 'supervision_criterios';
    public $timestamps = false;

    protected $fillable = [
        'id_supervision',
        'id_supcriterio',
        'estado',
    ];
}
