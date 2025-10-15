<?php

namespace App\Http\Controllers;

use App\Models\VwAlumnoHorario;
use App\Utils\RespuestaAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HorarioController extends Controller
{
    public function getMiHorario(Request $request)
    {
        try {
            $user = Auth::user();
            $horario = VwAlumnoHorario::where('id_alumno', $user->id)->get();

            return RespuestaAPI::exito('Horario obtenido con éxito', $horario);
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al obtener el horario: ' . $e->getMessage(), 500);
        }
    }
}
