<?php

namespace App\Http\Controllers;

use App\Models\SolicitudInscripcion;
use Illuminate\Support\Facades\Auth;
use App\Utils\RespuestaAPI;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class HistorialSolicitudesController extends Controller
{
    /**
     * @OA\Get(
     *     path="/coordinador-solicitud-inscripcion/buscar",
     *     summary="Buscar solicitudes de inscripción",
     *     tags={"Historial de Solicitudes"},
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         required=true,
     *         description="Término de búsqueda",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Solicitudes encontradas",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/SolicitudInscripcion")
     *         )
     *     )
     * )
     */
    public function search(Request $request)
    {
        $coordinador = Auth::user();
        $gruposCoordinador = DB::table('carrera_coordinador')->join('grupo', 'carrera_coordinador.id_carrera', '=', 'grupo.id_carrera')->where('carrera_coordinador.id_coordinador', $coordinador->id)->pluck('grupo.id_grupo');

        $query = $request->input('q');

        $solicitudes = SolicitudInscripcion::with(['alumno', 'grupo'])
            ->whereIn('id_grupo', $gruposCoordinador)
            ->where(function ($q) use ($query) {
                $q->whereHas('alumno', function ($q) use ($query) {
                    $q->where('nombre', 'like', "%{$query}%")
                        ->orWhere('apellido_paterno', 'like', "%{$query}%")
                        ->orWhere('apellido_materno', 'like', "%{$query}%")
                        ->orWhere('email', 'like', "%{$query}%");
                })
                ->orWhereHas('grupo', function ($q) use ($query) {
                    $q->where('nombre_grupo', 'like', "%{$query}%");
                });
            })
            ->get();

        return RespuestaAPI::success($solicitudes, 'Solicitudes encontradas');
    }
    
    /**
     * @OA\Get(
     *     path="/coordinador-solicitud-inscripcion/todas",
     *     summary="Obtener todas las solicitudes de inscripción de los grupos del coordinador",
     *     tags={"Historial de Solicitudes"},
     *     security={{"jwt":{}}},
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
        $gruposCoordinador = DB::table('carrera_coordinador')->join('grupo', 'carrera_coordinador.id_carrera', '=', 'grupo.id_carrera')->where('carrera_coordinador.id_coordinador', $coordinador->id)->pluck('grupo.id_grupo');

        $solicitudes = SolicitudInscripcion::with(['alumno', 'grupo'])
            ->whereIn('id_grupo', $gruposCoordinador)
            ->get();
        
        return RespuestaAPI::success($solicitudes, 'Todas las solicitudes obtenidas correctamente');
    }

    /**
     * @OA\Get(
     *     path="/coordinador-solicitud-inscripcion/aprobadas",
     *     summary="Obtener las solicitudes de inscripción aprobadas de los grupos del coordinador",
     *     tags={"Historial de Solicitudes"},
     *     security={{"jwt":{}}},
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
        $gruposCoordinador = DB::table('carrera_coordinador')->join('grupo', 'carrera_coordinador.id_carrera', '=', 'grupo.id_carrera')->where('carrera_coordinador.id_coordinador', $coordinador->id)->pluck('grupo.id_grupo');

        $solicitudes = SolicitudInscripcion::with(['alumno', 'grupo'])
            ->whereIn('id_grupo', $gruposCoordinador)
            ->where('estado', 'aprobada')
            ->get();
        
        return RespuestaAPI::success($solicitudes, 'Solicitudes aprobadas obtenidas correctamente');
    }

    /**
     * @OA\Get(
     *     path="/coordinador-solicitud-inscripcion/rechazadas",
     *     summary="Obtener las solicitudes de inscripción rechazadas de los grupos del coordinador",
     *     tags={"Historial de Solicitudes"},
     *     security={{"jwt":{}}},
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
        $gruposCoordinador = DB::table('carrera_coordinador')->join('grupo', 'carrera_coordinador.id_carrera', '=', 'grupo.id_carrera')->where('carrera_coordinador.id_coordinador', $coordinador->id)->pluck('grupo.id_grupo');

        $solicitudes = SolicitudInscripcion::with(['alumno', 'grupo'])
            ->whereIn('id_grupo', $gruposCoordinador)
            ->where('estado', 'rechazada')
            ->get();
        
        return RespuestaAPI::success($solicitudes, 'Solicitudes rechazadas obtenidas correctamente');
    }
}
