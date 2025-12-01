<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utils\RespuestaAPI;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;

class CarreraController extends Controller
{
    //------------------- Métodos para la gestión de Carreras -------------------//

    /**
     * @OA\Get(
     *     path="/carreras",
     *     summary="Listar todas las carreras",
     *     tags={"Carreras"},
     *     security={{"jwt": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Listado de carreras",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Carrera")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener las carreras"
     *     )
     * )
     */
    public function index()
    {
        try {
            $user = Auth::user();
            
            // Si el usuario es coordinador, solo devolver sus carreras asignadas
            if ($user && $user->id_rol == 3) { // 3 es el rol de coordinador
                $query = 'SELECT * FROM vw_coord_carreras WHERE id_coordinador = ?';
                $carreras = DB::select($query, [$user->id_usuario]);
            } else {
                // Si es administrador u otro rol, devolver todas las carreras
                $carreras = DB::select('SELECT * FROM vw_admin_carreras');
            }
            
            return RespuestaAPI::exito('Listado de carreras', $carreras);
        } catch (\Illuminate\Database\QueryException $e) {
            return RespuestaAPI::error('Error al obtener las carreras: ' . $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/carreras/{id}",
     *     summary="Obtener una carrera por su ID",
     *     tags={"Carreras"},
     *     security={{"jwt": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la carrera",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Carrera encontrada",
     *         @OA\JsonContent(ref="#/components/schemas/Carrera")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Carrera no encontrada"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener la carrera"
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $user = Auth::user();
            $query = 'SELECT * FROM vw_admin_carreras WHERE id_carrera = ?';
            $params = [$id];
            
            // Si el usuario es coordinador, agregar filtro para validar que es su carrera
            if ($user && $user->id_rol == 3) { // 3 es el rol de coordinador
                // Usar la vista de coordinador y filtrar por coordinador
                $query = 'SELECT c.* FROM vw_admin_carreras c ' .
                         'INNER JOIN vw_coord_carreras cc ON c.id_carrera = cc.id_carrera ' .
                         'WHERE c.id_carrera = ? AND cc.id_coordinador = ?';
                $params = [$id, $user->id_usuario];
            }
            
            $carrera = DB::select($query, $params);
            if (empty($carrera)) {
                return RespuestaAPI::error('Carrera no encontrada', 404);
            }
            return RespuestaAPI::exito('Carrera encontrada', $carrera[0]);
        } catch (\Illuminate\Database\QueryException $e) {
            return RespuestaAPI::error('Error al obtener la carrera: ' . $e->getMessage(), RespuestaAPI::HTTP_ERROR_INTERNO);
        }
    }

    /**
     * @OA\Post(
     *     path="/carreras",
     *     summary="Crear una nueva carrera",
     *     tags={"Carreras"},
     *     security={{"jwt": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre"},
     *             @OA\Property(property="nombre", type="string", maxLength=100, example="Ingeniería en Sistemas")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Carrera creada exitosamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación o la carrera ya existe"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al crear la carrera"
     *     )
     * )
     */
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
            if ($e->getCode() == 23000) {
                return RespuestaAPI::error('La carrera ya existe', 400);
            }
            if ($e->getCode() === '45000') {
                return RespuestaAPI::error($e->errorInfo[2], 400);
            }
            return RespuestaAPI::error('Error al crear la carrera.', 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/carreras/{id}",
     *     summary="Actualizar una carrera existente",
     *     tags={"Carreras"},
     *     security={{"jwt": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la carrera",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre"},
     *             @OA\Property(property="nombre", type="string", maxLength=100, example="Ingeniería en Software")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Carrera actualizada exitosamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación o la carrera ya existe"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al actualizar la carrera"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nombre' => 'required|string|max:100'
        ]);

        try {
            $user = Auth::user();
            
            // Si el usuario es coordinador, validar que sea su carrera
            if ($user && $user->id_rol == 3) { // 3 es el rol de coordinador
                $carrera = DB::select(
                    'SELECT c.* FROM vw_admin_carreras c ' .
                    'INNER JOIN vw_coord_carreras cc ON c.id_carrera = cc.id_carrera ' .
                    'WHERE c.id_carrera = ? AND cc.id_coordinador = ?',
                    [$id, $user->id_usuario]
                );
                
                if (empty($carrera)) {
                    return RespuestaAPI::error('No tienes permiso para actualizar esta carrera', 403);
                }
            }
            
            DB::statement(
                'CALL sp_carrera_actualizar(?, ?)',
                [$id, $request->input('nombre')]
            );

            return RespuestaAPI::exito('Carrera actualizada exitosamente', $request->all());

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                return RespuestaAPI::error('La carrera ya existe', 400);
            }
            if ($e->getCode() === '45000') {
                return RespuestaAPI::error($e->errorInfo[2], 400);
            }
            return RespuestaAPI::error('Error al actualizar la carrera: ' . $e->getMessage(), RespuestaAPI::HTTP_ERROR_INTERNO);
        }
    }

    /**
     * @OA\Delete(
     *     path="/carreras/{id}",
     *     summary="Eliminar una carrera",
     *     tags={"Carreras"},
     *     security={{"jwt": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la carrera",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Carrera eliminada exitosamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error al eliminar la carrera (e.g., dependencias)"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al eliminar la carrera"
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            $user = Auth::user();
            
            // Si el usuario es coordinador, validar que sea su carrera
            if ($user && $user->id_rol == 3) { // 3 es el rol de coordinador
                $carrera = DB::select(
                    'SELECT c.* FROM vw_admin_carreras c ' .
                    'INNER JOIN vw_coord_carreras cc ON c.id_carrera = cc.id_carrera ' .
                    'WHERE c.id_carrera = ? AND cc.id_coordinador = ?',
                    [$id, $user->id_usuario]
                );
                
                if (empty($carrera)) {
                    return RespuestaAPI::error('No tienes permiso para eliminar esta carrera', 403);
                }
            }
            
            DB::statement('CALL sp_carrera_eliminar(?)', [$id]);
            return RespuestaAPI::exito('Carrera eliminada exitosamente', null, 200);

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '45000') {
                return RespuestaAPI::error($e->errorInfo[2], 400);
            }
            return RespuestaAPI::error('Error al eliminar la carrera: ' . $e->getMessage(), RespuestaAPI::HTTP_ERROR_INTERNO);
        }
    }

    /**
     * @OA\Post(
     *     path="/asignarCarreraCoordinador",
     *     summary="Asignar una carrera a un coordinador",
     *     tags={"Carreras"},
     *     security={{"jwt": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id_coordinador", "id_carrera"},
     *             @OA\Property(property="id_coordinador", type="integer", example=1),
     *             @OA\Property(property="id_carrera", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Carrera asignada a coordinador exitosamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación o la asignación ya existe"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al asignar la carrera"
     *     )
     * )
     */
    public function asignarCarreraCoordinador(Request $request)
    {
        $this->validate($request, [
            'id_coordinador' => 'required|integer',
            'id_carrera' => 'required|integer'
        ]);

        try {
            DB::statement(
                'CALL sp_coordinador_carrera_insertar(?, ?)',
                [$request->input('id_coordinador'), $request->input('id_carrera')]
            );

            return RespuestaAPI::exito('Carrera asignada a coordinador exitosamente', null, 201);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                return RespuestaAPI::error('La asignación ya existe', 400);
            }
            if ($e->getCode() === '45000') {
                return RespuestaAPI::error($e->errorInfo[2], 400);
            }
            return RespuestaAPI::error('Error al asignar la carrera: ' . $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/asignarCarreraCoordinador",
     *     summary="Actualizar la asignación de una carrera a un coordinador",
     *     tags={"Carreras"},
     *     security={{"jwt": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id_coordinador", "id_carrera"},
     *             @OA\Property(property="id_coordinador", type="integer", example=1),
     *             @OA\Property(property="id_carrera", type="integer", example=1),
     *             @OA\Property(property="nuevo_id_coordinador", type="integer", example=2),
     *             @OA\Property(property="nuevo_id_carrera", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Asignación actualizada exitosamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación o la asignación ya existe"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al actualizar la asignación"
     *     )
     * )
     */
    public function actualizarCarreraCoordinador(Request $request)
    {
        $this->validate($request, [
            'id_coordinador' => 'required|integer',
            'id_carrera' => 'required|integer',
            'nuevo_id_coordinador' => 'sometimes|integer',
            'nuevo_id_carrera' => 'sometimes|integer'
        ]);

        try {
            DB::statement(
                'CALL sp_coordinador_carrera_actualizar(?, ?, ?, ?)',
                [
                    $request->input('id_coordinador'),
                    $request->input('id_carrera'),
                    $request->input('nuevo_id_coordinador'),
                    $request->input('nuevo_id_carrera')
                ]
            );

            return RespuestaAPI::exito('Asignación actualizada exitosamente', null);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                return RespuestaAPI::error('La asignación ya existe', 400);
            }
            if ($e->getCode() === '45000') {
                return RespuestaAPI::error($e->errorInfo[2], 400);
            }
            return RespuestaAPI::error('Error al actualizar la asignación: ' . $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/asignarCarreraCoordinador",
     *     summary="Eliminar la asignación de una carrera a un coordinador",
     *     tags={"Carreras"},
     *     security={{"jwt": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id_coordinador", "id_carrera"},
     *             @OA\Property(property="id_coordinador", type="integer", example=1),
     *             @OA\Property(property="id_carrera", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Asignación eliminada exitosamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error al eliminar la asignación"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al eliminar la asignación"
     *     )
     * )
     */
    public function eliminarCarreraCoordinador(Request $request)
    {
        $this->validate($request, [
            'id_coordinador' => 'required|integer',
            'id_carrera' => 'required|integer'
        ]);

        try {
            DB::statement(
                'CALL sp_coordinador_carrera_eliminar(?, ?)',
                [$request->input('id_coordinador'), $request->input('id_carrera')]
            );

            return RespuestaAPI::exito('Asignación eliminada exitosamente', null, 200);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '45000') {
                return RespuestaAPI::error($e->errorInfo[2], 400);
            }
            return RespuestaAPI::error('Error al eliminar la asignación: ' . $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/asignarCarreraCoordinador",
     *     summary="Obtener todas las asignaciones de carreras a coordinadores",
     *     tags={"Carreras"},
     *     security={{"jwt": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Listado de todas las asignaciones de carreras a coordinadores"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener las asignaciones"
     *     )
     * )
     */
    public function getAllAsignaciones()
    {
        try {
            $asignaciones = DB::select('SELECT * FROM vw_coord_carreras');
            return RespuestaAPI::exito('Listado de todas las asignaciones de carreras a coordinadores', $asignaciones);
        } catch (\Illuminate\Database\QueryException $e) {
            return RespuestaAPI::error('Error al obtener las asignaciones: ' . $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/carrerasPorCoordinador/{id}",
     *     summary="Obtener las carreras por coordinador",
     *     tags={"Carreras"},
     *     security={{"jwt": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del coordinador",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Listado de carreras del coordinador"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener las materias de las carreras"
     *     )
     * )
     */
    public function getCarrerasPorCoordinador($id)
    {
        try {
            $query = 'SELECT * FROM vw_coord_carreras ' .
                     'WHERE id_coordinador = ?';
            $carreras = DB::select($query, [$id]);
            return RespuestaAPI::exito('Listado de carreras del coordinador ' . $id, $carreras);
        } catch (\Illuminate\Database\QueryException $e) {
            return RespuestaAPI::error('Error al obtener las materias de las carreras: ' . $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/asignarCarreraPlantel",
     *     summary="Asignar una carrera a un plantel",
     *     tags={"Carreras"},
     *     security={{"jwt": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id_plantel", "id_carrera"},
     *             @OA\Property(property="id_plantel", type="integer", example=1),
     *             @OA\Property(property="id_carrera", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Carrera asignada a plantel exitosamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación o la asignación ya existe"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al asignar la carrera al plantel"
     *     )
     * )
     */
    public function asignarCarreraPlantel(Request $request)
    {
        $this->validate($request, [
            'id_plantel' => 'required|integer',
            'id_carrera' => 'required|integer'
        ]);

        try {
            DB::statement(
                'CALL sp_plantel_carrera_insertar(?, ?)',
                [$request->input('id_plantel'), $request->input('id_carrera')]
            );

            return RespuestaAPI::exito('Carrera asignada a plantel exitosamente', null, 201);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                return RespuestaAPI::error('La asignación ya existe', 400);
            }
            if ($e->getCode() === '45000') {
                return RespuestaAPI::error($e->errorInfo[2], 400);
            }
            return RespuestaAPI::error('Error al asignar la carrera al plantel: ' . $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/eliminarCarreraPlantel",
     *     summary="Eliminar la asignación de una carrera a un plantel",
     *     tags={"Carreras"},
     *     security={{"jwt": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id_plantel", "id_carrera"},
     *             @OA\Property(property="id_plantel", type="integer", example=1),
     *             @OA\Property(property="id_carrera", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Asignación de carrera a plantel eliminada exitosamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error al eliminar la asignación"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al eliminar la asignación"
     *     )
     * )
     */
    public function eliminarCarreraPlantel(Request $request)
    {
        $this->validate($request, [
            'id_plantel' => 'required|integer',
            'id_carrera' => 'required|integer'
        ]);

        try {
            DB::statement(
                'CALL sp_plantel_carrera_eliminar(?, ?)',
                [$request->input('id_plantel'), $request->input('id_carrera')]
            );

            return RespuestaAPI::exito('Asignación de carrera a plantel eliminada exitosamente', null, 200);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '45000') {
                return RespuestaAPI::error($e->errorInfo[2], 400);
            }
            return RespuestaAPI::error('Error al eliminar la asignación: ' . $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/carrerasPorPlantel/{id}",
     *     summary="Obtener las carreras por plantel",
     *     tags={"Carreras"},
     *     security={{"jwt": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del plantel",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Listado de carreras del plantel"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener las carreras del plantel"
     *     )
     * )
     */
    public function getCarrerasPorPlantel($id)
    {
        try {
            $query = 'SELECT * FROM vw_admin_plantel_carrera WHERE id_plantel = ?';
            $carreras = DB::select($query, [$id]);
            return RespuestaAPI::exito('Listado de carreras del plantel ' . $id, $carreras);
        } catch (\Illuminate\Database\QueryException $e) {
            return RespuestaAPI::error('Error al obtener las carreras del plantel: ' . $e->getMessage(), 500);
        }
    }

    //------------------- Métodos para la gestión de asignación de Carreras a Planteles -------------------

    /**
     * @OA\Get(
     *     path="/carrerasPorPlantel",
     *     summary="Obtener todas las asignaciones de carreras a planteles",
     *     tags={"Carreras"},
     *     security={{"jwt": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Listado de todas las asignaciones de carreras a planteles"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener las asignaciones"
     *     )
     * )
     */
    public function getAllCarrerasPorPlantel()
    {
        try {
            $asignaciones = DB::select('SELECT * FROM vw_admin_plantel_carrera');
            return RespuestaAPI::exito('Listado de todas las asignaciones de carreras a planteles', $asignaciones);
        } catch (\Illuminate\Database\QueryException $e) {
            return RespuestaAPI::error('Error al obtener las asignaciones: ' . $e->getMessage(), 500);
        }
    }

    //------------------- Métodos para la gestión de Turnos de Planteles -------------------

    /**
     * @OA\Post(
     *     path="/plantel-turno",
     *     summary="Asignar un turno a un plantel",
     *     tags={"Planteles"},
     *     security={{"jwt": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id_plantel", "id_dia", "id_turno", "hora_inicio", "hora_fin", "hora_descanso", "duracion_bloques", "duracion_descanso"},
     *             @OA\Property(property="id_plantel", type="integer", example=1),
     *             @OA\Property(property="id_dia", type="integer", example=1),
     *             @OA\Property(property="id_turno", type="integer", example=1),
     *             @OA\Property(property="hora_inicio", type="string", format="time", example="08:00:00"),
     *             @OA\Property(property="hora_fin", type="string", format="time", example="14:00:00"),
     *             @OA\Property(property="hora_descanso", type="string", format="time", example="11:00:00"),
     *             @OA\Property(property="duracion_bloques", type="integer", example=50),
     *             @OA\Property(property="duracion_descanso", type="integer", example=10)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Turno asignado a plantel exitosamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación o la asignación ya existe"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al asignar el turno al plantel"
     *     )
     * )
     */
    public function asignarTurnoPlantel(Request $request)
    {
        $this->validate($request, [
            'id_plantel' => 'required|integer',
            'id_dia' => 'required|integer',
            'id_turno' => 'required|integer',
            'hora_inicio' => 'required',
            'hora_fin' => 'required',
            'hora_descanso' => 'required',
            'duracion_bloques' => 'required|integer',
            'duracion_descanso' => 'required|integer',
        ]);

        try {
            DB::statement(
                'CALL sp_plantel_turno_insertar(?, ?, ?, ?, ?, ?, ?, ?)',
                [
                    $request->input('id_plantel'),
                    $request->input('id_dia'),
                    $request->input('id_turno'),
                    $request->input('hora_inicio'),
                    $request->input('hora_fin'),
                    $request->input('hora_descanso'),
                    $request->input('duracion_bloques'),
                    $request->input('duracion_descanso'),
                ]
            );

            return RespuestaAPI::exito('Turno asignado a plantel exitosamente', null, 201);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                return RespuestaAPI::error('La asignación ya existe', 400);
            }
            if ($e->getCode() === '45000') {
                return RespuestaAPI::error($e->errorInfo[2], 400);
            }
            return RespuestaAPI::error('Error al asignar el turno al plantel: ' . $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/plantel-turno/{id}",
     *     summary="Eliminar un turno de un plantel",
     *     tags={"Planteles"},
     *     security={{"jwt": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del turno del plantel",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Turno de plantel eliminado exitosamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error al eliminar el turno del plantel"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al eliminar el turno del plantel"
     *     )
     * )
     */
    public function eliminarTurnoPlantel($id)
    {
        try {
            DB::statement('CALL sp_plantel_turno_eliminar(?)', [$id]);
            return RespuestaAPI::exito('Turno de plantel eliminado exitosamente', null, 200);

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '45000') {
                return RespuestaAPI::error($e->errorInfo[2], 400);
            }
            return RespuestaAPI::error('Error al eliminar el turno del plantel: ' . $e->getMessage(), 500);
        }
    }

    // --- Métodos para la gestión de Carrera-Modalidad ---

    /**
     * @OA\Get(
     *     path="/carrera-modalidad",
     *     summary="Listar carreras y sus modalidades",
     *     tags={"Carreras"},
     *     security={{"jwt": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Listado de carreras y sus modalidades"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener los datos"
     *     )
     * )
     */
    public function indexCarreraModalidad()
    {
        try {
            $data = DB::select('SELECT * FROM vw_carrera_modalidades');
            return RespuestaAPI::exito('Listado de carreras y sus modalidades', $data);
        } catch (\Illuminate\Database\QueryException $e) {
            return RespuestaAPI::error('Error al obtener los datos: ' . $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/carrera-modalidad",
     *     summary="Asignar una modalidad a una carrera",
     *     tags={"Carreras"},
     *     security={{"jwt": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id_carrera", "id_modalidad"},
     *             @OA\Property(property="id_carrera", type="integer", example=1),
     *             @OA\Property(property="id_modalidad", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Modalidad asignada a la carrera exitosamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación"
     *     ),
     *      @OA\Response(
     *         response=409,
     *         description="Esta modalidad ya está asignada a esta carrera"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al asignar la modalidad"
     *     )
     * )
     */
    public function storeCarreraModalidad(Request $request)
    {
        $this.validate($request, [
            'id_carrera'   => 'required|integer',
            'id_modalidad' => 'required|integer',
        ]);

        try {
            DB::statement(
                'CALL sp_carrera_modalidad_agregar(?, ?)',
                [
                    $request->input('id_carrera'),
                    $request->input('id_modalidad'),
                ]
            );

            return RespuestaAPI::exito('Modalidad asignada a la carrera exitosamente', null, 201);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '45000') {
                return RespuestaAPI::error($e->errorInfo[2], 400);
            }
            // Handle duplicate entry
            if ($e->errorInfo[1] == 1062) {
                return RespuestaAPI::error('Esta modalidad ya está asignada a esta carrera.', 409);
            }
            return RespuestaAPI::error('Error al asignar la modalidad: ' . $e->getMessage(), 500);
        }
    }
    /**
     * @OA\Delete(
     *     path="/carrera-modalidad",
     *     summary="Eliminar la asignación de una modalidad a una carrera",
     *     tags={"Carreras"},
     *     security={{"jwt": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id_carrera", "id_modalidad"},
     *             @OA\Property(property="id_carrera", type="integer", example=1),
     *             @OA\Property(property="id_modalidad", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Asignación de modalidad eliminada exitosamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontró la asignación para eliminar o ya fue eliminada"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al eliminar la asignación"
     *     )
     * )
     */
    public function destroyCarreraModalidad(Request $request)
    {
        $this->validate($request, [
            'id_carrera'   => 'required|integer',
            'id_modalidad' => 'required|integer',
        ]);

        try {
            $result = DB::select(
                'CALL sp_carrera_modalidad_eliminar(?, ?)',
                [
                    $request->input('id_carrera'),
                    $request->input('id_modalidad'),
                ]
            );

            if (isset($result[0]->filas_afectadas) && $result[0]->filas_afectadas > 0) {
                return RespuestaAPI::exito('Asignación de modalidad eliminada exitosamente', null, 200);
            } else {
                return RespuestaAPI::error('No se encontró la asignación para eliminar o ya fue eliminada.', 404);
            }

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '45000') {
                return RespuestaAPI::error($e->errorInfo[2], 400);
            }
            return RespuestaAPI::error('Error al eliminar la asignación: ' . $e->getMessage(), 500);
        }
    }
}