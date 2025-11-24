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

    /**
     * @OA\Post(
     *     path="/login",
     *     summary="Iniciar sesión",
     *     tags={"Autenticación"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"correo","contrasena"},
     *             @OA\Property(property="correo", type="string", format="email", example="admin@example.com"),
     *             @OA\Property(property="contrasena", type="string", format="password", example="password"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Inicio de sesión exitoso",
     *         @OA\JsonContent(
     *             @OA\Property(property="access_token", type="string"),
     *             @OA\Property(property="token_type", type="string", example="Bearer"),
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="nombre", type="string"),
     *                 @OA\Property(property="apellido_paterno", type="string"),
     *                 @OA\Property(property="apellido_materno", type="string"),
     *                 @OA\Property(property="correo", type="string"),
     *                 @OA\Property(property="id_rol", type="integer"),
     *                 @OA\Property(property="fecha_registro", type="string", format="date-time"),
     *                 @OA\Property(property="ultimo_acceso", type="string", format="date-time"),
     *                 @OA\Property(property="rol", type="object")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciales inválidas"
     *     ),
     *      @OA\Response(
     *         response=422,
     *         description="Datos de entrada no válidos"
     *     )
     * )
     */
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
            'sub'   => $user->id,
            'usuario_id'   => $user->id,
            'rol'   => $user->id_rol ?? null,
            'email' => $user->correo,
        ]);

        return RespuestaAPI::exito('Inicio de sesión exitoso', [
            'access_token' => $token,
            'token_type'  => 'Bearer',
            'user' => [
                'id' => $user->id,
                'nombre' => $user->nombre,
                'apellido_paterno' => $user->apellido_paterno,
                'apellido_materno' => $user->apellido_materno,
                'correo' => $user->correo,
                'id_rol' => $user->id_rol,
                'fecha_registro' => $user->fecha_registro,
                'ultimo_acceso' => $user->ultimo_acceso,
                'rol' => $user->rol
            ]
        ]);
    }
}