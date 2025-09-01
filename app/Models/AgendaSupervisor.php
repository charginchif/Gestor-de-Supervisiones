<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgendaSupervisor extends Model
{
    protected $table = 'agenda_supervisor';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        // Agrega aquí los campos de la tabla agenda_supervisor
    ];
}
