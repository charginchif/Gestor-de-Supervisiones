<?php
namespace App\Http\Middleware;

use Closure;
use App\Models\CatRol;
use App\Utils\RespuestaAPI;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, $rolRequerido)
    {
        $usuario = Auth::user(); // Obtener usuario ya autenticado por JwtMiddleware

        if (!$usuario) {
            // Este caso no debería ocurrir si JwtMiddleware se ejecuta primero
            return RespuestaAPI::error('Usuario no autenticado', RespuestaAPI::HTTP_NO_AUTORIZADO);
        }

        $rol = CatRol::where('nombre', $rolRequerido)->first();

        if (!$rol) {
            return RespuestaAPI::error('Rol no válido', RespuestaAPI::HTTP_PROHIBIDO);
        }

        // Compara el id_rol del usuario con el id del rol requerido
        if ($usuario->id_rol === $rol->id) {
            return $next($request);
        } else {
            return RespuestaAPI::error('No tienes permisos para acceder a este recurso', RespuestaAPI::HTTP_PROHIBIDO);
        }
    }

}