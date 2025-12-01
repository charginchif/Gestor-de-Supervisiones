<?php

namespace App\Http\Controllers;

use App\Models\SolicitudInscripcion;
use App\Models\InscripcionGrupo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Utils\RespuestaAPI;
use Illuminate\Support\Facades\DB;



class SolicitudInscripcionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/coordinador-solicitud-inscripcion",
     *     summary="Obtener las solicitudes de inscripción pendientes de los grupos del coordinador",
     *     tags={"Solicitudes de Inscripción"},
     *     security={{"jwt":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Solicitudes obtenidas correctamente",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/SolicitudInscripcion")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $coordinador = Auth::user();

        $solicitudes = SolicitudInscripcion::with('alumno', 'grupo')
            ->whereHas('grupo', function ($query) use ($coordinador) {
                $query->where('id_coordinador', $coordinador->id);
            })
            ->get();

        return RespuestaAPI::success($solicitudes, 'Solicitudes obtenidas correctamente.');
    }

    /**
     * @OA\Post(
     *     path="/solicitudes-inscripcion",
     *     summary="Crear una nueva solicitud de inscripción",
     *     tags={"Solicitudes de Inscripción"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id_grupo"},
     *             @OA\Property(property="id_grupo", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Solicitud de inscripción creada correctamente",
     *         @OA\JsonContent(ref="#/components/schemas/SolicitudInscripcion")
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Ya existe una solicitud de inscripción pendiente para este grupo o el alumno ya está inscrito"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_grupo' => 'required|integer|exists:grupo,id_grupo',
        ]);

        $alumno = Auth::user();

        $solicitudExistente = SolicitudInscripcion::where('id_alumno', $alumno->id)
            ->where('id_grupo', $request->id_grupo)
            ->whereIn('estado', ['pendiente', 'aprobada'])
            ->first();

        if ($solicitudExistente) {
            if($solicitudExistente->estado == 'pendiente'){
                return RespuestaAPI::error('Ya existe una solicitud de inscripción pendiente para este grupo.', 409);
            } else {
                return RespuestaAPI::error('El alumno ya está inscrito en este grupo.', 409);
            }
        }

        $inscripcionExistente = InscripcionGrupo::where('id_alumno', $alumno->id)
            ->where('id_grupo', $request->id_grupo)
            ->first();

        if ($inscripcionExistente) {
            return RespuestaAPI::error('El alumno ya está inscrito en este grupo.',409);
        }

        $solicitud = SolicitudInscripcion::create([
            'id_alumno' => $alumno->id,
            'id_grupo' => $request->id_grupo,
        ]);

        return RespuestaAPI::success($solicitud, 'Solicitud de inscripción creada correctamente.', 201);
    }

    /**
     * @OA\Post(
     *     path="/coordinador-solicitud-inscripcion/{id}/aprobar",
     *     summary="Aprobar una solicitud de inscripción",
     *     tags={"Solicitudes de Inscripción"},
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la solicitud de inscripción",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Inscripción aprobada y realizada correctamente",
     *         @OA\JsonContent(ref="#/components/schemas/InscripcionGrupo")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Solicitud no encontrada"
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="La solicitud ya ha sido gestionada o el alumno ya está inscrito"
     *     )
     * )
     */
    public function approve($id)
    {
        $solicitud = SolicitudInscripcion::findOrFail($id);

        if ($solicitud->estado !== 'pendiente') {
            return RespuestaAPI::error('La solicitud ya ha sido gestionada.', 409);
        }

        $inscripcionExistente = InscripcionGrupo::where('id_alumno', $solicitud->id_alumno)
            ->where('id_grupo', $solicitud->id_grupo)
            ->first();

        if ($inscripcionExistente) {
            $solicitud->update(['estado' => 'rechazada']);
            return RespuestaAPI::error('El alumno ya está inscrito en este grupo.',409);
        }

        $inscripcion = InscripcionGrupo::create([
            'id_alumno' => $solicitud->id_alumno,
            'id_grupo' => $solicitud->id_grupo,
        ]);

        $solicitud->update(['estado' => 'aprobada']);

        return RespuestaAPI::success($inscripcion, 'Inscripción aprobada y realizada correctamente.');
    }

    /**
     * @OA\Delete(
     *     path="/coordinador-solicitud-inscripcion/{id}/rechazar",
     *     summary="Rechazar una solicitud de inscripción",
     *     tags={"Solicitudes de Inscripción"},
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la solicitud de inscripción",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Solicitud de inscripción rechazada"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Solicitud no encontrada"
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="La solicitud ya ha sido gestionada"
     *     )
     * )
     */
    public function reject($id)
    {
        $solicitud = SolicitudInscripcion::findOrFail($id);

        if ($solicitud->estado !== 'pendiente') {
            return RespuestaAPI::error('La solicitud ya ha sido gestionada.', 409);
        }

        $solicitud->delete();

        return RespuestaAPI::success(null, 'Solicitud de inscripción rechazada.');
    }

    /**
     * @OA\Get(
     *     path="/mis-solicitudes",
     *     summary="Obtener mis solicitudes de inscripción (alumno)",
     *     tags={"Solicitudes de Inscripción"},
     *     @OA\Response(
     *         response=200,
     *         description="Mis solicitudes de inscripción obtenidas correctamente",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/SolicitudInscripcion")
     *         )
     *     )
     * )
     */
    public function getMisSolicitudes()
    {
        $alumno = Auth::user();

        $solicitudes = SolicitudInscripcion::with('grupo')
            ->where('id_alumno', $alumno->id)
            ->get();

        return RespuestaAPI::success($solicitudes, 'Mis solicitudes de inscripción obtenidas correctamente.');
    }

    /**
     * @OA\Delete(
     *     path="/solicitudes-inscripcion/{id}/cancelar",
     *     summary="Cancelar una solicitud de inscripción (alumno)",
     *     tags={"Solicitudes de Inscripción"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la solicitud de inscripción",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Solicitud de inscripción cancelada correctamente"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="No tienes permiso para cancelar esta solicitud"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Solicitud no encontrada"
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="No puedes cancelar una solicitud que ya ha sido procesada"
     *     )
     * )
     */
    public function cancel($id)
    {
        $alumno = Auth::user();

        $solicitud = SolicitudInscripcion::findOrFail($id);

        if ($solicitud->id_alumno !== $alumno->id) {
            return RespuestaAPI::error('No tienes permiso para cancelar esta solicitud.', 403);
        }

        if ($solicitud->estado !== 'pendiente') {
            return RespuestaAPI::error('No puedes cancelar una solicitud que ya ha sido procesada.', 409);
        }

        $solicitud->delete();

        return RespuestaAPI::success(null, 'Solicitud de inscripción cancelada correctamente.');
    }
}
