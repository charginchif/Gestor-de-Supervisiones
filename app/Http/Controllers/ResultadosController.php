<?php

namespace App\Http\Controllers;

use App\Utils\RespuestaAPI;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ResultadosController extends Controller
{
    /**
     * @OA\Get(
     *     path="/resultados/supervision",
     *     summary="Obtener los resultados de la supervisión por rubro",
     *     tags={"Resultados"},
     *     @OA\Response(
     *         response=200,
     *         description="Resultados de la supervisión",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/VwSupervisionRubroPorcentaje")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener los resultados de la supervisión"
     *     )
     * )
     */
    public function getResultadosSupervision(Request $request)
    {
        $this->validate($request, [
            'anio' => 'required|integer',
            'periodo' => 'required|string|max:1',
        ]);
        $anio = $request->input('anio');
        $periodo = (string) $request->input('periodo');
        try {
            $resultados = DB::table('vw_supervision_resumen')
                ->where('anio', $anio)
                ->where('periodo', $periodo)->get();
            return RespuestaAPI::exito('Resultados de la supervisión', $resultados);
        } catch (\Illuminate\Database\QueryException $e) {
            return RespuestaAPI::error('Error al obtener los resultados de la supervisión: ' . $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/resultados/evaluacion-docente",
     *     summary="Obtener los resultados de la evaluación docente",
     *     tags={"Resultados"},
     *     @OA\Response(
     *         response=200,
     *         description="Resultados de la evaluación docente",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/VwEvalDocentePorcentajeTotal")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener los resultados de la evaluación docente"
     *     )
     * )
     */
    public function getResultadosEvaluacion()
    {
        try {
            $resultados = DB::table('vw_supervision_calificacion')->get();
            return RespuestaAPI::exito('Resultados de la evaluación docente', $resultados);
        } catch (\Illuminate\Database\QueryException $e) {
            return RespuestaAPI::error('Error al obtener los resultados de la evaluación docente: ' . $e->getMessage(), 500);
        }
    }
}
