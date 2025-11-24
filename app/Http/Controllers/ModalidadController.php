<?php

namespace App\Http\Controllers;

use App\Models\CatModalidad;
use Illuminate\Http\Request;
use App\Utils\RespuestaAPI;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;

class ModalidadController extends Controller
{
    /**
     * @OA\Get(
     *     path="/modalidades",
     *     summary="Listar todas las modalidades",
     *     tags={"Modalidades"},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Término de búsqueda",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Modalidades obtenidas correctamente",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/CatModalidad")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener las modalidades"
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/modalidades/{id}",
     *     summary="Obtener una modalidad por su ID",
     *     tags={"Modalidades"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la modalidad",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Modalidad obtenida correctamente",
     *         @OA\JsonContent(ref="#/components/schemas/CatModalidad")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Modalidad no encontrada"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener la modalidad"
     *     )
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/modalidades",
     *     summary="Crear una nueva modalidad",
     *     tags={"Modalidades"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre"},
     *             @OA\Property(property="nombre", type="string", maxLength=100, example="Sabatina")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Modalidad creada correctamente",
     *         @OA\JsonContent(ref="#/components/schemas/CatModalidad")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al crear la modalidad"
     *     )
     * )
     */
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

    /**
     * @OA\Put(
     *     path="/modalidades/{id}",
     *     summary="Actualizar una modalidad existente",
     *     tags={"Modalidades"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la modalidad",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre"},
     *             @OA\Property(property="nombre", type="string", maxLength=100, example="Sabatina Matutina")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Modalidad actualizada correctamente",
     *         @OA\JsonContent(ref="#/components/schemas/CatModalidad")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Modalidad no encontrada"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al actualizar la modalidad"
     *     )
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/modalidades/{id}",
     *     summary="Eliminar una modalidad",
     *     tags={"Modalidades"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la modalidad",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Modalidad eliminada correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Modalidad no encontrada"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al eliminar la modalidad"
     *     )
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/modalidades/bulk",
     *     summary="Crear o actualizar modalidades en bloque",
     *     tags={"Modalidades"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/CatModalidad")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operación masiva completada",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/CatModalidad")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
     */
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
