<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Utils\RespuestaAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::all();
        return RespuestaAPI::exito('Lista de usuarios', $usuarios);
    }

    public function show($id)
    {
        $usuario = User::find($id);
        if (!$usuario) {
            return RespuestaAPI::error('Usuario no encontrado', RespuestaAPI::HTTP_NO_ENCONTRADO);
        }
        return RespuestaAPI::exito('Usuario encontrado', $usuario);
    }

    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'nombre' => 'required|string|max:100',
                'apellido_paterno' => 'required|string|max:100',
                'apellido_materno' => 'required|string|max:100',
                'correo' => 'required|email|unique:usuario,correo',
                'contrasena' => 'required|string|min:8',
                'id_rol' => 'required|integer|exists:rol,id',
            ]);
        } catch (ValidationException $e) {
            return RespuestaAPI::error('Datos inválidos', RespuestaAPI::HTTP_ERROR_VALIDACION, ['errors' => $e->errors()]);
        }

        $data = $request->all();
        $data['contrasena'] = Hash::make($data['contrasena']);

        $usuario = User::create($data);

        return RespuestaAPI::exito('Usuario creado exitosamente', $usuario, RespuestaAPI::HTTP_CREADO);
    }

    public function update(Request $request, $id)
    {
        $usuario = User::find($id);
        if (!$usuario) {
            return RespuestaAPI::error('Usuario no encontrado', RespuestaAPI::HTTP_NO_ENCONTRADO);
        }

        try {
            $this->validate($request, [
                'nombre' => 'sometimes|required|string|max:100',
                'apellido_paterno' => 'sometimes|required|string|max:100',
                'apellido_materno' => 'sometimes|required|string|max:100',
                'correo' => 'sometimes|required|email|unique:usuario,correo,' . $id,
                'contrasena' => 'sometimes|string|min:8',
                'id_rol' => 'sometimes|required|integer|exists:rol,id',
                'estatus' => 'sometimes|required|in:activo,inactivo',
            ]);
        } catch (ValidationException $e) {
            return RespuestaAPI::error('Datos inválidos', RespuestaAPI::HTTP_ERROR_VALIDACION, ['errors' => $e->errors()]);
        }

        $data = $request->all();
        if ($request->has('contrasena')) {
            $data['contrasena'] = Hash::make($data['contrasena']);
        }

        $usuario->update($data);

        return RespuestaAPI::exito('Usuario actualizado exitosamente', $usuario);
    }

    public function destroy($id)
    {
        $usuario = User::find($id);
        if (!$usuario) {
            return RespuestaAPI::error('Usuario no encontrado', RespuestaAPI::HTTP_NO_ENCONTRADO);
        }

        $usuario->delete();

        return RespuestaAPI::exito('Usuario eliminado exitosamente', null, RespuestaAPI::HTTP_NO_CONTENIDO);
    }
}
