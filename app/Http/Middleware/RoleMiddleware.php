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
       

        if (!$claims || !isset($claims['sub'])) {
            error_log('RoleMiddleware: No claims or sub not set.');
            return RespuestaAPI::error('No autorizado para acceder a este recurso', RespuestaAPI::HTTP_NO_AUTORIZADO);
        }

        $usuario = User::find($claims['sub']);

        if (!$usuario) {
            error_log('RoleMiddleware: User not found in DB.');
            return RespuestaAPI::error('Usuario no encontrado', RespuestaAPI::HTTP_NO_AUTORIZADO);
        }

        $rol = CatRol::where('nombre', $rolRequerido)->first();

        if (!$rol) {
            error_log('RoleMiddleware: Role not found in DB: ' . $rolRequerido);
            return RespuestaAPI::error('Rol no válido', RespuestaAPI::HTTP_PROHIBIDO);
        }

        $requiredRoleId = $rol->id;

        error_log('User ID: ' . $usuario->id . ', User Role ID: ' . $usuario->id_rol . ', Required Role ID: ' . ($requiredRoleId ?? 'null'));

        // Check if the user's role ID matches the required role ID
        if ($usuario->id_rol != $requiredRoleId) {
            error_log('RoleMiddleware: Role check failed. User ID: ' . $usuario->id . ', User Role ID: ' . $usuario->id_rol . ', Required Role ID: ' . ($requiredRoleId ?? 'null'));
            return RespuestaAPI::error('No tienes permisos para acceder a este recurso', RespuestaAPI::HTTP_PROHIBIDO);
        }

        return $next($request);
    }

}
