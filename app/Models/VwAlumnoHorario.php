<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class VwAlumnoHorario
 *
 * @package App\Models
 *
 * This model represents a view of a student's schedule, including details about courses, teachers, and times.
 */
/**
 * @OA\Schema(
 *     title="VwAlumnoHorario",
 *     description="Vista de Horario de Alumno",
 *     @OA\Xml(
 *         name="VwAlumnoHorario"
 *     ),
 *     @OA\Property(
 *         property="id_alumno",
 *         title="ID del Alumno",
 *         description="Identificador único del alumno",
 *         type="integer",
 *         example=1
 *     ),
 *      @OA\Property(
 *         property="nombre_alumno",
 *         title="Nombre del Alumno",
 *         description="Nombre completo del alumno",
 *         type="string",
 *         example="Juan Perez"
 *     ),
 *      @OA\Property(
 *         property="id_grupo",
 *         title="ID del Grupo",
 *         description="Identificador único del grupo",
 *         type="integer",
 *         example=1
 *     ),
 *      @OA\Property(
 *         property="grupo",
 *         title="Grupo",
 *         description="Nombre del grupo",
 *         type="string",
 *         example="G-01"
 *     ),
 *      @OA\Property(
 *         property="id_materia",
 *         title="ID de la Materia",
 *         description="Identificador único de la materia",
 *         type="integer",
 *         example=1
 *     ),
 *      @OA\Property(
 *         property="materia",
 *         title="Materia",
 *         description="Nombre de la materia",
 *         type="string",
 *         example="Matemáticas"
 *     ),
 *      @OA\Property(
 *         property="id_docente",
 *         title="ID del Docente",
 *         description="Identificador único del docente",
 *         type="integer",
 *         example=1
 *     ),
 *      @OA\Property(
 *         property="nombre_docente",
 *         title="Nombre del Docente",
 *         description="Nombre completo del docente",
 *         type="string",
 *         example="Dr. Juan Perez"
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
class VwAlumnoHorario extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'vw_alumno_horario';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_alumno'; // Assuming 'id_alumno' as primary key for this view, or a composite key.

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
