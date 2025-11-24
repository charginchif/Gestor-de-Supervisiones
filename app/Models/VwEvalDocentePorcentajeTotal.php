<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class VwEvalDocentePorcentajeTotal
 *
 * @package App\Models
 *
 * This model represents a view for the total percentage of teacher evaluations.
 * It provides a summary of evaluation percentages for each teacher.
 */
/**
 * @OA\Schema(
 *     title="VwEvalDocentePorcentajeTotal",
 *     description="Vista de Porcentaje Total de Evaluación Docente",
 *     @OA\Xml(
 *         name="VwEvalDocentePorcentajeTotal"
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
 *         property="total_evaluaciones",
 *         title="Total de Evaluaciones",
 *         description="Número total de evaluaciones para el docente",
 *         type="integer",
 *         example=10
 *     ),
 *      @OA\Property(
 *         property="promedio_porcentaje_total",
 *         title="Promedio de Porcentaje Total",
 *         description="Promedio del porcentaje total de cumplimiento en todas las evaluaciones",
 *         type="number",
 *         format="float",
 *         example=95.50
 *     )
 * )
 */
class VwEvalDocentePorcentajeTotal extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'vw_eval_docente_porcentaje_total';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_docente'; // Assuming 'id_docente' as primary key for this view.

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}