<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utils\RespuestaAPI;
use Illuminate\Support\Facades\DB;

class MateriaController extends Controller
{

    public function index()
    {
        try {
            $materias = DB::select('SELECT * FROM vw_admin_materias');
            return RespuestaAPI::exito('Listado de materias', $materias);
        } catch (\Illuminate\Database\QueryException $e) {
            return RespuestaAPI::error('Error al obtener las materias: ' . $e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        try {
            $materia = DB::select('SELECT * FROM vw_admin_materias WHERE id_materia = ?', [$id]);
            if (empty($materia)) {
                return RespuestaAPI::error('Materia no encontrada', 404);
            }
            return RespuestaAPI::exito('Materia encontrada', $materia[0]);
        } catch (\Illuminate\Database\QueryException $e) {
            return RespuestaAPI::error('Error al obtener la materia: ' . $e->getMessage(), 500 );
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nombre' => 'required|string|max:100'
        ]);

        try {
            DB::statement(
                'CALL sp_materia_insertar(?)',
                [$request->input('nombre')]
            );

            return RespuestaAPI::exito('Materia creada exitosamente', $request->all(), 201);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '45000') {
                return RespuestaAPI::error($e->errorInfo[2], 400);
            }
            return RespuestaAPI::error('Error al crear la materia.', 500);
        }
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nombre' => 'required|string|max:100'
        ]);

        try {
            DB::statement(
                'CALL sp_materia_actualizar(?, ?)',
                [$id, $request->input('nombre')]
            );

            return RespuestaAPI::exito('Materia actualizada exitosamente', $request->all());

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '45000') {
                return RespuestaAPI::error($e->errorInfo[2], 400);
            }
            return RespuestaAPI::error('Error al actualizar la materia: ' . $e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::statement('CALL sp_materia_eliminar(?)', [$id]);
            return RespuestaAPI::exito('Materia eliminada exitosamente', null, 200);

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '45000') {
                return RespuestaAPI::error($e->errorInfo[2], 400);
            }
            return RespuestaAPI::error('Error al eliminar la materia: ' . $e->getMessage(), 500);
        }
    }
}
