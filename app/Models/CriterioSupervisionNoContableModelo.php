<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CriterioSupervisionNoContableModelo extends Model
{
    protected $table = 'vw_criterios_no_contables';
    protected $primaryKey = 'id_supcriterio';
    public $timestamps = false;
}