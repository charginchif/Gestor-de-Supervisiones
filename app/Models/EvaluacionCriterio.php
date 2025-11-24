<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EvaluacionCriterio
 *
 * @package App\Models
 *
 * This model represents an evaluation criterion in the system.
 * It interacts with the 'evaluacion_criterios' table.
 *
 * @OA\Schema(
 *     title="EvaluacionCriterio",
 *     description="Modelo de Criterio de Evaluación",
 *     @OA\Xml(
 *         name="EvaluacionCriterio"
 *     ),
 *     properties={
 *         @OA\Property(
 *             property="id",
 *             title="ID",
 *             description="Identificador único de la relación criterio-evaluación",
 *             type="integer",
 *             example=1,
 *             readOnly="true"
 *         ),
 *         @OA\Property(
 *             property="id_evaluacion",
 *             title="ID de Evaluación",
 *             description="Identificador único de la evaluación a la que pertenece",
 *             type="integer",
 *             example=1
 *         ),
 *         @OA\Property(
 *             property="id_evacriterio",
 *             title="ID de Criterio de Evaluación",
 *             description="Identificador único del criterio de evaluación asociado",
 *             type="integer",
 *             example=1
 *         ),
 *         @OA\Property(
 *             property="estado",
 *             title="Estado",
 *             description="Estado del criterio en la evaluación (ej. 0: No cumple, 1: Cumple, 2: Parcialmente cumple)",
 *             type="integer",
 *             example=1
 *         )
 *     }
 * )
 */
class EvaluacionCriterio extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'evaluacion_criterios';

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
        'id_evaluacion',
        'id_evacriterio',
        'estado',
    ];
}
