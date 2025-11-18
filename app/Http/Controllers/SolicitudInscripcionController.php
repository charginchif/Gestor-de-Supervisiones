<?php

namespace App\Http\Controllers;

use App\Models\SolicitudInscripcion;
use App\Models\InscripcionGrupo;
use App\Models\VwCoordGrupo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Utils\RespuestaAPI;



class SolicitudInscripcionController extends Controller
{
    public function index()
    {
        $coordinador = Auth::user();
        $gruposCoordinador = VwCoordGrupo::where('id_coordinador', $coordinador->id)->pluck('id_grupo');

        $solicitudes = SolicitudInscripcion::with(['alumno', 'grupo'])
            ->whereIn('id_grupo', $gruposCoordinador)
            ->where('estado', 'pendiente')
            ->get();
        
        return RespuestaAPI::success($solicitudes, 'Solicitudes obtenidas correctamente');
    }

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

    public function reject($id)
    {
        $solicitud = SolicitudInscripcion::findOrFail($id);

        if ($solicitud->estado !== 'pendiente') {
            return RespuestaAPI::error('La solicitud ya ha sido gestionada.', 409);
        }

        $solicitud->delete();

        return RespuestaAPI::success(null, 'Solicitud de inscripción rechazada.');
    }

    public function getMisSolicitudes()
    {
        $alumno = Auth::user();

        $solicitudes = SolicitudInscripcion::with('grupo')
            ->where('id_alumno', $alumno->id)
            ->get();

        return RespuestaAPI::success($solicitudes, 'Mis solicitudes de inscripción obtenidas correctamente.');
    }

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
