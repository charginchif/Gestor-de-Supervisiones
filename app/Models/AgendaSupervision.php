<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="AgendaSupervision",
 *     description="Modelo de Agenda de Supervisión",
 *     @OA\Xml(
 *         name="AgendaSupervision"
 *     ),
 *     properties={
 *         @OA\Property(
 *             property="id_agenda",
 *             title="ID de Agenda",
 *             description="Identificador único de la agenda de supervisión",
 *             type="integer",
 *             example=1,
 *             readOnly="true"
 *         ),
 *         @OA\Property(
 *             property="fecha",
 *             title="Fecha",
 *             description="Fecha de la supervisión",
 *             type="string",
 *             format="date",
 *             example="2024-01-01"
 *         ),
 *         @OA\Property(
 *             property="id_coordinador",
 *             title="ID del Coordinador",
 *             description="Identificador único del coordinador",
 *             type="integer",
 *             example=1
 *         ),
 *         @OA\Property(
 *             property="id_horario",
 *             title="ID del Horario",
 *             description="Identificador único del horario",
 *             type="integer",
 *             example=1
 *         ),
 *         @OA\Property(
 *             property="estado",
 *             title="Estado",
 *             description="Estado de la supervisión",
 *             type="integer",
 *             example=1
 *         )
 *     }
 * )
 */
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
