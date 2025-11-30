<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Documentacion de la API",
 *      description="Esta es la documentación de la API del sistema",
 *      @OA\Contact(
 *          email="admin@example.com"
 *      )
 * )
 * @OA\SecurityScheme(
 *      securityScheme="jwt",
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT",
 * )
 * @OA\Security(
 *      security={"jwt": {}}
 * )
 */
class Controller extends BaseController
{
    //
}
