<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RubroSupervisionContable
 *
 * @package App\Models
 *
 * This model represents a Rubro (Category/Heading) for countable supervision in the system.
 */
/**
 * @OA\Schema(
 *     title="RubroSupervisionContable",
 *     description="Modelo de Rubro de Supervisión Contable",
 *     @OA\Xml(
 *         name="RubroSupervisionContable"
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
 *         example="Facturación"
 *     )
 * )
 */
class RubroSupervisionContable extends Model
{
    protected $table = 'cat_rubro';

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
