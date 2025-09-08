<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use Illuminate\Support\Facades\Hash;
use App\Models\User;

// This is a temporary route for creating a test user.
// You can access it at /create-test-user from your project's public folder.


$router->get('/', function () use ($router) {
    return $router->app->version();
});



// API Route Group
$router->group(['prefix' => 'api'], function () use ($router) {
    // Public route for login
    $router->post('login', 'AuthController@iniciarSesion');
   

        // Rutas para Administrador
    $router->group(['middleware' => ['auth.jwt', 'role:administrador']], function () use ($router) {
        // Rutas para la gestión de usuarios
        $router->get('usuario', 'UsuarioController@index');
        $router->post('usuario', 'UsuarioController@store');
        $router->get('usuario/{id}', 'UsuarioController@show');
        $router->put('usuario/{id}', 'UsuarioController@update');
        $router->delete('usuario/{id}', 'UsuarioController@destroy');

        // Rutas para la gestión de planteles
        $router->get('planteles', 'PlantelController@index');
        $router->post('planteles', 'PlantelController@store');
        $router->get('planteles/{id}', 'PlantelController@show');
        $router->put('planteles/{id}', 'PlantelController@update');
        $router->delete('planteles/{id}', 'PlantelController@destroy');

        // Rutas para la gestión de alumnos
        $router->get('alumnos', 'UsuarioController@indexAlumnos');
        $router->post('alumnos', 'UsuarioController@storeAlumno');
        $router->get('alumnos/{id}', 'UsuarioController@showAlumno');
        $router->put('alumnos/{id}', 'UsuarioController@updateAlumno');

        // Rutas para la gestión de docentes
        $router->get('docentes', 'UsuarioController@indexDocentes');
        $router->post('docentes', 'UsuarioController@storeDocente');
        $router->get('docentes/{id}', 'UsuarioController@showDocente');
        $router->put('docentes/{id}', 'UsuarioController@updateDocente');

        // Rutas para la gestión de coordinadores
        $router->get('coordinadores', 'CoordinadorController@index');
        $router->post('coordinadores', 'CoordinadorController@store');
        $router->get('coordinadores/{id}', 'CoordinadorController@show');
        $router->put('coordinadores/{id}', 'CoordinadorController@update');

    });

    

        // Rutas para Coordinador
    $router->group(['middleware' => ['auth.jwt', 'role:coordinador']], function () use ($router) {
              // Rutas para la gestión de alumnos (para Coordinador)
            $router->get('alumnos', 'UsuarioController@indexAlumnos');
            $router->post('alumnos', 'UsuarioController@storeAlumno');
            $router->get('alumnos/{id}', 'UsuarioController@showAlumno');
            $router->put('alumnos/{id}', 'UsuarioController@updateAlumno');

            // Rutas para la gestión de docentes (para Coordinador)
            $router->get('docentes', 'UsuarioController@indexDocentes');
            $router->post('docentes', 'UsuarioController@storeDocente');
            $router->get('docentes/{id}', 'UsuarioController@showDocente');
            $router->put('docentes/{id}', 'UsuarioController@updateDocente');

            // Rutas para la gestión de planteles (para Coordinador)
            $router->get('planteles', 'PlantelController@indexCoordinadorPlanteles');
    });
});