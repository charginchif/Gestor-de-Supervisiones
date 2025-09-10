<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Services\JwtService;
use App\Utils\RespuestaAPI;

class AuthController extends Controller
{
    private JwtService $jwt;

    public function __construct(JwtService $jwt)
    {
        $this->jwt = $jwt;
    }

    public function iniciarSesion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'correo'     => 'bail|required|email',
            'contrasena' => 'bail|required|string|min:6',
        ], [
            'correo.required' => 'El correo electrónico es obligatorio.',
            'correo.email' => 'El formato del correo electrónico no es válido.',
            'contrasena.required' => 'La contraseña es obligatoria.',
            'contrasena.min' => 'La contraseña debe tener al menos :min caracteres.',
        ]);

        if ($validator->fails()) {
            return RespuestaAPI::error('Datos inválidos', RespuestaAPI::HTTP_ERROR_VALIDACION, [
                'errors' => $validator->errors()
            ]);
        }

        $user = User::where('correo', $request->correo)->first();
        if (!$user || !Hash::check($request->contrasena, $user->contrasena)) {
            return RespuestaAPI::error('Credenciales inválidas', RespuestaAPI::HTTP_NO_AUTORIZADO);
        }

        $user->ultimo_acceso = date('Y-m-d H:i:s');
        $user->save();

        $token = $this->jwt->crearToken([
            'usuario_id'   => $user->id,
            'rol'   => $user->id_rol ?? null,
            'email' => $user->correo,
        ]);

        return RespuestaAPI::exito('Inicio de sesión exitoso', [
            'access_token' => $token,
            'token_type'  => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->nombre,
                'email' => $user->correo,
                'id_rol' => $user->id_rol,
                'rol' => $user->rol
            ]
        ]);
    }
}