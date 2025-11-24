<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SolicitudInscripcion
 *
 * @package App\Models
 *
 * This model represents a Solicitud de Inscripcion (Enrollment Request) in the system.
 */
/**
 * @OA\Schema(
 *     title="SolicitudInscripcion",
 *     description="Modelo de Solicitud de Inscripción",
 *     @OA\Xml(
 *         name="SolicitudInscripcion"
 *     ),
 *     @OA\Property(
 *         property="id_solicitud_inscripcion",
 *         title="ID de la Solicitud de Inscripción",
 *         description="Identificador único de la solicitud de inscripción",
 *         type="integer",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="id_alumno",
 *         title="ID del Alumno",
 *         description="Identificador único del alumno que realiza la solicitud",
 *         type="integer",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="id_grupo",
 *         title="ID del Grupo",
 *         description="Identificador único del grupo al que se solicita la inscripción",
 *         type="integer",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="estado",
 *         title="Estado",
 *         description="Estado de la solicitud de inscripción",
 *         type="string",
 *         enum={"pendiente", "aprobada", "rechazada"},
 *         example="pendiente"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         title="Fecha de Creación",
 *         description="Fecha y hora de creación de la solicitud",
 *         type="string",
 *         format="date-time"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         title="Fecha de Actualización",
 *         description="Fecha y hora de la última actualización de la solicitud",
 *         type="string",
 *         format="date-time"
 *     ),
 *     @OA\Property(
 *         property="alumno",
 *         ref="#/components/schemas/Alumno"
 *     ),
 *     @OA\Property(
 *         property="grupo",
 *         ref="#/components/schemas/Grupo"
 *     )
 * )
 */
class SolicitudInscripcion extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'solicitud_inscripcion';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_solicitud_inscripcion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_alumno',
        'id_grupo',
        'estado',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Get the student that owns the request.
     */
    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'id_alumno', 'id_alumno');
    }

    /**
     * Get the group associated with the request.
     */
    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'id_grupo', 'id_grupo');
    }
}