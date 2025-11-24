<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utils\RespuestaAPI;
use Illuminate\Support\Facades\DB;

/**
 * Controlador para gestionar los criterios de evaluación docente.
 *
 * Este controlador maneja la lógica de negocio para los criterios de evaluación
 * que se aplican a los docentes.
 */
class CriterioEvaluacionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/criterios-evaluacion",
     *     summary="Listar todos los criterios de evaluación docente",
     *     tags={"Criterios de Evaluación"},
     *     @OA\Response(
     *         response=200,
     *         description="Listado de criterios de evaluación",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/CriterioEvaluacion")
     *         )
     *     ),
     *      @OA\Response(
     *         response=500,
     *         description="Error al obtener los criterios"
     *     )
     * )
     */
    public function index()
    {
        try {
            $criterios = DB::select('SELECT * FROM criterios_evaluacion');
            return RespuestaAPI::exito('Listado de criterios de evaluación', $criterios);
        } catch (\Illuminate\Database\QueryException $e) {
            return RespuestaAPI::error('Error al obtener los criterios: ' . $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/criterios-evaluacion/{id}",
     *     summary="Mostrar un criterio de evaluación docente específico",
     *     tags={"Criterios de Evaluación"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del criterio de evaluación",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Criterio de evaluación encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/CriterioEvaluacion")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Criterio de evaluación no encontrado"
     *     ),
     *      @OA\Response(
     *         response=500,
     *         description="Error al obtener el criterio"
     *     )
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/criterios-evaluacion",
     *     summary="Crear un nuevo criterio de evaluación docente",
     *     tags={"Criterios de Evaluación"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id_rubro", "descripcion"},
     *             @OA\Property(property="id_rubro", type="integer", example=1),
     *             @OA\Property(property="descripcion", type="string", example="El docente demuestra dominio del tema")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Criterio de evaluación creado exitosamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al crear el criterio de evaluación"
     *     )
     * )
     */
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

    /**
     * @OA\Put(
     *     path="/criterios-evaluacion/{id}",
     *     summary="Actualizar un criterio de evaluación docente existente",
     *     tags={"Criterios de Evaluación"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del criterio de evaluación",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id_rubro", "descripcion"},
     *             @OA\Property(property="id_rubro", type="integer", example=1),
     *             @OA\Property(property="descripcion", type="string", example="El docente demuestra un excelente dominio del tema")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Criterio de evaluación actualizado exitosamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al actualizar el criterio de evaluación"
     *     )
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/criterios-evaluacion/{id}",
     *     summary="Eliminar un criterio de evaluación docente",
     *     tags={"Criterios de Evaluación"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del criterio de evaluación",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Criterio de evaluación eliminado exitosamente"
     *     ),
     *      @OA\Response(
     *         response=400,
     *         description="Error al eliminar el criterio de evaluación"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al eliminar el criterio de evaluación"
     *     )
     * )
     */
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