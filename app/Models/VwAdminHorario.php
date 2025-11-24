<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class VwAdminHorario
 *
 * @package App\Models
 *
 * This model represents a view for administrative schedules/timetables in the system.
 * It provides a comprehensive view of schedules with related cycle and day information.
 */
/**
 * @OA\Schema(
 *     title="VwAdminHorario",
 *     description="Vista de Horarios para Administrador",
 *     @OA\Xml(
 *         name="VwAdminHorario"
 *     ),
 *     @OA\Property(
 *         property="id_horario",
 *         title="ID del Horario",
 *         description="Identificador único del horario",
 *         type="integer",
 *         example=1
 *     ),
 *      @OA\Property(
 *         property="id_ciclo",
 *         title="ID del Ciclo",
 *         description="Identificador único del ciclo",
 *         type="integer",
 *         example=1
 *     ),
 *      @OA\Property(
 *         property="ciclo",
 *         title="Ciclo",
 *         description="Nombre del ciclo",
 *         type="string",
 *         example="2024-1"
 *     ),
 *      @OA\Property(
 *         property="id_cat_dia",
 *         title="ID del Día",
 *         description="Identificador único del día de la semana",
 *         type="integer",
 *         example=1
 *     ),
 *      @OA\Property(
 *         property="dia",
 *         title="Día",
 *         description="Día de la semana",
 *         type="string",
 *         example="Lunes"
 *     ),
 *      @OA\Property(
 *         property="hora_inicio",
 *         title="Hora de Inicio",
 *         description="Hora de inicio de la clase",
 *         type="string",
 *         format="time",
 *         example="08:00:00"
 *     ),
 *      @OA\Property(
 *         property="hora_fin",
 *         title="Hora de Fin",
 *         description="Hora de fin de la clase",
 *         type="string",
 *         format="time",
 *         example="10:00:00"
 *     )
 * )
 */
class VwAdminHorario extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'vw_admin_horarios';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_horario'; // Assuming 'id_horario' as primary key based on OpenAPI schema

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
