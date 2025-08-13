<?php

/** @var \Laravel\Lumen\Routing\Router $router */

// Ruta base para comprobar que la API funciona
$router->get('/', function () use ($router) {
    return $router->app->version();
});

// Rutas para el controlador UsuarioController
$router->group(['prefix' => 'usuario'], function () use ($router) {
    $router->get('/', 'UsuarioController@listar'); // Listar todos los usuarios
    $router->get('{id}', 'UsuarioController@ver'); // Ver un usuario por ID

    $router->put('{id}', [
        'middleware' => 'auth',
        'uses' => 'UsuarioController@actualizar'
    ]);

    $router->delete('{id}', [
        'middleware' => 'auth',
        'uses' => 'UsuarioController@eliminar'
    ]);
});



?>

