<?php

namespace App\Http\Controllers;

use App\Models\SupervisionCriterio;
use Illuminate\Http\Request;
use App\Utils\RespuestaAPI;

class SupervisionCriterioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:jwt');
    }

    public function index()
    {
        $supervisionCriterios = SupervisionCriterio::all();
        return RespuestaAPI::exito('Listado de criterios de supervisión', $supervisionCriterios);
    }

    public function show($id)
    {
        $supervisionCriterio = SupervisionCriterio::find($id);
        if (!$supervisionCriterio) {
            return RespuestaAPI::error('Criterio de supervisión no encontrado', 404);
        }
        return RespuestaAPI::exito('Criterio de supervisión encontrado', $supervisionCriterio);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'id_supervision' => 'required|integer',
            'id_supcriterio' => 'required|integer',
            'estado' => 'required|string|max:255',
        ]);

        $supervisionCriterio = SupervisionCriterio::create($request->all());

        return RespuestaAPI::exito('Criterio de supervisión creado exitosamente', $supervisionCriterio, 201);
    }

    public function update(Request $request, $id)
    {
        $supervisionCriterio = SupervisionCriterio::find($id);
        if (!$supervisionCriterio) {
            return RespuestaAPI::error('Criterio de supervisión no encontrado', 404);
        }

        $this->validate($request, [
            'id_supervision' => 'required|integer',
            'id_supcriterio' => 'required|integer',
            'estado' => 'required|string|max:255',
        ]);

        $supervisionCriterio->update($request->all());

        return RespuestaAPI::exito('Criterio de supervisión actualizado exitosamente', $supervisionCriterio);
    }

    public function destroy($id)
    {
        $supervisionCriterio = SupervisionCriterio::find($id);
        if (!$supervisionCriterio) {
            return RespuestaAPI::error('Criterio de supervisión no encontrado', 404);
        }

        $supervisionCriterio->delete();

        return RespuestaAPI::exito('Criterio de supervisión eliminado exitosamente');
    }
}
