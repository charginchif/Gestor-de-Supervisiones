<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\VwCoordGrupo;
use App\Models\VwGrupoAlumnos;
use App\Models\PlanEstudio;
use App\Utils\RespuestaAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Utils\GeneradorCodigos;


class GrupoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/grupos/admin",
     *     summary="Listar todos los grupos (Admin)",
     *     tags={"Grupos"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de grupos",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/VwGrupoAlumnos")
     *         )
     *     )
     * )
     */
    public function indexAdmin()
    {
        $grupos = VwGrupoAlumnos::select('*')
            ->get();
        return RespuestaAPI::exito('Lista de grupos', $grupos);
    }

    /**
     * @OA\Get(
     *     path="/grupos",
     *     summary="Listar todos los grupos",
     *     tags={"Grupos"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de grupos",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/VwGrupoAlumnos")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        // This method will not work as expected because the coordinator information is not available in the new view.
        $grupos = VwGrupoAlumnos::select('id_grupo', 'grupo', 'id_modalidad', 'id_carrera')
            ->groupBy('id_grupo', 'grupo', 'id_modalidad', 'id_carrera')
            ->get();
        return RespuestaAPI::exito('Lista de grupos', $grupos);
    }

    /**
     * @OA\Get(
     *     path="/grupos/{id}",
     *     summary="Mostrar un grupo",
     *     tags={"Grupos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del grupo",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Grupo encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/Grupo")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Grupo no encontrado"
     *     )
     * )
     */
    public function show($id)
    {
        $grupo = Grupo::find($id);
        if (!$grupo) {
            return RespuestaAPI::error('Grupo no encontrado', 404);
        }
        return RespuestaAPI::exito('Grupo encontrado', $grupo);
    }

    /**
     * @OA\Post(
     *     path="/grupos",
     *     summary="Crear un nuevo grupo",
     *     tags={"Grupos"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"acronimo", "id_ciclo", "id_turno", "id_nivel", "id_plan_estudio", "id_plantel"},
     *             @OA\Property(property="acronimo", type="string", maxLength=15, example="G-01"),
     *             @OA\Property(property="id_ciclo", type="integer", example=1),
     *             @OA\Property(property="id_turno", type="integer", example=1),
     *             @OA\Property(property="id_nivel", type="integer", example=1),
     *             @OA\Property(property="id_plan_estudio", type="integer", example=1),
     *             @OA\Property(property="id_plantel", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Grupo creado exitosamente"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Datos inválidos"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al crear el grupo"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'acronimo' => 'required|string|max:15',
            'id_ciclo' => 'required|integer',
            'id_turno' => 'required|integer',
            'id_nivel' => 'required|integer',
            'id_plan_estudio' => 'required|integer',
            'id_plantel' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return RespuestaAPI::error('Datos inválidos', 422, $validator->errors());
        }

        try {
        $codigoGenerado = GeneradorCodigos::generateRandomCode();
        $request->input('codigo', $codigoGenerado);

            DB::statement(
                'CALL sp_grupo_insertar(?, ?, ?, ?, ?, ?, ?)',
                [
                    $request->acronimo,
                    $request->id_ciclo,
                    $request->id_turno,
                    $request->id_nivel,
                    $request->id_plan_estudio,
                    $request->id_plantel,
                    $codigoGenerado,
                ]
            );
            return RespuestaAPI::exito('Grupo creado exitosamente', null, 201);
        } catch (\Exception $e) {
            // Extract the core error message from the exception
            $errorMessage = $e->getMessage();
            if (str_contains($errorMessage, 'SQLSTATE[45000]')) {
                preg_match('/1644 (.*)/', $errorMessage, $matches);
                $errorMessage = $matches[1] ?? 'Error en la operación.';
            }
            return RespuestaAPI::error('Error al crear el grupo: ' . $errorMessage, 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/grupos/{id}",
     *     summary="Actualizar un grupo existente",
     *     tags={"Grupos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del grupo",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="acronimo", type="string", maxLength=15, example="G-01-UPD"),
     *             @OA\Property(property="id_ciclo", type="integer", example=1),
     *             @OA\Property(property="id_turno", type="integer", example=1),
     *             @OA\Property(property="id_modalidad", type="integer", example=1),
     *             @OA\Property(property="id_nivel", type="integer", example=1),
     *             @OA\Property(property="id_plan_estudio", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Grupo actualizado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/Grupo")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Datos inválidos"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al actualizar el grupo"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        // La validación de existencia del grupo la hace el propio SP.
        $validator = Validator::make($request->all(), [
            'acronimo' => 'sometimes|string|max:15',
            'id_ciclo' => 'sometimes|integer',
            'id_turno' => 'sometimes|integer',
            'id_modalidad' => 'sometimes|integer',
            'id_nivel' => 'sometimes|integer',
            'id_plan_estudio' => 'sometimes|integer',
        ]);

        if ($validator->fails()) {
            return RespuestaAPI::error('Datos inválidos', 422, $validator->errors());
        }

        try {
            DB::statement(
                'CALL sp_grupo_actualizar(?, ?, ?, ?, ?, ?, ?)',
                [
                    $id,
                    $request->input('acronimo'),
                    $request->input('id_ciclo'),
                    $request->input('id_turno'),
                    $request->input('id_modalidad'),
                    $request->input('id_nivel'),
                    $request->input('id_plan_estudio'),
                ]
            );

            $updatedGrupo = Grupo::find($id);
            return RespuestaAPI::exito('Grupo actualizado exitosamente', $updatedGrupo);
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al actualizar el grupo: ' . $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/grupos/{id}",
     *     summary="Eliminar un grupo",
     *     tags={"Grupos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del grupo",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Grupo eliminado exitosamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Grupo no encontrado"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al eliminar el grupo"
     *     )
     * )
     */
    public function destroy($id)
    {
        $grupo = Grupo::find($id);
        if (!$grupo) {
            return RespuestaAPI::error('Grupo no encontrado', 404);
        }

        try {
            DB::statement('CALL sp_grupo_eliminar(?)', [$id]);
            return RespuestaAPI::exito('Grupo eliminado exitosamente', null, 200);
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al eliminar el grupo: ' . $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/grupos/asignar-plan",
     *     summary="Asignar un plan de estudio a un grupo",
     *     tags={"Grupos"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id_grupo", "id_plan_estudio"},
     *             @OA\Property(property="id_grupo", type="integer", example=1),
     *             @OA\Property(property="id_plan_estudio", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Plan de estudio asignado exitosamente"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Datos inválidos"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al asignar el plan de estudio"
     *     )
     * )
     */
    public function asignarPlan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_grupo' => 'required|integer',
            'id_plan_estudio' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return RespuestaAPI::error('Datos inválidos', 422, $validator->errors());
        }

        try {
            DB::statement(
                'CALL sp_grupo_asignar_plan(?, ?)',
                [
                    $request->id_grupo,
                    $request->id_plan_estudio,
                ]
            );
            return RespuestaAPI::exito('Plan de estudio asignado exitosamente', null, 200);
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al asignar el plan de estudio: ' . $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/grupos/{id_grupo}/quitar-plan",
     *     summary="Quitar un plan de estudio de un grupo",
     *     tags={"Grupos"},
     *     @OA\Parameter(
     *         name="id_grupo",
     *         in="path",
     *         required=true,
     *         description="ID del grupo",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Plan de estudio quitado exitosamente"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al quitar el plan de estudio"
     *     )
     * )
     */
    public function quitarPlan($id_grupo)
    {
        try {
            DB::statement('CALL sp_grupo_quitar_plan(?)', [$id_grupo]);
            return RespuestaAPI::exito('Plan de estudio quitado exitosamente', null, 200);
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al quitar el plan de estudio: ' . $e->getMessage(), 500);
        }
    }
}