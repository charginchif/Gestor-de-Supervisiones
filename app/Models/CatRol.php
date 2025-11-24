<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="CatRol",
 *     description="Modelo de Catálogo de Rol",
 *     @OA\Xml(
 *         name="CatRol"
 *     ),
 *     properties={
 *         @OA\Property(
 *             property="id",
 *             title="ID del Rol",
 *             description="Identificador único del rol",
 *             type="integer",
 *             example=1,
 *             readOnly="true"
 *         ),
 *         @OA\Property(
 *             property="nombre",
 *             title="Nombre",
 *             description="Nombre del rol",
 *             type="string",
 *             example="Administrador",
 *             maxLength=255
 *         )
 *     }
 * )
 */
class CatRol extends Model
{
    protected $table = 'cat_rol';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = ['nombre'];
}
