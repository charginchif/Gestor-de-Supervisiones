<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utils\RespuestaAPI;
use Illuminate\Support\Facades\DB;

class CarreraController extends Controller
{
    public function index()
    {
        try {
            $carreras = DB::select('SELECT * FROM vw_admin_carreras');
            return RespuestaAPI::exito('Listado de carreras', $carreras);
        } catch (\Illuminate\Database\QueryException $e) {
            return RespuestaAPI::error('Error al obtener las carreras: ' . $e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        try {
            $carrera = DB::select('SELECT * FROM vw_admin_carreras WHERE id_carrera = ?', [$id]);
            if (empty($carrera)) {
                return RespuestaAPI::error('Carrera no encontrada', 404);
            }
            return RespuestaAPI::exito('Carrera encontrada', $carrera[0]);
        } catch (\Illuminate\Database\QueryException $e) {
            return RespuestaAPI::error('Error al obtener la carrera: ' . $e->getMessage(), RespuestaAPI::HTTP_ERROR_INTERNO );
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nombre' => 'required|string|max:100'
        ]);

        try {
            DB::statement(
                'CALL sp_carrera_insertar(?)',
                [$request->input('nombre')]
            );

            return RespuestaAPI::exito('Carrera creada exitosamente', $request->all(), 201);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '45000') {
                return RespuestaAPI::error($e->errorInfo[2], 400);
            }
            return RespuestaAPI::error('Error al crear la carrera.', 500);
        }
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nombre' => 'required|string|max:100'
        ]);

        try {
            DB::statement(
                'CALL sp_carrera_actualizar(?)',
                [$id, $request->input('nombre')]
            );

            return RespuestaAPI::exito('Carrera actualizada exitosamente', $request->all());

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '45000') {
                return RespuestaAPI::error($e->errorInfo[2], 400);
            }
            return RespuestaAPI::error('Error al actualizar la carrera: ' . $e->getMessage(), RespuestaAPI::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id)
    {
        try {
            DB::statement('CALL sp_carrera_eliminar(?)', [$id]);
            return RespuestaAPI::exito('Carrera eliminada exitosamente', null, 200);

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '45000') {
                return RespuestaAPI::error($e->errorInfo[2], 400);
            }
            return RespuestaAPI::error('Error al eliminar la carrera: ' . $e->getMessage(), RespuestaAPI::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
