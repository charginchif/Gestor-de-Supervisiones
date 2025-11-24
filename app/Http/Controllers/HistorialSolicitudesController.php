<?php

namespace App\Http\Controllers;

use App\Models\SolicitudInscripcion;
use App\Models\VwCoordGrupo;
use Illuminate\Support\Facades\Auth;
use App\Utils\RespuestaAPI;

class HistorialSolicitudesController extends Controller
{
    /**
     * @OA\Get(
     *     path="/historial-solicitudes",
     *     summary="Obtener todas las solicitudes de inscripción de los grupos del coordinador",
     *     tags={"Historial de Solicitudes"},
     *     @OA\Response(
     *         response=200,
     *         description="Todas las solicitudes obtenidas correctamente",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/SolicitudInscripcion")
     *         )
     *     )
     * )
     */
    public function getAll()
    {
        $coordinador = Auth::user();
        $gruposCoordinador = VwCoordGrupo::where('id_coordinador', $coordinador->id)->pluck('id_grupo');

        $solicitudes = SolicitudInscripcion::with(['alumno', 'grupo'])
            ->whereIn('id_grupo', $gruposCoordinador)
            ->get();
        
        return RespuestaAPI::success($solicitudes, 'Todas las solicitudes obtenidas correctamente');
    }

    /**
     * @OA\Get(
     *     path="/historial-solicitudes/aprobadas",
     *     summary="Obtener las solicitudes de inscripción aprobadas de los grupos del coordinador",
     *     tags={"Historial de Solicitudes"},
     *     @OA\Response(
     *         response=200,
     *         description="Solicitudes aprobadas obtenidas correctamente",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/SolicitudInscripcion")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/historial-solicitudes/rechazadas",
     *     summary="Obtener las solicitudes de inscripción rechazadas de los grupos del coordinador",
     *     tags={"Historial de Solicitudes"},
     *     @OA\Response(
     *         response=200,
     *         description="Solicitudes rechazadas obtenidas correctamente",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/SolicitudInscripcion")
     *         )
     *     )
     * )
     */
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
