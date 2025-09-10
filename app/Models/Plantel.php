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
        return DB::table('vw_admin_planteles')
                 ->where('id_coordinador', $coordinadorId)
                 ->get();
    }
}