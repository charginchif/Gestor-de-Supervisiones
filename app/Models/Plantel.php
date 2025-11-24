<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class Plantel
 *
 * @package App\Models
 *
 * This model represents a Plantel (Campus/School) in the system.
 * It provides methods to manage campus information and retrieve associated data.
 */
/**
 * @OA\Schema(
 *     schema="Plantel",
 *     title="Plantel",
 *     description="Modelo de Plantel",
 *     properties={
 *         @OA\Property(
 *             property="id_plantel",
 *             type="integer",
 *             description="ID del plantel",
 *             readOnly="true"
 *         ),
 *         @OA\Property(
 *             property="nombre",
 *             type="string",
 *             description="Nombre del plantel",
 *             maxLength=255
 *         ),
 *         @OA\Property(
 *             property="ubicacion",
 *             type="string",
 *             description="Ubicación del plantel",
 *             maxLength=255
 *         )
 *     }
 * )
 */
class Plantel extends Model
{
    protected $table = 'plantel';
    protected $primaryKey = 'id_plantel';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'ubicacion',
    ];

    /**
     * Retrieves the campuses associated with a specific coordinator from the 'vw_admin_planteles' view.
     *
     * @param int $coordinadorId The ID of the coordinator.
     * @return \Illuminate\Database\Eloquent\Collection A collection of Plantel models.
     */
    public static function getPlantelesByCoordinador(int $coordinadorId)
    {
        $carreraIds = DB::table('vw_coord_carreras')
            ->where('id_coordinador', $coordinadorId)
            ->pluck('id_carrera');

        if ($carreraIds->isEmpty()) {
            return collect(); // Returns an empty collection if the coordinator has no assigned careers.
        }

        $plantelIds = DB::table('vw_admin_plantel_carrera')
            ->whereIn('id_carrera', $carreraIds)
            ->pluck('id_plantel')
            ->unique();

        if ($plantelIds->isEmpty()) {
            return collect(); // Returns an empty collection if the careers are not associated with any campus.
        }

        // Use the current model (Plantel) to get the details of the campuses.
        return self::whereIn('id_plantel', $plantelIds)->get();
    }
}