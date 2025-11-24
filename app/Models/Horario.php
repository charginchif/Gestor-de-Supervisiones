<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Horario
 *
 * @package App\Models
 *
 * This model represents a Horario (Schedule/Timetable) in the system.
 * It interacts with the 'horarios' table.
 *
 * @OA\Schema(
 *     title="Horario",
 *     description="Modelo de Horario",
 *     @OA\Xml(
 *         name="Horario"
 *     ),
 *     properties={
 *         @OA\Property(
 *             property="id_horario",
 *             title="ID del Horario",
 *             description="Identificador único del horario",
 *             type="integer",
 *             example=1,
 *             readOnly="true"
 *         ),
 *         @OA\Property(
 *             property="id_ciclo",
 *             title="ID del Ciclo",
 *             description="Identificador único del ciclo asociado",
 *             type="integer",
 *             example=1
 *         ),
 *         @OA\Property(
 *             property="id_dia",
 *             title="ID del Día",
 *             description="Identificador único del día asociado (ej. 1 para Lunes, 7 para Domingo)",
 *             type="integer",
 *             example=1
 *         ),
 *         @OA\Property(
 *             property="hora_inicio",
 *             title="Hora de Inicio",
 *             description="Hora de inicio del horario",
 *             type="string",
 *             format="time",
 *             example="08:00:00"
 *         ),
 *         @OA\Property(
 *             property="hora_fin",
 *             title="Hora de Fin",
 *             description="Hora de fin del horario",
 *             type="string",
 *             format="time",
 *             example="09:00:00"
 *         )
 *     }
 * )
 */
class Horario extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'horarios';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_horario';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_ciclo',
        'id_dia',
        'hora_inicio',
        'hora_fin',
    ];
}