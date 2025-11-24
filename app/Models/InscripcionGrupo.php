<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class InscripcionGrupo
 *
 * @package App\Models
 *
 * This model represents a student's enrollment in a group.
 */
/**
 * @OA\Schema(
 *     title="InscripcionGrupo",
 *     description="Modelo de Inscripción en Grupo",
 *     @OA\Xml(
 *         name="InscripcionGrupo"
 *     ),
 *     properties={
 *         @OA\Property(
 *             property="id_inscripcion_grupo",
 *             title="ID de la Inscripción en Grupo",
 *             description="Identificador único de la inscripción en el grupo",
 *             type="integer",
 *             example=1,
 *             readOnly="true"
 *         ),
 *         @OA\Property(
 *             property="id_alumno",
 *             title="ID del Alumno",
 *             description="Identificador único del alumno inscrito",
 *             type="integer",
 *             example=1
 *         ),
 *         @OA\Property(
 *             property="id_grupo",
 *             title="ID del Grupo",
 *             description="Identificador único del grupo en el que se inscribe el alumno",
 *             type="integer",
 *             example=1
 *         )
 *     }
 * )
 */
class InscripcionGrupo extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'inscripcion_grupo';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_inscripcion_grupo';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_alumno',
        'id_grupo',
    ];
}
