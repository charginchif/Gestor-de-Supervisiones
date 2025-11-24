<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utils\RespuestaAPI;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MateriaController extends Controller
{

    /**
     * @OA\Get(
     *     path="/materias",
     *     summary="Listar todas las materias",
     *     tags={"Materias"},
     *     @OA\Response(
     *         response=200,
     *         description="Listado de materias",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Materia")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener las materias"
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/materias/{id}",
     *     summary="Obtener una materia por su ID",
     *     tags={"Materias"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la materia",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Materia encontrada",
     *         @OA\JsonContent(ref="#/components/schemas/Materia")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Materia no encontrada"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener la materia"
     *     )
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/materias",
     *     summary="Crear una nueva materia",
     *     tags={"Materias"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre"},
     *             @OA\Property(property="nombre", type="string", maxLength=100, example="Cálculo Diferencial")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Materia creada exitosamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al crear la materia"
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

    /**
     * @OA\Put(
     *     path="/materias/{id}",
     *     summary="Actualizar una materia existente",
     *     tags={"Materias"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la materia",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre"},
     *             @OA\Property(property="nombre", type="string", maxLength=100, example="Cálculo Integral")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Materia actualizada exitosamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al actualizar la materia"
     *     )
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/materias/{id}",
     *     summary="Eliminar una materia",
     *     tags={"Materias"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la materia",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Materia eliminada exitosamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error al eliminar la materia (e.g., dependencias)"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al eliminar la materia"
     *     )
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/materias/asignar-docente",
     *     summary="Asignar un docente a una materia",
     *     tags={"Materias"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id_materia", "id_docente"},
     *             @OA\Property(property="id_materia", type="integer", example=1),
     *             @OA\Property(property="id_docente", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Docente asignado a la materia exitosamente"
     *     ),
     *      @OA\Response(
     *         response=403,
     *         description="No tienes permiso para asignar docentes a esta materia"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al asignar el docente a la materia"
     *     )
     * )
     */
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