<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use Illuminate\Http\Request;
use App\Utils\RespuestaAPI;

class HorarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:jwt');
    }

    public function index()
    {
        $horarios = Horario::all();
        return RespuestaAPI::exito('Listado de horarios', $horarios);
    }

    public function show($id)
    {
        $horario = Horario::find($id);
        if (!$horario) {
            return RespuestaAPI::error('Horario no encontrado', 404);
        }
        return RespuestaAPI::exito('Horario encontrado', $horario);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'id_ciclo' => 'required|integer|exists:ciclo_escolar,id_ciclo',
            'id_dia' => 'required|integer',
            'hora_inicio' => 'required',
            'hora_fin' => 'required',
        ]);

        $horario = Horario::create($request->all());

        return RespuestaAPI::exito('Horario creado exitosamente', $horario, 201);
    }

    public function update(Request $request, $id)
    {
        $horario = Horario::find($id);
        if (!$horario) {
            return RespuestaAPI::error('Horario no encontrado', 404);
        }

        $this->validate($request, [
            'id_ciclo' => 'required|integer|exists:ciclo_escolar,id_ciclo',
            'id_dia' => 'required|integer',
            'hora_inicio' => 'required',
            'hora_fin' => 'required',
        ]);

        $horario->update($request->all());

        return RespuestaAPI::exito('Horario actualizado exitosamente', $horario);
    }

    public function destroy($id)
    {
        $horario = Horario::find($id);
        if (!$horario) {
            return RespuestaAPI::error('Horario no encontrado', 404);
        }

        $horario->delete();

        return RespuestaAPI::exito('Horario eliminado exitosamente');
    }
}
