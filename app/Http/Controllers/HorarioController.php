<?php

namespace App\Http\Controllers;

use App\Models\VwAlumnoHorario;
use App\Utils\RespuestaAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HorarioController extends Controller
{
    /**
     * @OA\Get(
     *     path="/mi-horario",
     *     summary="Obtener mi horario (alumno)",
     *     tags={"Horarios"},
     *     @OA\Response(
     *         response=200,
     *         description="Horario obtenido con éxito",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/VwAlumnoHorario")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener el horario"
     *     )
     * )
     */
    public function getMiHorario(Request $request)
    {
        try {
            $user = Auth::user();
            $horario = VwAlumnoHorario::where('id_alumno', $user->id)->get();

            return RespuestaAPI::exito('Horario obtenido con éxito', $horario);
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al obtener el horario: ' . $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/horarios",
     *     summary="Obtener todos los horarios (admin)",
     *     tags={"Horarios"},
     *     @OA\Response(
     *         response=200,
     *         description="Horarios obtenidos con éxito",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/VwAdminHorario")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener los horarios"
     *     )
     * )
     */
    public function index()
    {
        try {
            $horarios = DB::table('vw_admin_horarios')->get();
            return RespuestaAPI::exito('Horarios obtenidos con éxito', $horarios);
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al obtener los horarios: ' . $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/horarios",
     *     summary="Crear un nuevo horario",
     *     tags={"Horarios"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id_ciclo", "id_cat_dia", "hora_inicio", "hora_fin"},
     *             @OA\Property(property="id_ciclo", type="integer", example=1),
     *             @OA\Property(property="id_cat_dia", type="integer", example=1),
     *             @OA\Property(property="hora_inicio", type="string", format="time", example="08:00:00"),
     *             @OA\Property(property="hora_fin", type="string", format="time", example="10:00:00")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Horario creado con éxito"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al crear el horario"
     *     )
     * )
     */
    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'id_ciclo' => 'required|integer',
                'id_cat_dia' => 'required|integer',
                'hora_inicio' => 'required',
                'hora_fin' => 'required',
            ]);

            DB::statement('CALL sp_horario_insertar(?, ?, ?, ?)', [
                $request->input('id_ciclo'),
                $request->input('id_cat_dia'),
                $request->input('hora_inicio'),
                $request->input('hora_fin'),
            ]);

            return RespuestaAPI::exito('Horario creado con éxito', null, 201);
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al crear el horario: ' . $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/horarios/{id}",
     *     summary="Actualizar un horario existente",
     *     tags={"Horarios"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del horario",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="id_ciclo", type="integer", example=1),
     *             @OA\Property(property="id_cat_dia", type="integer", example=1),
     *             @OA\Property(property="hora_inicio", type="string", format="time", example="08:00:00"),
     *             @OA\Property(property="hora_fin", type="string", format="time", example="10:00:00")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Horario actualizado con éxito"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al actualizar el horario"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        try {
            DB::statement('CALL sp_horario_actualizar(?, ?, ?, ?, ?)', [
                $id,
                $request->input('id_ciclo'),
                $request->input('id_cat_dia'),
                $request->input('hora_inicio'),
                $request->input('hora_fin'),
            ]);

            return RespuestaAPI::exito('Horario actualizado con éxito', null);
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al actualizar el horario: ' . $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/horarios/{id}",
     *     summary="Eliminar un horario",
     *     tags={"Horarios"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del horario",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Horario eliminado con éxito"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al eliminar el horario"
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            DB::statement('CALL sp_horario_eliminar(?)', [$id]);

            return RespuestaAPI::exito('Horario eliminado con éxito', null);
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al eliminar el horario: ' . $e->getMessage(), 500);
        }
    }
}
