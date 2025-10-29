<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VwCoordGrupo extends Model
{
    protected $table = 'vw_coord_grupos';
    // Since it is a view, it does not have a primary key.
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;
}
