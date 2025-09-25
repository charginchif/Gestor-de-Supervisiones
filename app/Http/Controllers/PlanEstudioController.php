<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Utils\RespuestaAPI;


class PlanEstudioController extends Controller
{
    public function index($id_carrera)
    {
        $planEstudio = DB::table('vw_admin_plan_estudio')->where('id_carrera', $id_carrera)->get();
        return RespuestaAPI::exito('Éxito', $planEstudio);
    }

    public function indexAll()
    {
        $planEstudio = DB::table('vw_admin_plan_estudio')->get();
        return RespuestaAPI::exito('Éxito', $planEstudio);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_carrera' => 'required|integer',
            'id_cat_nivel' => 'required|integer',
            'id_modalidad' => 'required|integer',
            'ids_materias_csv' => 'required|string',
        ]);

        try {
            $resultado = DB::select(
                'CALL sp_plan_estudio_insertar(?, ?, ?, ?)',
                [
                    $request->id_carrera,
                    $request->id_cat_nivel,
                    $request->id_modalidad,
                    $request->ids_materias_csv,
                ]
            );
            return RespuestaAPI::exito('Éxito', $resultado, 201);
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al insertar el plan de estudio: ' . $e->getMessage(), 500);
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'id_carrera' => 'required|integer',
            'id_cat_nivel' => 'required|integer',
            'id_modalidad' => 'required|integer',
            'ids_materias_csv' => 'required|string',
        ]);

        try {
            $resultado = DB::select(
                'CALL sp_plan_estudio_actualizar(?, ?, ?, ?)',
                [
                    $request->id_carrera,
                    $request->id_cat_nivel,
                    $request->id_modalidad,
                    $request->ids_materias_csv,
                ]
            );
            return RespuestaAPI::exito('Éxito', $resultado);
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al actualizar el plan de estudio: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'id_carrera' => 'required|integer',
            'id_materia' => 'required|integer',
            'id_cat_nivel' => 'required|integer',
            'id_modalidad' => 'required|integer',
        ]);

        try {
            DB::statement(
                'CALL sp_plan_estudio_eliminar(?, ?, ?, ?)',
                [
                    $request->id_carrera,
                    $request->id_materia,
                    $request->id_cat_nivel,
                    $request->id_modalidad,
                ]
            );
            return RespuestaAPI::exito('Materia eliminada del plan de estudio con éxito');
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al eliminar la materia del plan de estudio: ' . $e->getMessage(), 500);
        }
    }
}
