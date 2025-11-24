<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RubroSupervisionNoContable
 *
 * @package App\Models
 *
 * This model represents a Rubro (Category/Heading) for non-countable supervision in the system.
 */
/**
 * @OA\Schema(
 *     title="RubroSupervisionNoContable",
 *     description="Modelo de Rubro de Supervisión No Contable",
 *     @OA\Xml(
 *         name="RubroSupervisionNoContable"
 *     ),
 *     @OA\Property(
 *         property="id",
 *         title="ID",
 *         description="Identificador único del rubro",
 *         type="integer",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="nombre",
 *         title="Nombre",
 *         description="Nombre del rubro",
 *         type="string",
 *         example="Atención al cliente"
 *     )
 * )
 */
class RubroSupervisionNoContable extends Model
{
    protected $table = 'cat_rubro_no_contable';

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
