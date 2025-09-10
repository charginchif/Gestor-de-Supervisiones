<?php
namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use App\Models\CatRol;
use App\Utils\RespuestaAPI;

class RoleMiddleware
{
    public function handle($request, Closure $next, $rolRequerido)
    {
        $claims = $request->attributes->get('jwt_claims');

        if (!$claims || !isset($claims['usuario_id'])) {
            return RespuestaAPI::error('No autorizado para acceder a este recurso', RespuestaAPI::HTTP_NO_AUTORIZADO);
        }

        $usuario = User::find($claims['usuario_id']);

        if (!$usuario) {
            return RespuestaAPI::error('Usuario no encontrado', RespuestaAPI::HTTP_NO_AUTORIZADO);
        }

        $rol = CatRol::where('nombre', $rolRequerido)->first();

        if (!$rol) {
            return RespuestaAPI::error('Rol no válido', RespuestaAPI::HTTP_PROHIBIDO);
        }

        $requiredRoleId = $rol->id;

        // Check if the user's role ID matches the required role ID
        if ($usuario->id_rol === $requiredRoleId) {
            return $next($request);
        } else {
            return RespuestaAPI::error('No tienes permisos para acceder a este recurso', RespuestaAPI::HTTP_PROHIBIDO);
        }
    }

}
