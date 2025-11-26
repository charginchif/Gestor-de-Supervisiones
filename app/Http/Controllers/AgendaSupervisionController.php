<?php

namespace App\Http\Controllers;

use App\Utils\RespuestaAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgendaSupervisionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/",
     *     summary="Obtener todas las agendas de supervisión",
     *     tags={"Agendas de Supervisión"},
     *     @OA\Response(
     *         response=200,
     *         description="Agendas de supervisión obtenidas con éxito",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/VwCoordAgendaSupervision")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener las agendas de supervisión"
     *     )
     * )
     */
    public function index()
    {
        try {
            $agendas = DB::table('vw_coord_agenda_supervision')->get();
            return RespuestaAPI::exito('Agendas de supervisión obtenidas con éxito', $agendas);
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al obtener las agendas de supervisión: ' . $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/agendas-supervision",
     *     summary="Crear una nueva agenda de supervisión",
     *     tags={"Agendas de Supervisión"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"fecha", "id_coordinador", "id_horario", "estado"},
     *             @OA\Property(property="fecha", type="string", format="date", example="2024-01-01"),
     *             @OA\Property(property="id_coordinador", type="integer", example=1),
     *             @OA\Property(property="id_horario", type="integer", example=1),
     *             @OA\Property(property="estado", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Agenda de supervisión creada con éxito"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al crear la agenda de supervisión"
     *     )
     * )
     */
    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'fecha' => 'required|date',
                'id_coordinador' => 'required|integer',
                'id_horario' => 'required|integer',
                'estado' => 'required|integer',
            ]);

            $result = DB::select('CALL sp_agenda_supervision_insertar(?, ?, ?, ?)', [
                $request->input('fecha'),
                $request->input('id_coordinador'),
                $request->input('id_horario'),
                $request->input('estado'),
            ]);

            return RespuestaAPI::exito('Agenda de supervisión creada con éxito', $result, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $missingFields = implode(', ', array_keys($e->errors()));
            $errorMessage = 'Faltan campos requeridos o son inválidos: ' . $missingFields;
            
            $examplePayload = [
                'fecha' => 'YYYY-MM-DD',
                'id_coordinador' => 1,
                'id_horario' => 1,
                'estado' => 1
            ];

            return RespuestaAPI::error(
                'Error al crear la agenda de supervisión: ' . $errorMessage,
                422,
                ['ejemplo_de_uso_correcto' => $examplePayload]
            );
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al crear la agenda de supervisión: ' . $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/agendas-supervision/{id}",
     *     summary="Actualizar una agenda de supervisión existente",
     *     tags={"Agendas de Supervisión"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la agenda de supervisión",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="fecha", type="string", format="date", example="2024-01-02"),
     *             @OA\Property(property="id_coordinador", type="integer", example=1),
     *             @OA\Property(property="id_horario", type="integer", example=1),
     *             @OA\Property(property="estado", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Agenda de supervisión actualizada con éxito"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al actualizar la agenda de supervisión"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        try {
            $result = DB::select('CALL sp_agenda_supervision_actualizar(?, ?, ?, ?, ?)', [
                $id,
                $request->input('fecha'),
                $request->input('id_coordinador'),
                $request->input('id_horario'),
                $request->input('estado'),
            ]);

            return RespuestaAPI::exito('Agenda de supervisión actualizada con éxito', $result);
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al actualizar la agenda de supervisión: ' . $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/agendas-supervision/{id}",
     *     summary="Eliminar una agenda de supervisión",
     *     tags={"Agendas de Supervisión"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la agenda de supervisión",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Agenda de supervisión eliminada con éxito"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al eliminar la agenda de supervisión"
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            DB::statement('CALL sp_agenda_supervision_eliminar(?)', [$id]);

            return RespuestaAPI::exito('Agenda de supervisión eliminada con éxito', null);
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al eliminar la agenda de supervisión: ' . $e->getMessage(), 500);
        }
    }
}
