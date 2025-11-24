<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class VwSupervisionRubroPorcentaje
 *
 * @package App\Models
 *
 * This model represents a view displaying the percentage of compliance for supervision rubrics.
 * It provides a summary of fulfilled criteria within each rubric for a given supervision.
 */
/**
 * @OA\Schema(
 *     title="VwSupervisionRubroPorcentaje",
 *     description="Vista de Porcentaje de Rubro de Supervisión",
 *     @OA\Xml(
 *         name="VwSupervisionRubroPorcentaje"
 *     ),
 *     @OA\Property(
 *         property="id_supervision",
 *         title="ID de Supervisión",
 *         description="Identificador único de la supervisión",
 *         type="integer",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="id_rubro",
 *         title="ID de Rubro",
 *         description="Identificador único del rubro",
 *         type="integer",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="rubro",
 *         title="Rubro",
 *         description="Nombre del rubro",
 *         type="string",
 *         example="Calidad del Servicio"
 *     ),
 *     @OA\Property(
 *         property="total_criterios",
 *         title="Total de Criterios",
 *         description="Número total de criterios en el rubro",
 *         type="integer",
 *         example=5
 *     ),
 *     @OA\Property(
 *         property="cumplidos",
 *         title="Criterios Cumplidos",
 *         description="Número de criterios que se cumplieron",
 *         type="integer",
 *         example=4
 *     ),
 *     @OA\Property(
 *         property="porcentaje_cumplimiento",
 *         title="Porcentaje de Cumplimiento",
 *         description="Porcentaje de criterios cumplidos",
 *         type="number",
 *         format="float",
 *         example=80.00
 *     )
 * )
 */
class VwSupervisionRubroPorcentaje extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'vw_supervision_rubro_porcentaje';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_supervision'; // Assuming 'id_supervision' as part of a composite key for this view.

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}