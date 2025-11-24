<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PlanEstudio
 *
 * @package App\Models
 *
 * This model represents a Plan de Estudio (Study Plan) in the system, defining the curriculum.
 */
/**
 * @OA\Schema(
 *     title="PlanEstudio",
 *     description="Modelo de Plan de Estudio",
 *     @OA\Xml(
 *         name="PlanEstudio"
 *     ),
 *     properties={
 *         @OA\Property(
 *             property="id_plan_estudio",
 *             title="ID del Plan de Estudio",
 *             description="Identificador único del plan de estudio",
 *             type="integer",
 *             example=1,
 *             readOnly="true"
 *         ),
 *         @OA\Property(
 *             property="id_carrera",
 *             title="ID de la Carrera",
 *             description="Identificador único de la carrera asociada",
 *             type="integer",
 *             example=1
 *         ),
 *         @OA\Property(
 *             property="id_materia",
 *             title="ID de la Materia",
 *             description="Identificador único de la materia asociada",
 *             type="integer",
 *             example=1
 *         ),
 *         @OA\Property(
 *             property="id_cat_nivel",
 *             title="ID del Nivel",
 *             description="Identificador único del nivel asociado",
 *             type="integer",
 *             example=1
 *         ),
 *         @OA\Property(
 *             property="id_modalidad",
 *             title="ID de la Modalidad",
 *             description="Identificador único de la modalidad asociada",
 *             type="integer",
 *             example=1
 *         )
 *     }
 * )
 */
class PlanEstudio extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'plan_estudio';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_plan_estudio';

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
        'id_carrera',
        'id_materia',
        'id_cat_nivel',
        'id_modalidad',
    ];
}
