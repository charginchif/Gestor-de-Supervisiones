<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utils\RespuestaAPI;
use Illuminate\Support\Facades\DB;

class CriterioEvaluacionController extends Controller
{
    public function index()
    {
        try {
            $criterios = DB::select('SELECT * FROM criterios_evaluacion');
            return RespuestaAPI::exito('Listado de criterios de evaluación', $criterios);
        } catch (\Illuminate\Database\QueryException $e) {
            return RespuestaAPI::error('Error al obtener los criterios: ' . $e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        try {
            $criterio = DB::select('SELECT * FROM criterios_evaluacion WHERE id_evacriterio = ?', [$id]);
            if (empty($criterio)) {
                return RespuestaAPI::error('Criterio de evaluación no encontrado', 404);
            }
            return RespuestaAPI::exito('Criterio de evaluación encontrado', $criterio[0]);
        } catch (\Illuminate\Database\QueryException $e) {
            return RespuestaAPI::error('Error al obtener el criterio: ' . $e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'id_rubro' => 'required|integer',
            'descripcion' => 'required|string'
        ]);

        try {
            DB::statement(
                'CALL sp_criterio_eval_insertar(?, ?)',
                [$request->input('id_rubro'), $request->input('descripcion')]
            );

            return RespuestaAPI::exito('Criterio de evaluación creado exitosamente', null, 201);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '45000') {
                return RespuestaAPI::error($e->errorInfo[2], 400);
            }
            return RespuestaAPI::error('Error al crear el criterio de evaluación.', 500);
        }
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'id_rubro' => 'required|integer',
            'descripcion' => 'required|string'
        ]);

        try {
            DB::statement(
                'CALL sp_criterio_eval_actualizar(?, ?, ?)',
                [$id, $request->input('id_rubro'), $request->input('descripcion')]
            );

            return RespuestaAPI::exito('Criterio de evaluación actualizado exitosamente', null);

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '45000') {
                return RespuestaAPI::error($e->errorInfo[2], 400);
            }
            return RespuestaAPI::error('Error al actualizar el criterio de evaluación: ' . $e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::statement('CALL sp_criterio_eval_eliminar(?)', [$id]);
            return RespuestaAPI::exito('Criterio de evaluación eliminado exitosamente', null, 200);

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '45000') {
                return RespuestaAPI::error($e->errorInfo[2], 400);
            }
            return RespuestaAPI::error('Error al eliminar el criterio de evaluación: ' . $e->getMessage(), 500);
        }
    }
}
