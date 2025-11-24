<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="CriterioEvaluacion",
 *     description="Modelo de Criterio de Evaluación",
 *     @OA\Xml(
 *         name="CriterioEvaluacion"
 *     ),
 *     properties={
 *         @OA\Property(
 *             property="id_evacriterio",
 *             title="ID del Criterio de Evaluación",
 *             description="Identificador único del criterio de evaluación",
 *             type="integer",
 *             example=1,
 *             readOnly="true"
 *         ),
 *         @OA\Property(
 *             property="id_rubro",
 *             title="ID del Rubro",
 *             description="Identificador único del rubro asociado",
 *             type="integer",
 *             example=1
 *         ),
 *         @OA\Property(
 *             property="descripcion",
 *             title="Descripción",
 *             description="Descripción del criterio de evaluación",
 *             type="string",
 *             example="El docente demuestra dominio del tema",
 *             maxLength=255
 *         )
 *     }
 * )
 */
class CriterioEvaluacion extends Model
{
    protected $table = 'criterios_evaluacion';
    protected $primaryKey = 'id_evacriterio';
    public $timestamps = false;

    protected $fillable = [
        'id_rubro',
        'descripcion'
    ];
}
