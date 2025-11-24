<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class VwGrupoAlumnos
 *
 * @package App\Models
 *
 * This model represents a view that combines information about groups and their enrolled students.
 * It provides a consolidated view of student enrollments within groups.
 */
/**
 * @OA\Schema(
 *     title="VwGrupoAlumnos",
 *     description="Vista de Grupos y Alumnos",
 *     @OA\Xml(
 *         name="VwGrupoAlumnos"
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
 *         property="id_modalidad",
 *         title="ID de la Modalidad",
 *         description="Identificador único de la modalidad asociada",
 *         type="integer",
 *         example=1
 *     ),
 *      @OA\Property(
 *         property="id_carrera",
 *         title="ID de la Carrera",
 *         description="Identificador único de la carrera asociada",
 *         type="integer",
 *         example=1
 *     ),
 *      @OA\Property(
 *         property="id_alumno",
 *         title="ID del Alumno",
 *         description="Identificador único del alumno",
 *         type="integer",
 *         example=1
 *     ),
 *      @OA\Property(
 *         property="nombre_completo",
 *         title="Nombre Completo",
 *         description="Nombre completo del alumno",
 *         type="string",
 *         example="Juan Perez"
 *     )
 * )
 */
class VwGrupoAlumnos extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'vw_grupo_alumnos';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_grupo'; // Assuming 'id_grupo' as part of a composite key for this view.

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
