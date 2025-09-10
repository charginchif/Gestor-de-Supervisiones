<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtService
{
    private string $secret;
    private int $ttl;
    private string $iss;
    private string $aud;

    public function __construct()
    {
        $this->secret = env('JWT_SECRET', 'secret');
        $this->ttl    = (int) env('JWT_TTL', 3600);
        $this->iss    = env('JWT_ISS', 'App');
        $this->aud    = env('JWT_AUD', 'AppClient');
    }

    public function crearToken(array $claims = []): string
    {
        $now = time();
        $payload = array_merge([
            'iss' => $this->iss,
            'aud' => $this->aud,
            'iat' => $now,
            'nbf' => $now,
            // 'exp' => $now + $this->ttl, // Comentado para generar tokens estáticos para pruebas
        ], $claims);

        return JWT::encode($payload, $this->secret, 'HS256');
    }

    public function verificarToken(string $jwt): array
    {
        $decoded = JWT::decode($jwt, new Key($this->secret, 'HS256'));
        return json_decode(json_encode($decoded), true); // a array
    }
}
