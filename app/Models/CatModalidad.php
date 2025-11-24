<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="CatModalidad",
 *     description="Modelo de Catálogo de Modalidad",
 *     @OA\Xml(
 *         name="CatModalidad"
 *     ),
 *     properties={
 *         @OA\Property(
 *             property="id",
 *             title="ID de la Modalidad",
 *             description="Identificador único de la modalidad",
 *             type="integer",
 *             example=1,
 *             readOnly="true"
 *         ),
 *         @OA\Property(
 *             property="nombre",
 *             title="Nombre",
 *             description="Nombre de la modalidad",
 *             type="string",
 *             example="Escolarizada",
 *             maxLength=255
 *         )
 *     }
 * )
 */
class CatModalidad extends Model
{
    protected $table = 'cat_modalidad';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
    ];
}
