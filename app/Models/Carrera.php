<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Carrera",
 *     title="Carrera",
 *     description="Modelo de Carrera",
 *     properties={
 *         @OA\Property(
 *             property="id_carrera",
 *             type="integer",
 *             description="ID de la carrera",
 *             readOnly="true"
 *         ),
 *         @OA\Property(
 *             property="nombre",
 *             type="string",
 *             description="Nombre de la carrera",
 *             maxLength=255
 *         ),
 *         @OA\Property(
 *             property="id_incorporacion",
 *             type="integer",
 *             description="ID de incorporación"
 *         )
 *     }
 * )
 */
class Carrera extends Model
{
    protected $table = 'carrera';
    protected $primaryKey = 'id_carrera';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'id_incorporacion',
    ];
}
