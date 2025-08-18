<?php
namespace App\Http\Middleware;

use Closure;
use App\Services\JwtService;
use App\Utils\RespuestaAPI;

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
            $request->attributes->set('jwt_claims', $claims);
        } catch (\Throwable $e) {
            return RespuestaAPI::error('Token inválido o expirado', RespuestaAPI::HTTP_NO_AUTORIZADO);
        }

        return $next($request);
    }
}
