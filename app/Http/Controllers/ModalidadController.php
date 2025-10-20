<?php

namespace App\Http\Controllers;

use App\Models\CatModalidad;
use Illuminate\Http\Request;
use App\Utils\RespuestaAPI;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;

class ModalidadController extends Controller
{
    public function list(Request $request)
    {
        try {
            $search = $request->get('search');

            $query = CatModalidad::query();

            if ($search) {
                $query->where('nombre', 'like', '%' . $search . '%');
            }

            $modalidades = $query->get();

            return RespuestaAPI::exito('Modalidades obtenidas correctamente.', $modalidades);
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al obtener las modalidades: ' . $e->getMessage(), RespuestaAPI::HTTP_ERROR_INTERNO);
        }
    }

    public function get($id)
    {
        try {
            $modalidad = CatModalidad::findOrFail($id);
            return RespuestaAPI::exito('Modalidad obtenida correctamente.', $modalidad);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return RespuestaAPI::error('Modalidad no encontrada.', RespuestaAPI::HTTP_NO_ENCONTRADO);
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al obtener la modalidad: ' . $e->getMessage(), RespuestaAPI::HTTP_ERROR_INTERNO);
        }
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100|unique:cat_modalidad',
        ]);

        if ($validator->fails()) {
            return RespuestaAPI::error('Error de validación.', RespuestaAPI::HTTP_ERROR_VALIDACION, $validator->errors());
        }

        try {
            $modalidad = CatModalidad::create($request->all());
            return RespuestaAPI::exito('Modalidad creada correctamente.', $modalidad, RespuestaAPI::HTTP_CREADO);
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return RespuestaAPI::error('El nombre de la modalidad ya existe.', RespuestaAPI::HTTP_ERROR_VALIDACION);
            }
            return RespuestaAPI::error('Error al crear la modalidad: ' . $e->getMessage(), RespuestaAPI::HTTP_ERROR_INTERNO);
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al crear la modalidad: ' . $e->getMessage(), RespuestaAPI::HTTP_ERROR_INTERNO);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100|unique:cat_modalidad,nombre,' . $id,
        ]);

        if ($validator->fails()) {
            return RespuestaAPI::error('Error de validación.', RespuestaAPI::HTTP_ERROR_VALIDACION, $validator->errors());
        }

        try {
            $modalidad = CatModalidad::findOrFail($id);
            $modalidad->update($request->all());
            return RespuestaAPI::exito('Modalidad actualizada correctamente.', $modalidad);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return RespuestaAPI::error('Modalidad no encontrada.', RespuestaAPI::HTTP_NO_ENCONTRADO);
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return RespuestaAPI::error('El nombre de la modalidad ya existe.', RespuestaAPI::HTTP_ERROR_VALIDACION);
            }
            return RespuestaAPI::error('Error al actualizar la modalidad: ' . $e->getMessage(), RespuestaAPI::HTTP_ERROR_INTERNO);
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al actualizar la modalidad: ' . $e->getMessage(), RespuestaAPI::HTTP_ERROR_INTERNO);
        }
    }

    public function delete($id)
    {
        try {
            $modalidad = CatModalidad::findOrFail($id);
            $modalidad->delete();
            return RespuestaAPI::exito('Modalidad eliminada correctamente.', ['deleted' => true]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return RespuestaAPI::error('Modalidad no encontrada.', RespuestaAPI::HTTP_NO_ENCONTRADO);
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al eliminar la modalidad: ' . $e->getMessage(), RespuestaAPI::HTTP_ERROR_INTERNO);
        }
    }

    public function bulkUpsert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            '*.id' => 'integer',
            '*.nombre' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return RespuestaAPI::error('Error de validación.', RespuestaAPI::HTTP_ERROR_VALIDACION, $validator->errors());
        }

        $results = [];
        foreach ($request->all() as $item) {
            try {
                $existing = CatModalidad::where('nombre', $item['nombre'])->first();
                if ($existing && (!isset($item['id']) || $existing->id != $item['id'])) {
                    continue; 
                }

                $modalidad = CatModalidad::updateOrCreate(
                    ['id' => $item['id'] ?? null],
                    ['nombre' => $item['nombre']]
                );
                $results[] = $modalidad;
            } catch (\Exception $e) {
            }
        }

        return RespuestaAPI::exito('Operación masiva completada.', $results);
    }
}
