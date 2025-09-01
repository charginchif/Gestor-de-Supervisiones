<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use App\Utils\RespuestaAPI;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // Obtener el token válido desde la variable de entorno
        $validToken = env('AUTH_TOKEN');
        $token = $request->header('Authorization');

        // El token debe ser enviado como: Authorization: Bearer token123
        if ($token !== 'Bearer ' . $validToken) {
            return RespuestaAPI::error('No autorizado', RespuestaAPI::HTTP_NO_AUTORIZADO);
        }

        return $next($request);
    }
}