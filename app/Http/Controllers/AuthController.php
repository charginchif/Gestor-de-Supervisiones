<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
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
        try {
            $this->validate($request, [
                'correo'     => 'required|email',
                'contrasena' => 'required|string|min:4',
            ]);
        } catch (ValidationException $e) {
            return RespuestaAPI::error('Datos inválidos', RespuestaAPI::HTTP_ERROR_VALIDACION, [
                'errors' => $e->errors()
            ]);
        }

        $user = User::where('correo', $request->correo)->first();
        if (!$user || !Hash::check($request->contrasena, $user->contrasena)) {
            return RespuestaAPI::error('Credenciales inválidas', RespuestaAPI::HTTP_NO_AUTORIZADO);
        }

        

        $user->ultimo_acceso = date('Y-m-d H:i:s');
        $user->save();

        $token = $this->jwt->crearToken([
            'sub'   => $user->id,
            'rol'   => $user->id_rol ?? null,
            'email' => $user->correo,
        ]);

        return RespuestaAPI::exito('Inicio de sesión exitoso', [
            'access_token' => $token,
            'token_type'  => 'Bearer',
           // 'expires_in' => (int) env('JWT_TTL', 3600),
            'user' => [
                'id' => $user->id,
                'name' => $user->nombre,
                'email' => $user->correo,
                'role' => $user->id_rol
            ]
        ]);
    }
}
