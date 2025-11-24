<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class VwCoordAgendaSupervision
 *
 * @package App\Models
 *
 * This model represents a view of the supervision agenda for coordinators.
 * It combines information about the agenda, coordinator, and associated schedules.
 */
/**
 * @OA\Schema(
 *     title="VwCoordAgendaSupervision",
 *     description="Vista de Agenda de Supervisión de Coordinador",
 *     @OA\Xml(
 *         name="VwCoordAgendaSupervision"
 *     ),
 *     @OA\Property(property="id_agenda", type="integer", example=1),
 *     @OA\Property(property="fecha", type="string", format="date", example="2024-01-01"),
 *     @OA\Property(property="id_coordinador", type="integer", example=1),
 *     @OA\Property(property="nombre_coordinador", type="string", example="Juan Perez"),
 *     @OA\Property(property="id_horario", type="integer", example=1),
 *     @OA\Property(property="hora_inicio", type="string", format="time", example="08:00:00"),
 *     @OA\Property(property="hora_fin", type="string", format="time", example="10:00:00"),
 *     @OA\Property(property="id_ciclo", type="integer", example=1),
 *     @OA\Property(property="ciclo", type="string", example="2024-1"),
 *     @OA\Property(property="id_cat_dia", type="integer", example=1),
 *     @OA\Property(property="dia", type="string", example="Lunes"),
 *     @OA\Property(property="estado", type="integer", example=1)
 * )
 */
class VwCoordAgendaSupervision extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'vw_coord_agenda_supervision';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_agenda'; // Assuming 'id_agenda' as primary key for this view.

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
