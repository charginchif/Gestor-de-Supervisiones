<?php

namespace App\Http\Controllers;

use App\Models\Coordinador;
use App\Utils\RespuestaAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use OpenApi\Annotations as OA;

class CoordinadorController extends UsuarioController
{
    /**
     * @OA\Get(
     *     path="/coordinadores",
     *     summary="Listar todos los coordinadores",
     *     tags={"Coordinadores"},
     *     security={{"jwt": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Listado de coordinadores",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Coordinador")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $coordinadores = Coordinador::all();
        return RespuestaAPI::exito('Lista de coordinadores', $coordinadores);
    }

    /**
     * @OA\Get(
     *     path="/coordinadores/{id}",
     *     summary="Obtener un coordinador por su ID",
     *     tags={"Coordinadores"},
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
     *         description="Coordinador encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/Coordinador")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Coordinador no encontrado"
     *     )
     * )
     */
    public function show($id)
    {
        $coordinador = Coordinador::find($id);

        if (!$coordinador) {
            return RespuestaAPI::error('Coordinador no encontrado', 404);
        }

        return RespuestaAPI::exito('Coordinador encontrado', $coordinador);
    }

    /**
     * @OA\Post(
     *     path="/coordinadores",
     *     summary="Crear uno o más coordinadores",
     *     description="Crea un nuevo coordinador. Puede recibir un único objeto de coordinador o un arreglo de objetos.",
     *      tags={"Coordinadores"},
     *     security={{"jwt": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 required={"nombre", "apellido_paterno", "apellido_materno", "correo", "contrasena"},
     *                 @OA\Property(property="nombre", type="string", maxLength=100),
     *                 @OA\Property(property="apellido_paterno", type="string", maxLength=100),
     *                 @OA\Property(property="apellido_materno", type="string", maxLength=100),
     *                 @OA\Property(property="correo", type="string", format="email"),
     *                 @OA\Property(property="contrasena", type="string", format="password", minLength=8)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Coordinador(es) creado(s) exitosamente."
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Datos inválidos o algunos coordinadores no pudieron ser creados."
     *     )
     * )
     */
    public function store(Request $request)
    {
        return parent::storeCoordinador($request);
    }

    /**
     * @OA\Put(
     *     path="/coordinadores/{id}",
     *     summary="Actualizar la información de un coordinador",
     *     tags={"Coordinadores"},
     *     security={{"jwt": {}}},
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del coordinador a actualizar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="nombre", type="string", maxLength=100),
     *             @OA\Property(property="apellido_paterno", type="string", maxLength=100),
     *             @OA\Property(property="apellido_materno", type="string", maxLength=100),
     *             @OA\Property(property="correo", type="string", format="email")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Coordinador actualizado exitosamente."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Coordinador no encontrado."
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Datos inválidos."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al actualizar el coordinador."
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $coordinador = Coordinador::find($id);
        if (!$coordinador) {
            return RespuestaAPI::error('Coordinador no encontrado', 404);
        }

        // Obtener el usuario asociado
        $user = User::find($coordinador->usuario_id);
        if (!$user) {
            return RespuestaAPI::error('Usuario asociado no encontrado', 404);
        }
        
        $rules = [
            'nombre'           => 'sometimes|string|max:100|regex:/^[\pL\s\-]+$/u',
            'apellido_paterno' => 'sometimes|string|max:100|regex:/^[\pL\s\-]+$/u',
            'apellido_materno' => 'sometimes|string|max:100|regex:/^[\pL\s\-]+$/u',
            'contrasena'       => 'sometimes|string|min:8',
        ];

        // Only apply unique rule for 'correo' if it's present and different from the current one
        if ($request->has('correo')) {
            if ($request->input('correo') !== $user->correo) {
                $rules['correo'] = 'email|unique:usuario,correo,' . $user->id;
            } else {
                // If correo is present but unchanged, just validate it's an email
                $rules['correo'] = 'email';
            }
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return RespuestaAPI::error('Datos inválidos', 422, $validator->errors());
        }

        try {
            $userData = [
                'nombre'           => $request->input('nombre', $user->nombre),
                'apellido_paterno' => $request->input('apellido_paterno', $user->apellido_paterno),
                'apellido_materno' => $request->input('apellido_materno', $user->apellido_materno),
                'correo'           => $request->input('correo', $user->correo),
            ];

            if ($request->has('contrasena') && !empty($request->input('contrasena'))) {
                $userData['contrasena'] = Hash::make($request->input('contrasena'));
            }

            $user->update($userData);
            
            // Re-obtener el coordinador para reflejar los cambios del usuario en el objeto de respuesta
            $updatedCoordinador = Coordinador::find($id);
            return RespuestaAPI::exito('Coordinador actualizado exitosamente', $updatedCoordinador);

        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al actualizar el coordinador: ' . $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/coordinadores/{id}",
     *     summary="Eliminar un coordinador",
     *      tags={"Coordinadores"},
     *     security={{"jwt": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del coordinador a eliminar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Coordinador eliminado exitosamente."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Coordinador no encontrado."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al eliminar el coordinador."
     *     )
     * )
     */
    public function destroy($id)
    {
        $coordinador = Coordinador::find($id);
        if (!$coordinador) {
            return RespuestaAPI::error('Coordinador no encontrado', 404);
        }

        try {
            DB::statement('CALL sp_eliminar_usuario(?)', [$coordinador->id_usuario]);
            return RespuestaAPI::exito('Coordinador eliminado exitosamente', null, 200);
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al eliminar el coordinador: ' . $e->getMessage(), 500);
        }
    }

}