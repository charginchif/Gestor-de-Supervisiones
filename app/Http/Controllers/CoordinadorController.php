<?php

namespace App\Http\Controllers;

use App\Models\Coordinador;
use App\Models\User;
use App\Utils\RespuestaAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CoordinadorController extends Controller
{
    /**
     * Muestra una lista de todos los coordinadores.
     */
    public function index()
    {
        $coordinadores = Coordinador::all();
        return RespuestaAPI::exito('Lista de coordinadores', $coordinadores);
    }

    /**
     * Muestra un coordinador específico.
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
     * Crea un nuevo coordinador.
     */
    public function store(Request $request)
    {
        $usersData = $request->all();
        $results = [];
        $errors = [];

        if (!is_array(reset($usersData))) {
            $usersData = [$usersData];
        }

        foreach ($usersData as $userData) {
            $validator = Validator::make($userData, [
                'nombre'           => 'required|string|max:100',
                'apellido_paterno' => 'required|string|max:100',
                'apellido_materno' => 'required|string|max:100',
                'correo'           => 'required|email|unique:usuario,correo',
                'contrasena'       => 'required|string|min:8',
            ]);

            if ($validator->fails()) {
                $errors[] = [
                    'correo' => $userData['correo'] ?? 'N/A',
                    'errors' => $validator->errors()
                ];
                continue;
            }

            try {
                $result = User::crearCoordinador(
                    $userData['nombre'],
                    $userData['apellido_paterno'],
                    $userData['apellido_materno'],
                    $userData['correo'],
                    Hash::make($userData['contrasena'])
                );
                $results[] = $result;
            } catch (\Exception $e) {
                $errors[] = [
                    'correo' => $userData['correo'],
                    'error' => $e->getMessage()
                ];
            }
        }

        if (!empty($errors)) {
            return RespuestaAPI::error('Algunos coordinadores no pudieron ser creados', 422, ['errors' => $errors, 'created' => $results]);
        }

        return RespuestaAPI::exito('Coordinadores creados exitosamente', $results, 201);
    }

    /**
     * Actualiza la información de un coordinador.
     */
    public function update(Request $request, $id)
    {
        $coordinador = Coordinador::find($id);
        if (!$coordinador) {
            return RespuestaAPI::error('Coordinador no encontrado', 404);
        }
        
        $validator = Validator::make($request->all(), [
            'nombre'           => 'sometimes|string|max:100|regex:/^[\pL\s\-]+$/u',
            'apellido_paterno' => 'sometimes|string|max:100|regex:/^[\pL\s\-]+$/u',
            'apellido_materno' => 'sometimes|string|max:100|regex:/^[\pL\s\-]+$/u',
            'correo'           => 'sometimes|email|unique:usuario,correo,' . $coordinador->id_usuario,
        ]);

        if ($validator->fails()) {
            return RespuestaAPI::error('Datos inválidos', 422, $validator->errors());
        }

        try {
            DB::statement(
                'CALL sp_actualizar_coordinador(?, ?, ?, ?, ?, ?)',
                [
                    $id,
                    $coordinador->id_usuario,
                    $request->input('nombre', $coordinador->nombre),
                    $request->input('apellido_paterno', $coordinador->apellido_paterno),
                    $request->input('apellido_materno', $coordinador->apellido_materno),
                    $request->input('correo', $coordinador->correo),
                ]
            );

            $updatedCoordinador = Coordinador::find($id);
            return RespuestaAPI::exito('Coordinador actualizado exitosamente', $updatedCoordinador);

        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al actualizar el coordinador: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Elimina un coordinador.
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