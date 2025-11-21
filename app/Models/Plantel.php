<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB; // Added this line

class Plantel extends Model
{
    protected $table = 'plantel';
    protected $primaryKey = 'id_plantel';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'ubicacion',
    ];

    /**
     * Obtiene los planteles asociados a un coordinador específico desde la vista vw_admin_planteles.
     *
     * @param int $coordinadorId
     * @return \Illuminate\Support\Collection
     */
    public static function getPlantelesByCoordinador(int $coordinadorId)
    {
        $carreraIds = DB::table('vw_coord_carreras')
            ->where('id_coordinador', $coordinadorId)
            ->pluck('id_carrera');

        if ($carreraIds->isEmpty()) {
            return collect(); // Devuelve una colección vacía si el coordinador no tiene carreras asignadas.
        }

        $plantelIds = DB::table('vw_admin_plantel_carrera')
            ->whereIn('id_carrera', $carreraIds)
            ->pluck('id_plantel')
            ->unique();

        if ($plantelIds->isEmpty()) {
            return collect(); // Devuelve una colección vacía si las carreras no están asociadas a ningún plantel.
        }

        // Usamos el modelo actual (Plantel) para obtener los detalles de los planteles.
        return self::whereIn('id_plantel', $plantelIds)->get();
    }
}