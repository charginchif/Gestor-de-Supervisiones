<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgendaSupervision extends Model
{
    protected $table = 'agenda_supervision';
    protected $primaryKey = 'id_agenda';
    public $timestamps = false;

    protected $fillable = [
        'fecha',
        'id_coordinador',
        'id_horario',
        'estado',
    ];
}
