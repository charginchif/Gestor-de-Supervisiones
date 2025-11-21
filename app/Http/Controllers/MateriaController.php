<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utils\RespuestaAPI;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MateriaController extends Controller
{

    public function index()
    {
        try {
            $user = Auth::user();
            $rol = strtolower($user->rol);
            $materias = [];

            if ($rol === 'coordinador') {
                $carrerasCoordinador = $this->getCarrerasDelCoordinador($user->id);

                if (!empty($carrerasCoordinador)) {
                    // Using a subquery to get materias from plan_estudio
                    $materias = DB::table('vw_admin_materias as vm')
                                ->whereIn('vm.id_materia', function($query) use ($carrerasCoordinador) {
                                    $query->select('id_materia')
                                          ->from('plan_estudio')
                                          ->whereIn('id_carrera', $carrerasCoordinador);
                                })
                                ->get();
                }
            } else {
                $materias = DB::select('SELECT * FROM vw_admin_materias');
            }
            
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

    private function getCarrerasDelCoordinador($idUsuario)
    {
        $coordinador = DB::table('coordinador')->where('usuario_id', $idUsuario)->first();
        if (!$coordinador) {
            return null;
        }

        return DB::table('coordinador_carrera')
                 ->where('id_coordinador', $coordinador->id_coordinador)
                 ->pluck('id_carrera')->toArray();
    }

    public function asignarDocente(Request $request)
    {
        $this->validate($request, [
            'id_materia' => 'required|integer|exists:materia,id_materia',
            'id_docente' => 'required|integer|exists:docente,id_docente',
        ]);

        $user = Auth::user();
        $rol = strtolower($user->rol);

        if ($rol === 'coordinador') {
            $carrerasCoordinador = $this->getCarrerasDelCoordinador($user->id);
            
            $planEstudio = DB::table('plan_estudio')->where('id_materia', $request->id_materia)->first();

            if (!$planEstudio || !in_array($planEstudio->id_carrera, $carrerasCoordinador)) {
                return RespuestaAPI::error('No tienes permiso para asignar docentes a esta materia.', 403);
            }
        }

        try {
            DB::statement('CALL sp_asignar_docente_materia(?, ?)', [$request->id_docente, $request->id_materia]);
            return RespuestaAPI::exito('Docente asignado a la materia exitosamente.', null, 201);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '45000') {
                return RespuestaAPI::error($e->errorInfo[2], 400);
            }
            return RespuestaAPI::error('Error al asignar el docente a la materia: ' . $e->getMessage(), 500);
        }
    }
}