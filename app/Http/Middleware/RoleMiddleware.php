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
        error_log('RoleMiddleware: Claims: ' . json_encode($claims));
        if (!$claims || !isset($claims['sub'])) {
            error_log('RoleMiddleware: No claims or sub not set.');
            return RespuestaAPI::error('No autorizado para acceder a este recurso', RespuestaAPI::HTTP_NO_AUTORIZADO);
        }

        $usuario = User::find($claims['sub']);
        error_log('RoleMiddleware: User ID from claims: ' . $claims['sub']);
        error_log('RoleMiddleware: User found: ' . ($usuario ? $usuario->id : 'null'));

        if (!$usuario) {
            error_log('RoleMiddleware: User not found in DB.');
            return RespuestaAPI::error('Usuario no encontrado', RespuestaAPI::HTTP_NO_AUTORIZADO);
        }

        // Map role names to role IDs
        $roleMap = [
            'administrador' => 1,
            'coordinador' => 3, 
            'alumno' => 4, 
            'docente' => 2, 
        ];

        // Find the required role ID from the map
        $requiredRoleId = $roleMap[$rolRequerido] ?? null;
        error_log('RoleMiddleware: Required Role String: ' . $rolRequerido);
        error_log('RoleMiddleware: Required Role ID: ' . ($requiredRoleId ?? 'null'));
        error_log('RoleMiddleware: User\'s Role ID: ' . $usuario->id_rol);


        // Check if the user's role ID matches the required role ID
        if (!$requiredRoleId || $usuario->id_rol != $requiredRoleId) {
            error_log('RoleMiddleware: Role check failed. User ID: ' . $usuario->id . ', User Role ID: ' . $usuario->id_rol . ', Required Role ID: ' . ($requiredRoleId ?? 'null'));
            return RespuestaAPI::error('No tienes permisos para acceder a este recurso', RespuestaAPI::HTTP_PROHIBIDO);
        }

        return $next($request);
    }
}
