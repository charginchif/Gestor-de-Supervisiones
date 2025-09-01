<?php
namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use App\Utils\RespuestaAPI;

class RoleMiddleware
{
    public function handle($request, Closure $next, $rolRequerido)
    {
        $claims = $request->attributes->get('jwt_claims');
        if (!$claims || !isset($claims['sub'])) {
            return RespuestaAPI::error('No autorizado para acceder a este recurso', RespuestaAPI::HTTP_NO_AUTORIZADO);
        }

        $usuario = User::find($claims['sub']);

        if (!$usuario || !$usuario->rol) {
            return RespuestaAPI::error('Usuario o rol no encontrado', RespuestaAPI::HTTP_NO_AUTORIZADO);
        }

        if (strtolower($usuario->rol) !== strtolower($rolRequerido)) {
            return RespuestaAPI::error('No tienes permisos para acceder a este recurso', RespuestaAPI::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
