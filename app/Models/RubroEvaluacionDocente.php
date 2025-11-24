<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RubroEvaluacionDocente
 *
 * @package App\Models
 *
 * This model represents a Rubro (Category/Heading) for teacher evaluation in the system.
 */
/**
 * @OA\Schema(
 *     title="RubroEvaluacionDocente",
 *     description="Modelo de Rubro de Evaluación Docente",
 *     @OA\Xml(
 *         name="RubroEvaluacionDocente"
 *     ),
 *     properties={
 *         @OA\Property(
 *             property="id",
 *             title="ID",
 *             description="Identificador único del rubro",
 *             type="integer",
 *             example=1,
 *             readOnly="true"
 *         ),
 *         @OA\Property(
 *             property="nombre",
 *             title="Nombre",
 *             description="Nombre del rubro",
 *             type="string",
 *             example="Dominio de la materia",
 *             maxLength=255
 *         )
 *     }
 * )
 */
class RubroEvaluacionDocente extends Model
{
    protected $table = 'cat_rubro_alumno_docente';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

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
        'nombre',
    ];
}
