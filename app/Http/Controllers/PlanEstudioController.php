<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Utils\RespuestaAPI;
use Illuminate\Validation\ValidationException;

class PlanEstudioController extends Controller
{
    /**
     * @OA\Get(
     *     path="/planes-estudio/carrera/{id_carrera}",
     *     summary="Obtener el plan de estudio de una carrera",
     *     tags={"Planes de Estudio"},
     *     @OA\Parameter(
     *         name="id_carrera",
     *         in="path",
     *         required=true,
     *         description="ID de la carrera",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Plan de estudio obtenido con éxito",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id_carrera", type="integer"),
     *                 @OA\Property(property="id_modalidad", type="integer"),
     *                 @OA\Property(property="materias", type="array", @OA\Items(
     *                     @OA\Property(property="id_materia", type="integer"),
     *                     @OA\Property(property="id_cat_nivel", type="integer")
     *                 ))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="No se encontró un plan de estudio para la carrera especificada"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="La carrera no existe"
     *     )
     * )
     */
    public function index($id_carrera)
    {
        // Primero, verificar si la carrera existe
        $carrera = DB::table('carrera')->where('id_carrera', $id_carrera)->first();
        if (!$carrera) {
            return RespuestaAPI::error('La carrera no existe', 404);
        }

        $planEstudio = DB::table('vw_admin_plan_estudio')->where('id_carrera', $id_carrera)->get();

        if ($planEstudio->isEmpty()) {
            return RespuestaAPI::error('No se encontró un plan de estudio para la carrera especificada', 204);
        }

        $grouped = [];
        foreach ($planEstudio as $item) {
            $key = $item->id_carrera . '-' . $item->id_modalidad;
            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'id_carrera' => $item->id_carrera,
                    'id_modalidad' => $item->id_modalidad,
                    'materias' => [],
                ];
            }
            $grouped[$key]['materias'][] = [
                'id_materia' => $item->id_materia,
                'id_cat_nivel' => $item->id_cat_nivel,
            ];
        }

        return RespuestaAPI::exito('Éxito', array_values($grouped));
    }

    /**
     * @OA\Get(
     *     path="/planes-estudio",
     *     summary="Obtener todos los planes de estudio",
     *     tags={"Planes de Estudio"},
     *     @OA\Response(
     *         response=200,
     *         description="Planes de estudio obtenidos con éxito",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id_carrera", type="integer"),
     *                 @OA\Property(property="id_modalidad", type="integer"),
     *                 @OA\Property(property="materias", type="array", @OA\Items(
     *                     @OA\Property(property="id_materia", type="integer"),
     *                     @OA\Property(property="id_cat_nivel", type="integer")
     *                 ))
     *             )
     *         )
     *     )
     * )
     */
    public function indexAll()
    {
        $planEstudio = DB::table('vw_admin_plan_estudio')->get();

        $grouped = [];
        foreach ($planEstudio as $item) {
            $key = $item->id_carrera . '-' . $item->id_modalidad;
            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'id_carrera' => $item->id_carrera,
                    'id_modalidad' => $item->id_modalidad,
                    'materias' => [],
                ];
            }
            $grouped[$key]['materias'][] = [
                'id_materia' => $item->id_materia,
                'id_cat_nivel' => $item->id_cat_nivel,
            ];
        }

        return RespuestaAPI::exito('Éxito', array_values($grouped));
    }

    /**
     * @OA\Post(
     *     path="/planes-estudio",
     *     summary="Crear un nuevo plan de estudio",
     *     tags={"Planes de Estudio"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id_carrera", "id_modalidad", "materias"},
     *             @OA\Property(property="id_carrera", type="integer", example=1),
     *             @OA\Property(property="id_modalidad", type="integer", example=1),
     *             @OA\Property(property="materias", type="array", @OA\Items(
     *                 required={"id_materia", "id_cat_nivel"},
     *                 @OA\Property(property="id_materia", type="integer", example=1),
     *                 @OA\Property(property="id_cat_nivel", type="integer", example=1)
     *             ))
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Plan de estudio creado con éxito"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Datos de entrada no válidos"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al crear el plan de estudio"
     *     )
     * )
     */
    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'id_carrera' => 'required|integer',
                'id_modalidad' => 'required|integer',
                'materias' => 'required|array',
                'materias.*.id_materia' => 'required|integer',
                'materias.*.id_cat_nivel' => 'required|integer',
            ]);

            $json_data = json_encode($request->all());

            $resultado = DB::select('CALL sp_plan_estudio_crear(?)', [$json_data]);

            return RespuestaAPI::exito('Plan de estudio creado con éxito', $resultado, 201);
        } catch (ValidationException $e) {
            $example = [
                'id_carrera' => 1,
                'id_modalidad' => 2,
                'materias' => [
                    ['id_materia' => 101, 'id_cat_nivel' => 1],
                    ['id_materia' => 102, 'id_cat_nivel' => 2],
                ]
            ];
            $customMessage = 'La estructura de los datos es incorrecta. Asegúrese de que la petición siga el formato de ejemplo.';
            return RespuestaAPI::error($customMessage, 422, ['ejemplo' => $example, 'detalles' => $e->errors()]);
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al crear el plan de estudio: ' . $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/planes-estudio/{id_plan_estudio}",
     *     summary="Actualizar un plan de estudio existente",
     *     tags={"Planes de Estudio"},
     *     @OA\Parameter(
     *         name="id_plan_estudio",
     *         in="path",
     *         required=true,
     *         description="ID del plan de estudio",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="id_carrera", type="integer", example=1),
     *             @OA\Property(property="id_modalidad", type="integer", example=1),
     *             @OA\Property(property="id_materia", type="integer", example=1),
     *             @OA\Property(property="id_cat_nivel", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Plan de estudio actualizado con éxito"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Datos de entrada no válidos"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al actualizar el plan de estudio"
     *     )
     * )
     */
    public function update(Request $request, $id_plan_estudio)
    {
        try {
            $this->validate($request, [
                'id_carrera' => 'integer|nullable',
                'id_modalidad' => 'integer|nullable',
                'id_materia' => 'integer|nullable|required_with:id_cat_nivel',
                'id_cat_nivel' => 'integer|nullable|required_with:id_materia',
            ]);

            // Check if at least one field is present
            if (!$request->hasAny(['id_carrera', 'id_modalidad', 'id_materia', 'id_cat_nivel'])) {
                // Using a custom message, but this will be caught by the ValidationException handler
                throw ValidationException::withMessages(['fields' => 'Debe proporcionar al menos un campo para actualizar (id_carrera, id_modalidad, o id_materia y id_cat_nivel).']);
            }

            $resultado = DB::select(
                'CALL sp_plan_estudio_actualizar(?, ?, ?, ?, ?)',
                [
                    $id_plan_estudio,
                    $request->input('id_carrera'),
                    $request->input('id_modalidad'),
                    $request->input('id_materia'),
                    $request->input('id_cat_nivel'),
                ]
            );

            return RespuestaAPI::exito('Plan de estudio actualizado con éxito', $resultado);
        } catch (ValidationException $e) {
            return RespuestaAPI::error('Datos de entrada no válidos', 422, $e->errors());
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al actualizar el plan de estudio: ' . $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/planes-estudio/{id_plan_estudio}",
     *     summary="Eliminar un plan de estudio",
     *     tags={"Planes de Estudio"},
     *     @OA\Parameter(
     *         name="id_plan_estudio",
     *         in="path",
     *         required=true,
     *         description="ID del plan de estudio",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Plan de estudio eliminado con éxito"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al eliminar el plan de estudio"
     *     )
     * )
     */
    public function destroy($id_plan_estudio)
    {
        try {
            $resultado = DB::select('CALL sp_plan_estudio_eliminar(?)', [$id_plan_estudio]);
            
            return RespuestaAPI::exito('Plan de estudio eliminado con éxito', $resultado);
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al eliminar el plan de estudio: ' . $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/planes-estudio/materia",
     *     summary="Eliminar una materia de un plan de estudio",
     *     tags={"Planes de Estudio"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id_plan_estudio", "id_cat_nivel", "id_materia"},
     *             @OA\Property(property="id_plan_estudio", type="integer", example=1),
     *             @OA\Property(property="id_cat_nivel", type="integer", example=1),
     *             @OA\Property(property="id_materia", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Materia eliminada del plan de estudio con éxito"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Datos de entrada no válidos"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al eliminar la materia del plan de estudio"
     *     )
     * )
     */
    public function destroyMateria(Request $request)
    {
        try {
            $this->validate($request, [
                'id_plan_estudio' => 'required|integer',
                'id_cat_nivel'    => 'required|integer',
                'id_materia'      => 'required|integer',
            ]);

            DB::select('CALL sp_plan_estudio_eliminar_materia(?, ?, ?)', [
                $request->id_plan_estudio,
                $request->id_cat_nivel,
                $request->id_materia
            ]);
            
            return RespuestaAPI::exito('Materia eliminada del plan de estudio con éxito');
        } catch (ValidationException $e) {
            return RespuestaAPI::error('Datos de entrada no válidos', 422, $e->errors());
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al eliminar la materia del plan de estudio: ' . $e->getMessage(), 500);
        }
    }
}
