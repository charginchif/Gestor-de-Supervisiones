<?php

namespace App\Http\Controllers;

use App\Utils\RespuestaAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RubroController extends Controller
{
    public function index()
    {
        try {
            $rubros = DB::select('SELECT * FROM cat_rubro_alumno_docente');
            return RespuestaAPI::exito('Listado de rubros', $rubros);
        } catch (\Illuminate\Database\QueryException $e) {
            return RespuestaAPI::error('Error al obtener los rubros: ' . $e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        try {
            $rubro = DB::selectOne('SELECT * FROM cat_rubro_alumno_docente WHERE id = ?', [$id]);
            if ($rubro) {
                return RespuestaAPI::exito('Rubro encontrado', $rubro);
            }
            return RespuestaAPI::error('Rubro no encontrado', 404);
        } catch (\Illuminate\Database\QueryException $e) {
            return RespuestaAPI::error('Error al obtener el rubro: ' . $e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return RespuestaAPI::error('Error de validación', 422, $validator->errors());
        }

        try {
            DB::insert('INSERT INTO cat_rubro_alumno_docente (nombre) VALUES (?)', [
                $request->nombre,
            ]);
            $id = DB::getPdo()->lastInsertId();
            $rubro = DB::selectOne('SELECT * FROM cat_rubro_alumno_docente WHERE id = ?', [$id]);
            return RespuestaAPI::exito('Rubro creado exitosamente', $rubro, 201);
        } catch (\Illuminate\Database\QueryException $e) {
            return RespuestaAPI::error('Error al crear el rubro: ' . $e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'sometimes|required|string|max:255',
        ]);

        if ($validator->fails()) {
            return RespuestaAPI::error('Error de validación', 422, $validator->errors());
        }

        try {
            $rubro = DB::selectOne('SELECT * FROM cat_rubro_alumno_docente WHERE id = ?', [$id]);

            if (!$rubro) {
                return RespuestaAPI::error('Rubro no encontrado', 404);
            }

            $updateData = $request->only(['nombre']);
            if (empty($updateData)) {
                return RespuestaAPI::error('No hay datos para actualizar', 400);
            }

            $query = 'UPDATE cat_rubro_alumno_docente SET ';
            $bindings = [];
            foreach ($updateData as $key => $value) {
                $query .= "$key = ?, ";
                $bindings[] = $value;
            }
            $query = rtrim($query, ', ');
            $query .= ' WHERE id = ?';
            $bindings[] = $id;

            DB::update($query, $bindings);

            $rubroActualizado = DB::selectOne('SELECT * FROM cat_rubro_alumno_docente WHERE id = ?', [$id]);

            return RespuestaAPI::exito('Rubro actualizado exitosamente', $rubroActualizado);
        } catch (\Illuminate\Database\QueryException $e) {
            return RespuestaAPI::error('Error al actualizar el rubro: ' . $e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $rubro = DB::selectOne('SELECT * FROM cat_rubro_alumno_docente WHERE id = ?', [$id]);

            if (!$rubro) {
                return RespuestaAPI::error('Rubro no encontrado', 404);
            }

            DB::delete('DELETE FROM cat_rubro_alumno_docente WHERE id = ?', [$id]);

            return RespuestaAPI::exito('Rubro eliminado exitosamente');
        } catch (\Illuminate\Database\QueryException $e) {
            return RespuestaAPI::error('Error al eliminar el rubro: ' . $e->getMessage(), 500);
        }
    }

    public function indexContable()
    {
        try {
            $rubros = DB::select('SELECT * FROM cat_rubro');
            return RespuestaAPI::exito('Listado de rubros contables', $rubros);
        } catch (\Illuminate\Database\QueryException $e) {
            return RespuestaAPI::error('Error al obtener los rubros contables: ' . $e->getMessage(), 500);
        }
    }

    public function showContable($id)
    {
        try {
            $rubro = DB::selectOne('SELECT * FROM cat_rubro WHERE id = ?', [$id]);
            if ($rubro) {
                return RespuestaAPI::exito('Rubro contable encontrado', $rubro);
            }
            return RespuestaAPI::error('Rubro contable no encontrado', 404);
        } catch (\Illuminate\Database\QueryException $e) {
            return RespuestaAPI::error('Error al obtener el rubro contable: ' . $e->getMessage(), 500);
        }
    }

    public function storeContable(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return RespuestaAPI::error('Error de validación', 422, $validator->errors());
        }

        try {
            DB::insert('INSERT INTO cat_rubro (nombre) VALUES (?)', [
                $request->nombre,
            ]);
            $id = DB::getPdo()->lastInsertId();
            $rubro = DB::selectOne('SELECT * FROM cat_rubro WHERE id = ?', [$id]);
            return RespuestaAPI::exito('Rubro contable creado exitosamente', $rubro, 201);
        } catch (\Illuminate\Database\QueryException $e) {
            return RespuestaAPI::error('Error al crear el rubro contable: ' . $e->getMessage(), 500);
        }
    }

    public function updateContable(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'sometimes|required|string|max:255',
        ]);

        if ($validator->fails()) {
            return RespuestaAPI::error('Error de validación', 422, $validator->errors());
        }

        try {
            $rubro = DB::selectOne('SELECT * FROM cat_rubro WHERE id = ?', [$id]);

            if (!$rubro) {
                return RespuestaAPI::error('Rubro contable no encontrado', 404);
            }

            $updateData = $request->only(['nombre']);
            if (empty($updateData)) {
                return RespuestaAPI::error('No hay datos para actualizar', 400);
            }

            $query = 'UPDATE cat_rubro SET ';
            $bindings = [];
            foreach ($updateData as $key => $value) {
                $query .= "$key = ?, ";
                $bindings[] = $value;
            }
            $query = rtrim($query, ', ');
            $query .= ' WHERE id = ?';
            $bindings[] = $id;

            DB::update($query, $bindings);

            $rubroActualizado = DB::selectOne('SELECT * FROM cat_rubro WHERE id = ?', [$id]);

            return RespuestaAPI::exito('Rubro contable actualizado exitosamente', $rubroActualizado);
        } catch (\Illuminate\Database\QueryException $e) {
            return RespuestaAPI::error('Error al actualizar el rubro contable: ' . $e->getMessage(), 500);
        }
    }

    public function destroyContable($id)
    {
        try {
            $rubro = DB::selectOne('SELECT * FROM cat_rubro WHERE id = ?', [$id]);

            if (!$rubro) {
                return RespuestaAPI::error('Rubro contable no encontrado', 404);
            }

            DB::delete('DELETE FROM cat_rubro WHERE id = ?', [$id]);

            return RespuestaAPI::exito('Rubro contable eliminado exitosamente');
        } catch (\Illuminate\Database\QueryException $e) {
            return RespuestaAPI::error('Error al eliminar el rubro contable: ' . $e->getMessage(), 500);
        }
    }

    public function indexNoContable()
    {
        try {
            $rubros = DB::select('SELECT * FROM cat_rubro_no_contable');
            return RespuestaAPI::exito('Listado de rubros no contables', $rubros);
        } catch (\Illuminate\Database\QueryException $e) {
            return RespuestaAPI::error('Error al obtener los rubros no contables: ' . $e->getMessage(), 500);
        }
    }

    public function showNoContable($id)
    {
        try {
            $rubro = DB::selectOne('SELECT * FROM cat_rubro_no_contable WHERE id = ?', [$id]);
            if ($rubro) {
                return RespuestaAPI::exito('Rubro no contable encontrado', $rubro);
            }
            return RespuestaAPI::error('Rubro no contable no encontrado', 404);
        } catch (\Illuminate\Database\QueryException $e) {
            return RespuestaAPI::error('Error al obtener el rubro no contable: ' . $e->getMessage(), 500);
        }
    }

    public function storeNoContable(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return RespuestaAPI::error('Error de validación', 422, $validator->errors());
        }

        try {
            DB::insert('INSERT INTO cat_rubro_no_contable (nombre) VALUES (?)', [
                $request->nombre,
            ]);
            $id = DB::getPdo()->lastInsertId();
            $rubro = DB::selectOne('SELECT * FROM cat_rubro_no_contable WHERE id = ?', [$id]);
            return RespuestaAPI::exito('Rubro no contable creado exitosamente', $rubro, 201);
        } catch (\Illuminate\Database\QueryException $e) {
            return RespuestaAPI::error('Error al crear el rubro no contable: ' . $e->getMessage(), 500);
        }
    }

    public function updateNoContable(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'sometimes|required|string|max:255',
        ]);

        if ($validator->fails()) {
            return RespuestaAPI::error('Error de validación', 422, $validator->errors());
        }

        try {
            $rubro = DB::selectOne('SELECT * FROM cat_rubro_no_contable WHERE id = ?', [$id]);

            if (!$rubro) {
                return RespuestaAPI::error('Rubro no contable no encontrado', 404);
            }

            $updateData = $request->only(['nombre']);
            if (empty($updateData)) {
                return RespuestaAPI::error('No hay datos para actualizar', 400);
            }

            $query = 'UPDATE cat_rubro_no_contable SET ';
            $bindings = [];
            foreach ($updateData as $key => $value) {
                $query .= "$key = ?, ";
                $bindings[] = $value;
            }
            $query = rtrim($query, ', ');
            $query .= ' WHERE id = ?';
            $bindings[] = $id;

            DB::update($query, $bindings);

            $rubroActualizado = DB::selectOne('SELECT * FROM cat_rubro_no_contable WHERE id = ?', [$id]);

            return RespuestaAPI::exito('Rubro no contable actualizado exitosamente', $rubroActualizado);
        } catch (\Illuminate\Database\QueryException $e) {
            return RespuestaAPI::error('Error al actualizar el rubro no contable: ' . $e->getMessage(), 500);
        }
    }

    public function destroyNoContable($id)
    {
        try {
            $rubro = DB::selectOne('SELECT * FROM cat_rubro_no_contable WHERE id = ?', [$id]);

            if (!$rubro) {
                return RespuestaAPI::error('Rubro no contable no encontrado', 404);
            }

            DB::delete('DELETE FROM cat_rubro_no_contable WHERE id = ?', [$id]);

            return RespuestaAPI::exito('Rubro no contable eliminado exitosamente');
        } catch (\Illuminate\Database\QueryException $e) {
            return RespuestaAPI::error('Error al eliminar el rubro no contable: ' . $e->getMessage(), 500);
        }
    }
}
