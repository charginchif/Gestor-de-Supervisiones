<?php
namespace App\Http\Middleware;

use App\Models\User;
use App\Services\JwtService;
use App\Utils\RespuestaAPI;
use Closure;
use Illuminate\Support\Facades\Auth;

class JwtMiddleware
{
    private JwtService $jwt;

    public function __construct(JwtService $jwt)
    {
        $this->jwt = $jwt;
    }

    public function handle($request, Closure $next)
    {
        $auth = $request->header('Authorization');
        if (!$auth || stripos($auth, 'Bearer ') !== 0) {
            return RespuestaAPI::error('Token no enviado', RespuestaAPI::HTTP_NO_AUTORIZADO);
        }

        try {
            $token = trim(substr($auth, 7));
            $claims = $this->jwt->verificarToken($token);

            if (empty($claims['sub'])) {
                return RespuestaAPI::error('Token inválido (sin subject)', RespuestaAPI::HTTP_NO_AUTORIZADO);
            }

            $user = User::find($claims['sub']);

            if (!$user) {
                return RespuestaAPI::error('Usuario no encontrado', RespuestaAPI::HTTP_NO_AUTORIZADO);
            }

            Auth::setUser($user);

            $request->attributes->set('jwt_claims', $claims);
        } catch (\Throwable $e) {
            return RespuestaAPI::error('Token inválido o expirado', RespuestaAPI::HTTP_NO_AUTORIZADO);
        }

        return $next($request);
    }
}