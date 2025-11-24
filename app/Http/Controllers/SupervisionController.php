<?php

namespace App\Http\Controllers;

use App\Models\CriterioSupervisionContableModelo;
use App\Models\CriterioSupervisionNoContableModelo;
use App\Utils\RespuestaAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

/**
 * Controlador para gestionar los criterios de supervisión.
 *
 * Este controlador maneja la lógica de negocio para los criterios de supervisión,
 * tanto contables como no contables.
 */
class SupervisionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/supervision/rubros",
     *     summary="Listar todos los rubros contables y no contables",
     *     tags={"Supervisión"},
     *     @OA\Response(
     *         response=200,
     *         description="Criterios de supervisión contables y no contables obtenidos con éxito",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="contables",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/CriterioSupervisionContableModelo")
     *             ),
     *             @OA\Property(
     *                 property="no_contables",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/CriterioSupervisionNoContableModelo")
     *             )
     *         )
     *     )
     * )
     */
    public function listarRubrosContablesNoContables(Request $request)
    {
        $contables = CriterioSupervisionContableModelo::all();
        $noContables = CriterioSupervisionNoContableModelo::all();

        $rubros = [];
        return RespuestaAPI::exito('Criterios de supervisión contables y no contables obtenidos con éxito', [
            'contables' => $contables,
            'no_contables' => $noContables,
        ]);
    }

    // --- Criterios Contables ---

    /**
     * @OA\Get(
     *     path="/supervision/contable",
     *     summary="Listar todos los criterios de supervisión contables",
     *     tags={"Supervisión"},
     *     @OA\Response(
     *         response=200,
     *         description="Criterios de supervisión contables obtenidos con éxito",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="rubros",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id_rubro", type="integer", example=1),
     *                     @OA\Property(property="nombre", type="string", example="Rubro Contable 1"),
     *                     @OA\Property(
     *                         property="criterios",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="id_criterio", type="integer", example=1),
     *                             @OA\Property(property="criterio", type="string", example="Criterio 1 del rubro 1")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function indexContable()
    {
        $criterios = CriterioSupervisionContableModelo::all();
        $rubros = [];

        foreach ($criterios as $criterio) {
            if (!isset($rubros[$criterio->id_rubro])) {
                $rubros[$criterio->id_rubro] = [
                    'id_rubro' => $criterio->id_rubro,
                    'nombre' => $criterio->rubro,
                    'criterios' => [],
                ];
            }

            $rubros[$criterio->id_rubro]['criterios'][] = [
                'id_criterio' => $criterio->id_supcriterio,
                'criterio' => $criterio->criterio,
            ];
        }

        $datos = ['rubros' => array_values($rubros)];

        return RespuestaAPI::exito('Criterios de supervisión contables obtenidos con éxito', $datos);
    }

    /**
     * @OA\Get(
     *     path="/supervision/contable/{id}",
     *     summary="Mostrar un criterio de supervisión contable específico",
     *     tags={"Supervisión"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del criterio de supervisión contable",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Criterio de supervisión contable obtenido con éxito",
     *         @OA\JsonContent(ref="#/components/schemas/CriterioSupervisionContableModelo")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Criterio de supervisión contable no encontrado"
     *     )
     * )
     */
    public function showContable($id)
    {
        $criterio = CriterioSupervisionContableModelo::find($id);
        if ($criterio) {
            return RespuestaAPI::exito('Criterio de supervisión contable obtenido con éxito', $criterio);
        } else {
            return RespuestaAPI::error('Criterio de supervisión contable no encontrado', RespuestaAPI::HTTP_NOT_FOUND);
        }
    }

    /**
     * @OA\Post(
     *     path="/supervision/contable/insertar",
     *     summary="Almacenar un nuevo criterio de supervisión contable",
     *     tags={"Supervisión"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"p_descripcion", "p_id_rubro"},
     *             @OA\Property(property="p_descripcion", type="string", example="Descripción del criterio"),
     *             @OA\Property(property="p_id_rubro", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Criterio contable creado con éxito",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Criterio contable creado con éxito")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Datos inválidos"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al crear el criterio contable"
     *     )
     * )
     */
    public function storeContable(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'p_descripcion' => 'required|string',
            'p_id_rubro' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return RespuestaAPI::error('Datos inválidos', RespuestaAPI::HTTP_ERROR_VALIDACION, ['errors' => $validator->errors()]);
        }

        try {
            DB::statement(
                'CALL sp_criterio_supervision_insertar(?, ?)',
                [
                    $request->input('p_descripcion'),
                    $request->input('p_id_rubro'),
                ]
            );
            return RespuestaAPI::exito('Criterio contable creado con éxito');
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al crear el criterio contable: ' . $e->getMessage(), RespuestaAPI::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Put(
     *     path="/supervision/contable/{id}",
     *     summary="Actualizar un criterio de supervisión contable existente",
     *     tags={"Supervisión"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del criterio de supervisión contable",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"p_descripcion", "p_id_rubro"},
     *             @OA\Property(property="p_descripcion", type="string", example="Descripción actualizada"),
     *             @OA\Property(property="p_id_rubro", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Criterio contable actualizado con éxito",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Criterio contable actualizado con éxito")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Datos inválidos"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al actualizar el criterio contable"
     *     )
     * )
     */
    public function updateContable(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'p_descripcion' => 'required|string',
            'p_id_rubro' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return RespuestaAPI::error('Datos inválidos', RespuestaAPI::HTTP_ERROR_VALIDACION, ['errors' => $validator->errors()]);
        }

        try {
            DB::statement(
                'CALL sp_criterio_supervision_actualizar(?, ?, ?)',
                [
                    $id,
                    $request->input('p_descripcion'),
                    $request->input('p_id_rubro'),
                ]
            );
            return RespuestaAPI::exito('Criterio contable actualizado con éxito');
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al actualizar el criterio contable: ' . $e->getMessage(), RespuestaAPI::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/supervision/contable/{id}",
     *     summary="Eliminar un criterio de supervisión contable",
     *     tags={"Supervisión"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del criterio de supervisión contable",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Criterio contable eliminado con éxito",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Criterio contable eliminado con éxito")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al eliminar el criterio contable"
     *     )
     * )
     */
    public function destroyContable($id)
    {
        try {
            DB::statement(
                'CALL sp_criterio_supervision_eliminar(?)',
                [$id]
            );
            return RespuestaAPI::exito('Criterio contable eliminado con éxito');
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al eliminar el criterio contable: ' . $e->getMessage(), RespuestaAPI::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/supervision/contable/buscar",
     *     summary="Buscar criterios de supervisión contables",
     *     tags={"Supervisión"},
     *     @OA\Parameter(
     *         name="id_rubro",
     *         in="query",
     *         description="ID del rubro",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="nombre",
     *         in="query",
     *         description="Nombre del rubro",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Criterios de supervisión contables filtrados obtenidos con éxito",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="rubros",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id_rubro", type="integer", example=1),
     *                     @OA\Property(property="nombre", type="string", example="Rubro Contable 1"),
     *                     @OA\Property(
     *                         property="criterios",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="id_criterio", type="integer", example=1),
     *                             @OA\Property(property="criterio", type="string", example="Criterio 1 del rubro 1")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Debe proporcionar al menos un parámetro de búsqueda (id_rubro o nombre)."
     *     )
     * )
     */
    public function buscarContable(Request $request)
    {
        if (!$request->has('id_rubro') && !$request->has('nombre')) {
            return RespuestaAPI::error('Debe proporcionar al menos un parámetro de búsqueda (id_rubro o nombre).', RespuestaAPI::HTTP_ERROR_VALIDACION);
        }

        $query = CriterioSupervisionContableModelo::query();

        if ($request->has('id_rubro')) {
            $query->where('id_rubro', $request->input('id_rubro'));
        }

        if ($request->has('nombre')) {
            $query->where('rubro', 'like', '%' . $request->input('nombre') . '%');
        }

        $criterios = $query->get();

        if ($criterios->isEmpty()) {
            return RespuestaAPI::exito('No se encontraron criterios con los parámetros de búsqueda proporcionados.', []);
        }

        $rubros = [];

        foreach ($criterios as $criterio) {
            if (!isset($rubros[$criterio->id_rubro])) {
                $rubros[$criterio->id_rubro] = [
                    'id_rubro' => $criterio->id_rubro,
                    'nombre' => $criterio->rubro,
                    'criterios' => [],
                ];
            }

            $rubros[$criterio->id_rubro]['criterios'][] = [
                'id_criterio' => $criterio->id_supcriterio,
                'criterio' => $criterio->criterio,
            ];
        }

        $datos = ['rubros' => array_values($rubros)];

        return RespuestaAPI::exito('Criterios de supervisión contables filtrados obtenidos con éxito', $datos);
    }

    // --- Criterios No Contables ---

    /**
     * @OA\Get(
     *     path="/supervision/no-contable",
     *     summary="Listar todos los criterios de supervisión no contables",
     *     tags={"Supervisión"},
     *     @OA\Response(
     *         response=200,
     *         description="Criterios de supervisión no contables obtenidos con éxito",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="rubros",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id_nc_rubro", type="integer", example=1),
     *                     @OA\Property(property="nombre", type="string", example="Rubro No Contable 1"),
     *                     @OA\Property(
     *                         property="criterios",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="id_nc_criterio", type="integer", example=1),
     *                             @OA\Property(property="criterio", type="string", example="Criterio 1 del rubro 1")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function indexNoContable()
    {
        $criterios = CriterioSupervisionNoContableModelo::all();
        $rubros = [];

        foreach ($criterios as $criterio) {
            if (!isset($rubros[$criterio->id_nc_rubro])) {
                $rubros[$criterio->id_nc_rubro] = [
                    'id_nc_rubro' => $criterio->id_nc_rubro,
                    'nombre' => $criterio->rubro,
                    'criterios' => [],
                ];
            }

            $rubros[$criterio->id_nc_rubro]['criterios'][] = [
                'id_nc_criterio' => $criterio->id_nc_criterio,
                'criterio' => $criterio->criterio,
            ];
        }

        $datos = ['rubros' => array_values($rubros)];

        return RespuestaAPI::exito('Criterios de supervisión no contables obtenidos con éxito', $datos);
    }

    /**
     * @OA\Get(
     *     path="/supervision/no-contable/{id}",
     *     summary="Mostrar un criterio de supervisión no contable específico",
     *     tags={"Supervisión"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del criterio de supervisión no contable",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Criterio de supervisión no contable obtenido con éxito",
     *         @OA\JsonContent(ref="#/components/schemas/CriterioSupervisionNoContableModelo")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Criterio de supervisión no contable no encontrado"
     *     )
     * )
     */
    public function showNoContable($id)
    {
        $criterio = CriterioSupervisionNoContableModelo::find($id);
        if ($criterio) {
            return RespuestaAPI::exito('Criterio de supervisión no contable obtenido con éxito', $criterio);
        } else {
            return RespuestaAPI::error('Criterio de supervisión no contable no encontrado', RespuestaAPI::HTTP_NOT_FOUND);
        }
    }

    /**
     * @OA\Post(
     *     path="/supervision/no-contable/insertar",
     *     summary="Almacenar un nuevo criterio de supervisión no contable",
     *     tags={"Supervisión"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"p_descripcion", "p_id_nc_rubro"},
     *             @OA\Property(property="p_descripcion", type="string", example="Descripción del criterio no contable"),
     *             @OA\Property(property="p_id_nc_rubro", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Criterio no contable creado con éxito",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Criterio no contable creado con éxito")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Datos inválidos"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al crear el criterio no contable"
     *     )
     * )
     */
    public function storeNoContable(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'p_descripcion' => 'required|string',
            'p_id_nc_rubro' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return RespuestaAPI::error('Datos inválidos', RespuestaAPI::HTTP_ERROR_VALIDACION, ['errors' => $validator->errors()]);
        }

        try {
            DB::statement(
                'CALL sp_criterio_supervision_no_contable_insertar(?, ?)',
                [
                    $request->input('p_descripcion'),
                    $request->input('p_id_nc_rubro'),
                ]
            );
            return RespuestaAPI::exito('Criterio no contable creado con éxito');
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al crear el criterio no contable: ' . $e->getMessage(), RespuestaAPI::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Put(
     *     path="/supervision/no-contable/{id}",
     *     summary="Actualizar un criterio de supervisión no contable existente",
     *     tags={"Supervisión"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del criterio de supervisión no contable",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"p_descripcion", "p_id_nc_rubro"},
     *             @OA\Property(property="p_descripcion", type="string", example="Descripción actualizada"),
     *             @OA\Property(property="p_id_nc_rubro", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Criterio no contable actualizado con éxito",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Criterio no contable actualizado con éxito")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Datos inválidos"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al actualizar el criterio no contable"
     *     )
     * )
     */
    public function updateNoContable(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'p_descripcion' => 'required|string',
            'p_id_nc_rubro' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return RespuestaAPI::error('Datos inválidos', RespuestaAPI::HTTP_ERROR_VALIDACION, ['errors' => $validator->errors()]);
        }

        try {
            DB::statement(
                'CALL sp_criterio_supervision_no_contable_actualizar(?, ?, ?)',
                [
                    $id,
                    $request->input('p_descripcion'),
                    $request->input('p_id_nc_rubro'),
                ]
            );
            return RespuestaAPI::exito('Criterio no contable actualizado con éxito');
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al actualizar el criterio no contable: ' . $e->getMessage(), RespuestaAPI::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/supervision/no-contable/{id}",
     *     summary="Eliminar un criterio de supervisión no contable",
     *     tags={"Supervisión"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del criterio de supervisión no contable",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Criterio no contable eliminado con éxito",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Criterio no contable eliminado con éxito")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al eliminar el criterio no contable"
     *     )
     * )
     */
    public function destroyNoContable($id)
    {
        try {
            DB::statement(
                'CALL sp_criterio_supervision_no_contable_eliminar(?)',
                [$id]
            );
            return RespuestaAPI::exito('Criterio no contable eliminado con éxito');
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al eliminar el criterio no contable: ' . $e->getMessage(), RespuestaAPI::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}