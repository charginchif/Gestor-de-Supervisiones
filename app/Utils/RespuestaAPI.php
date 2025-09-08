<?php

namespace App\Utils;

class RespuestaAPI
{
    public const HTTP_OK                  = 200;
    public const HTTP_CREADO              = 201;
    public const HTTP_NO_CONTENIDO        = 204;
    public const HTTP_SOLICITUD_INCORRECTA= 400;
    public const HTTP_NO_AUTORIZADO       = 401;
    public const HTTP_PROHIBIDO           = 403;
    public const HTTP_NO_ENCONTRADO       = 404;
    public const HTTP_ERROR_VALIDACION    = 422;
    public const HTTP_ERROR_INTERNO       = 500;

    public static function enviar($exito, $mensaje, $datos = null, $httpCode = self::HTTP_OK)
    {
        return response()->json([
            'exito'   => $exito,
            'codigo'  => $httpCode,
            'mensaje' => $mensaje,
            'datos'   => $datos
        ], $httpCode);
    }

    public static function exito($mensaje, $datos = null, $httpCode = self::HTTP_OK)
    {
        return self::enviar(true, $mensaje, $datos, $httpCode);
    }

    public static function error($mensaje, $httpCode = self::HTTP_ERROR_INTERNO, $datos = null)
    {
        return self::enviar(false, $mensaje, $datos, $httpCode);
    }
}
