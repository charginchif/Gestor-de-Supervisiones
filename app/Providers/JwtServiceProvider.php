<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\JwtService;

class JwtServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Registra un singleton en el contenedor de Illuminate
        $this->app->singleton(JwtService::class, function ($app) {
            return new JwtService();
        });

        // Alias opcional por string (útil para Facade)
        $this->app->alias(JwtService::class, 'jwt');
    }

    public function boot(): void
    {
        $this->app['auth']->viaRequest('jwt', function ($request) {
            $token = $request->bearerToken();

            if ($token) {
                try {
                    $jwtService = app(JwtService::class);
                    $claims = $jwtService->verificarToken($token);
                    return \App\Models\User::find($claims['sub']);
                } catch (\Exception $e) {
                    // Token is invalid or expired
                    return null;
                }
            }
            return null;
        });
    }
}
