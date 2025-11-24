<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Grupo
 *
 * @package App\Models
 *
 * This model represents a Grupo (Group) in the system, primarily for academic grouping.
 */
/**
 * @OA\Schema(
 *     title="Grupo",
 *     description="Modelo de Grupo",
 *     @OA\Xml(
 *         name="Grupo"
 *     ),
 *     properties={
 *         @OA\Property(
 *             property="id_grupo",
 *             title="ID del Grupo",
 *             description="Identificador único del grupo",
 *             type="integer",
 *             example=1,
 *             readOnly="true"
 *         ),
 *         @OA\Property(
 *             property="acronimo",
 *             title="Acrónimo",
 *             description="Acrónimo del grupo",
 *             type="string",
 *             example="G-01",
 *             maxLength=50
 *         ),
 *         @OA\Property(
 *             property="id_ciclo",
 *             title="ID del Ciclo",
 *             description="Identificador único del ciclo asociado",
 *             type="integer",
 *             example=1
 *         ),
 *         @OA\Property(
 *             property="id_turno",
 *             title="ID del Turno",
 *             description="Identificador único del turno asociado",
 *             type="integer",
 *             example=1
 *         ),
 *         @OA\Property(
 *             property="id_modalidad",
 *             title="ID de la Modalidad",
 *             description="Identificador único de la modalidad asociada",
 *             type="integer",
 *             example=1
 *         ),
 *          @OA\Property(
 *             property="id_nivel",
 *             title="ID del Nivel",
 *             description="Identificador único del nivel asociado",
 *             type="integer",
 *             example=1
 *         ),
 *          @OA\Property(
 *             property="id_carrera",
 *             title="ID de la Carrera",
 *             description="Identificador único de la carrera asociada",
 *             type="integer",
 *             example=1
 *         ),
 *          @OA\Property(
 *             property="codigo_inscripcion",
 *             title="Código de Inscripción",
 *             description="Código de inscripción para el grupo",
 *             type="string",
 *             example="XYZ123",
 *             maxLength=100
 *         )
 *     }
 * )
 */
class Grupo extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'grupo';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_grupo';

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
        'acronimo',
        'id_ciclo',
        'id_turno',
        'id_modalidad',
        'id_nivel',
        'id_carrera',
        'codigo_inscripcion',
    ];
}
