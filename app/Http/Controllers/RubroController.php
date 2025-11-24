<?php

namespace App\Http\Controllers;

use App\Utils\RespuestaAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

/**
 * Controlador para gestionar los rubros.
 *
 * Este controlador maneja la lógica de negocio para los rubros de evaluación de docentes
 * y los rubros de supervisión (contables y no contables).
 */
class RubroController extends Controller
{
    // --- Rubros de Evaluación Docente ---

    /**
     * @OA\Get(
     *     path="/rubros/evaluacion-docente",
     *     summary="Listar todos los rubros de evaluación docente",
     *     tags={"Rubros"},
     *     @OA\Response(
     *         response=200,
     *         description="Listado de rubros",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/RubroEvaluacionDocente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener los rubros"
     *     )
     * )
     */
    public function index()
    {
        try {
            $rubros = DB::select('SELECT * FROM cat_rubro_alumno_docente');
            return RespuestaAPI::exito('Listado de rubros', $rubros);
        } catch (\Illuminate\Database\QueryException $e) {
            return RespuestaAPI::error('Error al obtener los rubros: ' . $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/rubros/evaluacion-docente/{id}",
     *     summary="Mostrar un rubro de evaluación docente específico",
     *     tags={"Rubros"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del rubro",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rubro encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/RubroEvaluacionDocente")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Rubro no encontrado"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener el rubro"
     *     )
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/rubros/evaluacion-docente",
     *     summary="Crear un nuevo rubro de evaluación docente",
     *     tags={"Rubros"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre"},
     *             @OA\Property(property="nombre", type="string", maxLength=255, example="Claridad en la comunicación")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Rubro creado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/RubroEvaluacionDocente")
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Ya existe un rubro con este nombre"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al crear el rubro"
     *     )
     * )
     */
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
            if ($e->errorInfo[1] == 1062) {
                return RespuestaAPI::error('Ya existe un rubro con este nombre.', 409);
            }
            return RespuestaAPI::error('Error al crear el rubro: ' . $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/rubros/evaluacion-docente/{id}",
     *     summary="Actualizar un rubro de evaluación docente existente",
     *     tags={"Rubros"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del rubro",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="nombre", type="string", maxLength=255, example="Claridad y precisión en la comunicación")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rubro actualizado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/RubroEvaluacionDocente")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="No hay datos para actualizar"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Rubro no encontrado"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al actualizar el rubro"
     *     )
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/rubros/evaluacion-docente/{id}",
     *     summary="Eliminar un rubro de evaluación docente",
     *     tags={"Rubros"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del rubro",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rubro eliminado exitosamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Rubro no encontrado"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al eliminar el rubro"
     *     )
     * )
     */
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

    // --- Rubros de Supervisión Contable ---

    /**
     * @OA\Get(
     *     path="/rubros/supervision-contable",
     *     summary="Listar todos los rubros de supervisión contable",
     *     tags={"Rubros"},
     *     @OA\Response(
     *         response=200,
     *         description="Listado de rubros contables",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/RubroSupervisionContable")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener los rubros contables"
     *     )
     * )
     */
    public function indexContable()
    {
        try {
            $rubros = DB::select('SELECT * FROM cat_rubro');
            return RespuestaAPI::exito('Listado de rubros contables', $rubros);
        } catch (\Illuminate\Database\QueryException $e) {
            return RespuestaAPI::error('Error al obtener los rubros contables: ' . $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/rubros/supervision-contable/{id}",
     *     summary="Mostrar un rubro de supervisión contable específico",
     *     tags={"Rubros"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del rubro",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rubro contable encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/RubroSupervisionContable")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Rubro contable no encontrado"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener el rubro contable"
     *     )
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/rubros/supervision-contable",
     *     summary="Crear un nuevo rubro de supervisión contable",
     *     tags={"Rubros"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre"},
     *             @OA\Property(property="nombre", type="string", maxLength=255, example="Auditoría Interna")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Rubro contable creado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/RubroSupervisionContable")
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Ya existe un rubro con este nombre"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al crear el rubro contable"
     *     )
     * )
     */
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
            if ($e->errorInfo[1] == 1062) {
                return RespuestaAPI::error('Ya existe un rubro con este nombre.', 409);
            }
            return RespuestaAPI::error('Error al crear el rubro contable: ' . $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/rubros/supervision-contable/{id}",
     *     summary="Actualizar un rubro de supervisión contable existente",
     *     tags={"Rubros"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del rubro",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="nombre", type="string", maxLength=255, example="Auditoría Externa")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rubro contable actualizado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/RubroSupervisionContable")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="No hay datos para actualizar"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Rubro contable no encontrado"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al actualizar el rubro contable"
     *     )
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/rubros/supervision-contable/{id}",
     *     summary="Eliminar un rubro de supervisión contable",
     *     tags={"Rubros"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del rubro",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rubro contable eliminado exitosamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Rubro contable no encontrado"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al eliminar el rubro contable"
     *     )
     * )
     */
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

    // --- Rubros de Supervisión No Contable ---

    /**
     * @OA\Get(
     *     path="/rubros/supervision-no-contable",
     *     summary="Listar todos los rubros de supervisión no contable",
     *     tags={"Rubros"},
     *     @OA\Response(
     *         response=200,
     *         description="Listado de rubros no contables",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/RubroSupervisionNoContable")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener los rubros no contables"
     *     )
     * )
     */
    public function indexNoContable()
    {
        try {
            $rubros = DB::select('SELECT * FROM cat_rubro_no_contable');
            return RespuestaAPI::exito('Listado de rubros no contables', $rubros);
        } catch (\Illuminate\Database\QueryException $e) {
            return RespuestaAPI::error('Error al obtener los rubros no contables: ' . $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/rubros/supervision-no-contable/{id}",
     *     summary="Mostrar un rubro de supervisión no contable específico",
     *     tags={"Rubros"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del rubro",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rubro no contable encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/RubroSupervisionNoContable")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Rubro no contable no encontrado"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener el rubro no contable"
     *     )
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/rubros/supervision-no-contable",
     *     summary="Crear un nuevo rubro de supervisión no contable",
     *     tags={"Rubros"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre"},
     *             @OA\Property(property="nombre", type="string", maxLength=255, example="Calidad del servicio")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Rubro no contable creado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/RubroSupervisionNoContable")
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Ya existe un rubro con este nombre"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al crear el rubro no contable"
     *     )
     * )
     */
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
            if ($e->errorInfo[1] == 1062) {
                return RespuestaAPI::error('Ya existe un rubro con este nombre.', 409);
            }
            return RespuestaAPI::error('Error al crear el rubro no contable: ' . $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/rubros/supervision-no-contable/{id}",
     *     summary="Actualizar un rubro de supervisión no contable existente",
     *     tags={"Rubros"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del rubro",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="nombre", type="string", maxLength=255, example="Calidad del servicio al cliente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rubro no contable actualizado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/RubroSupervisionNoContable")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="No hay datos para actualizar"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Rubro no contable no encontrado"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al actualizar el rubro no contable"
     *     )
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/rubros/supervision-no-contable/{id}",
     *     summary="Eliminar un rubro de supervisión no contable",
     *     tags={"Rubros"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del rubro",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rubro no contable eliminado exitosamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Rubro no contable no encontrado"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al eliminar el rubro no contable"
     *     )
     * )
     */
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