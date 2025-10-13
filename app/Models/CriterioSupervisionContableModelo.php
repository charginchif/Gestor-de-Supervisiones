<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CriterioSupervisionContableModelo extends Model
{
    protected $table = 'criterios_supervision';
    protected $primaryKey = 'id_supcriterio';
    public $timestamps = false;
}