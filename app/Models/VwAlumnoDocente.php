<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class VwAlumnoDocente
 *
 * @package App\Models
 *
 * This model represents a view that combines information about students and their associated teachers.
 * It provides a consolidated view for administrative purposes.
 */
/**
 * @OA\Schema(
 *     title="VwAlumnoDocente",
 *     description="Vista de Alumno-Docente",
 *     @OA\Xml(
 *         name="VwAlumnoDocente"
 *     ),
 *     @OA\Property(property="id_usuario", type="integer", example=1),
 *     @OA\Property(property="id_alumno", type="integer", example=1),
 *     @OA\Property(property="nombre_alumno", type="string", example="Juan Perez"),
 *     @OA\Property(property="id_docente", type="integer", example=1),
 *     @OA\Property(property="nombre_docente", type="string", example="Dr. Juan Perez"),
 *     @OA\Property(property="id_grupo", type="integer", example=1),
 *     @OA\Property(property="grupo", type="string", example="G-01")
 * )
 */
class VwAlumnoDocente extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'vw_alumno_docentes';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_alumno'; // Assuming 'id_alumno' as primary key for this view

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}