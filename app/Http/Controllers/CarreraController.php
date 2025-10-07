<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utils\RespuestaAPI;
use Illuminate\Support\Facades\DB;

class CarreraController extends Controller
{
    //------------------- Métodos para la gestión de Carreras -------------------//

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
            return RespuestaAPI::error('Error al obtener la carrera: ' . $e->getMessage(), RespuestaAPI::HTTP_ERROR_INTERNO);
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
                'CALL sp_carrera_actualizar(?, ?)',
                [$id, $request->input('nombre')]
            );

            return RespuestaAPI::exito('Carrera actualizada exitosamente', $request->all());

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '45000') {
                return RespuestaAPI::error($e->errorInfo[2], 400);
            }
            return RespuestaAPI::error('Error al actualizar la carrera: ' . $e->getMessage(), RespuestaAPI::HTTP_ERROR_INTERNO);
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
            return RespuestaAPI::error('Error al eliminar la carrera: ' . $e->getMessage(), RespuestaAPI::HTTP_ERROR_INTERNO);
        }
    }

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
            if ($e->getCode() === '45000') {
                return RespuestaAPI::error($e->errorInfo[2], 400);
            }
            return RespuestaAPI::error('Error al asignar la carrera: ' . $e->getMessage(), 500);
        }
    }

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
            if ($e->getCode() === '45000') {
                return RespuestaAPI::error($e->errorInfo[2], 400);
            }
            return RespuestaAPI::error('Error al actualizar la asignación: ' . $e->getMessage(), 500);
        }
    }

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

    public function getAllAsignaciones()
    {
        try {
            $asignaciones = DB::select('SELECT * FROM vw_coord_carreras');
            return RespuestaAPI::exito('Listado de todas las asignaciones de carreras a coordinadores', $asignaciones);
        } catch (\Illuminate\Database\QueryException $e) {
            return RespuestaAPI::error('Error al obtener las asignaciones: ' . $e->getMessage(), 500);
        }
    }

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
            if ($e->getCode() === '45000') {
                return RespuestaAPI::error($e->errorInfo[2], 400);
            }
            return RespuestaAPI::error('Error al asignar la carrera al plantel: ' . $e->getMessage(), 500);
        }
    }

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

    public function getCarrerasPorPlantel($id)
    {
        try {
            $query = 'SELECT c.id_carrera, c.nombre, c.id_incorporacion, pc.id_plantel FROM carrera c JOIN plantel_carrera pc ON c.id_carrera = pc.id_carrera WHERE pc.id_plantel = ?';
            $carreras = DB::select($query, [$id]);
            return RespuestaAPI::exito('Listado de carreras del plantel ' . $id, $carreras);
        } catch (\Illuminate\Database\QueryException $e) {
            return RespuestaAPI::error('Error al obtener las carreras del plantel: ' . $e->getMessage(), 500);
        }
    }

    //------------------- Métodos para la gestión de asignación de Carreras a Planteles -------------------

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
            if ($e->getCode() === '45000') {
                return RespuestaAPI::error($e->errorInfo[2], 400);
            }
            return RespuestaAPI::error('Error al asignar el turno al plantel: ' . $e->getMessage(), 500);
        }
    }

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

    public function actualizarTurnoPlantel(Request $request, $id)
    {
        $this->validate($request, [
            'id_plantel' => 'sometimes|integer',
            'id_dia' => 'sometimes|integer',
            'id_turno' => 'sometimes|integer',
            'hora_inicio' => 'sometimes',
            'hora_fin' => 'sometimes',
            'hora_descanso' => 'sometimes',
            'duracion_bloques' => 'sometimes|integer',
            'duracion_descanso' => 'sometimes|integer',
        ]);

        try {
            DB::statement(
                'CALL sp_plantel_turno_actualizar(?, ?, ?, ?, ?, ?, ?, ?, ?)',
                [
                    $id,
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

            return RespuestaAPI::exito('Turno de plantel actualizado exitosamente', null);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '45000') {
                return RespuestaAPI::error($e->errorInfo[2], 400);
            }
            return RespuestaAPI::error('Error al actualizar el turno del plantel: ' . $e->getMessage(), 500);
        }
    }
}