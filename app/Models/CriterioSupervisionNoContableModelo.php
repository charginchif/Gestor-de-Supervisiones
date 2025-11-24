<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CriterioSupervisionNoContableModelo
 *
 * @package App\Models
 *
 * This model represents the 'vw_criterios_no_contables' view in the database.
 * It is used to manage and access criteria for non-countable supervision.
 * The primary key for this view is 'id_supcriterio'.
 *
 * @OA\Schema(
 *     title="CriterioSupervisionNoContableModelo",
 *     description="Criterio Supervision No Contable Modelo",
 *     @OA\Xml(
 *         name="CriterioSupervisionNoContableModelo"
 *     )
 * )
 */
class CriterioSupervisionNoContableModelo extends Model
{
    /**
     * @OA\Property(
     *     property="id_supcriterio",
     *     type="integer",
     *     format="int64",
     *     description="ID del criterio de supervisión no contable",
     *     readOnly="true"
     * )
     * @OA\Property(
     *     property="criterio",
     *     type="string",
     *     description="Descripción del criterio de supervisión no contable",
     *     maxLength=255
     * )
     * @OA\Property(
     *     property="porcentaje",
     *     type="number",
     *     format="float",
     *     description="Porcentaje asociado al criterio no contable"
     * )
     * @OA\Property(
     *     property="tipo_criterio",
     *     type="string",
     *     description="Tipo de criterio (ej. 'No Contable')",
     *     maxLength=50
     * )
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'vw_criterios_no_contables';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_supcriterio';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}