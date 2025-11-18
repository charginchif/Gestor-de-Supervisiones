<?php

namespace App\Http\Controllers;

use App\Models\SolicitudInscripcion;
use App\Models\VwCoordGrupo;
use Illuminate\Support\Facades\Auth;
use App\Utils\RespuestaAPI;

class HistorialSolicitudesController extends Controller
{
    public function getAll()
    {
        $coordinador = Auth::user();
        $gruposCoordinador = VwCoordGrupo::where('id_coordinador', $coordinador->id)->pluck('id_grupo');

        $solicitudes = SolicitudInscripcion::with(['alumno', 'grupo'])
            ->whereIn('id_grupo', $gruposCoordinador)
            ->get();
        
        return RespuestaAPI::success($solicitudes, 'Todas las solicitudes obtenidas correctamente');
    }

    public function getApproved()
    {
        $coordinador = Auth::user();
        $gruposCoordinador = VwCoordGrupo::where('id_coordinador', $coordinador->id)->pluck('id_grupo');

        $solicitudes = SolicitudInscripcion::with(['alumno', 'grupo'])
            ->whereIn('id_grupo', $gruposCoordinador)
            ->where('estado', 'aprobada')
            ->get();
        
        return RespuestaAPI::success($solicitudes, 'Solicitudes aprobadas obtenidas correctamente');
    }

    public function getRejected()
    {
        $coordinador = Auth::user();
        $gruposCoordinador = VwCoordGrupo::where('id_coordinador', $coordinador->id)->pluck('id_grupo');

        $solicitudes = SolicitudInscripcion::with(['alumno', 'grupo'])
            ->whereIn('id_grupo', $gruposCoordinador)
            ->where('estado', 'rechazada')
            ->get();
        
        return RespuestaAPI::success($solicitudes, 'Solicitudes rechazadas obtenidas correctamente');
    }
}
