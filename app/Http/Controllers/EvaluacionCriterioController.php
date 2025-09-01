<?php

namespace App\Http\Controllers;

use App\Models\EvaluacionCriterio;
use Illuminate\Http\Request;
use App\Utils\RespuestaAPI;

class EvaluacionCriterioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:jwt');
    }

    public function index()
    {
        $evaluacionCriterios = EvaluacionCriterio::all();
        return RespuestaAPI::exito('Listado de criterios de evaluación', $evaluacionCriterios);
    }

    public function show($id)
    {
        $evaluacionCriterio = EvaluacionCriterio::find($id);
        if (!$evaluacionCriterio) {
            return RespuestaAPI::error('Criterio de evaluación no encontrado', 404);
        }
        return RespuestaAPI::exito('Criterio de evaluación encontrado', $evaluacionCriterio);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'id_evaluacion' => 'required|integer',
            'id_evacriterio' => 'required|integer',
            'estado' => 'required|string|max:255',
        ]);

        $evaluacionCriterio = EvaluacionCriterio::create($request->all());

        return RespuestaAPI::exito('Criterio de evaluación creado exitosamente', $evaluacionCriterio, 201);
    }

    public function update(Request $request, $id)
    {
        $evaluacionCriterio = EvaluacionCriterio::find($id);
        if (!$evaluacionCriterio) {
            return RespuestaAPI::error('Criterio de evaluación no encontrado', 404);
        }

        $this->validate($request, [
            'id_evaluacion' => 'required|integer',
            'id_evacriterio' => 'required|integer',
            'estado' => 'required|string|max:255',
        ]);

        $evaluacionCriterio->update($request->all());

        return RespuestaAPI::exito('Criterio de evaluación actualizado exitosamente', $evaluacionCriterio);
    }

    public function destroy($id)
    {
        $evaluacionCriterio = EvaluacionCriterio::find($id);
        if (!$evaluacionCriterio) {
            return RespuestaAPI::error('Criterio de evaluación no encontrado', 404);
        }

        $evaluacionCriterio->delete();

        return RespuestaAPI::exito('Criterio de evaluación eliminado exitosamente');
    }
}
