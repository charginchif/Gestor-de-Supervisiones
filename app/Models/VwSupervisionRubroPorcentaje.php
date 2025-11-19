<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VwSupervisionRubroPorcentaje extends Model
{
    protected $table = 'vw_supervision_rubro_porcentaje';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_supervision',
        'id_rubro',
        'rubro',
        'total_criterios',
        'criterios_cumplidos',
        'porcentaje',
    ];
}
