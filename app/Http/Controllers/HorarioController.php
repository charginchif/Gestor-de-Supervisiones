<?php

namespace App\Http\Controllers;

use App\Models\VwAlumnoHorario;
use App\Utils\RespuestaAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function index()
    {
        try {
            $horarios = DB::table('vw_admin_horarios')->get();
            return RespuestaAPI::exito('Horarios obtenidos con éxito', $horarios);
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al obtener los horarios: ' . $e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'id_ciclo' => 'required|integer',
                'id_cat_dia' => 'required|integer',
                'hora_inicio' => 'required',
                'hora_fin' => 'required',
            ]);

            DB::statement('CALL sp_horario_insertar(?, ?, ?, ?)', [
                $request->input('id_ciclo'),
                $request->input('id_cat_dia'),
                $request->input('hora_inicio'),
                $request->input('hora_fin'),
            ]);

            return RespuestaAPI::exito('Horario creado con éxito', null, 201);
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al crear el horario: ' . $e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::statement('CALL sp_horario_actualizar(?, ?, ?, ?, ?)', [
                $id,
                $request->input('id_ciclo'),
                $request->input('id_cat_dia'),
                $request->input('hora_inicio'),
                $request->input('hora_fin'),
            ]);

            return RespuestaAPI::exito('Horario actualizado con éxito', null);
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al actualizar el horario: ' . $e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::statement('CALL sp_horario_eliminar(?)', [$id]);

            return RespuestaAPI::exito('Horario eliminado con éxito', null);
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al eliminar el horario: ' . $e->getMessage(), 500);
        }
    }
}
