<?php

namespace App\Http\Controllers;

use App\Utils\RespuestaAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgendaSupervisionController extends Controller
{
    public function index()
    {
        try {
            $agendas = DB::table('vw_coord_agenda_supervision')->get();
            return RespuestaAPI::exito('Agendas de supervisión obtenidas con éxito', $agendas);
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al obtener las agendas de supervisión: ' . $e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'fecha' => 'required|date',
                'id_coordinador' => 'required|integer',
                'id_horario' => 'required|integer',
                'estado' => 'required|integer',
            ]);

            $result = DB::select('CALL sp_agenda_supervision_insertar(?, ?, ?, ?)', [
                $request->input('fecha'),
                $request->input('id_coordinador'),
                $request->input('id_horario'),
                $request->input('estado'),
            ]);

            return RespuestaAPI::exito('Agenda de supervisión creada con éxito', $result, 201);
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al crear la agenda de supervisión: ' . $e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $result = DB::select('CALL sp_agenda_supervision_actualizar(?, ?, ?, ?, ?)', [
                $id,
                $request->input('fecha'),
                $request->input('id_coordinador'),
                $request->input('id_horario'),
                $request->input('estado'),
            ]);

            return RespuestaAPI::exito('Agenda de supervisión actualizada con éxito', $result);
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al actualizar la agenda de supervisión: ' . $e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::statement('CALL sp_agenda_supervision_eliminar(?)', [$id]);

            return RespuestaAPI::exito('Agenda de supervisión eliminada con éxito', null);
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al eliminar la agenda de supervisión: ' . $e->getMessage(), 500);
        }
    }
}
