<?php

namespace App\Http\Controllers;

use App\Utils\RespuestaAPI;
use Illuminate\Support\Facades\DB;

class ResultadosController extends Controller
{
    /**
     * Devuelve los resultados de la supervisión por rubro.
     */
    public function getResultadosSupervision()
    {
        try {
            $resultados = DB::table('vw_supervision_rubro_porcentaje')->get();
            return RespuestaAPI::exito('Resultados de la supervisión', $resultados);
        } catch (\Illuminate\Database\QueryException $e) {
            return RespuestaAPI::error('Error al obtener los resultados de la supervisión: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Devuelve los resultados de la evaluación docente.
     */
    public function getResultadosEvaluacion()
    {
        try {
            $resultados = DB::table('vw_eval_docente_porcentaje_total')->get();
            return RespuestaAPI::exito('Resultados de la evaluación docente', $resultados);
        } catch (\Illuminate\Database\QueryException $e) {
            return RespuestaAPI::error('Error al obtener los resultados de la evaluación docente: ' . $e->getMessage(), 500);
        }
    }
}
