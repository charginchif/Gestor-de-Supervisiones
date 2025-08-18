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
$router->post('create-test-users', function () {
    
    $usuarios = [
        [
            'nombre' => 'Test',
            'apellido_paterno' => 'User',
            'apellido_materno' => 'Test',
            'correo' => 'test10@example.com',
            'contrasena' => Hash::make('password123'),
            'id_rol' => 4 // Assumes role 1 exists
        ],
        [
            'nombre' => 'Test2',
            'apellido_paterno' => 'User2',
            'apellido_materno' => 'Test2',
            'correo' => 'test2@example.com',
            'contrasena' => Hash::make('password123'),
            'id_rol' => 4 // Assumes role 1 exists
        ],
        [
            'nombre' => 'Test3',
            'apellido_paterno' => 'User3',
            'apellido_materno' => 'Test3',
            'correo' => 'test3@example.com',
            'contrasena' => Hash::make('password123'),
            'id_rol' => 4 // Assumes role 4 exists
        ]
    ];

    foreach ($usuarios as $usuario) {
        User::create($usuario);
    }
});


$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->delete('all', function () {
    $id = [1, 2, 3, 4, 5];

    foreach ($id as $id) {
        $user = User::find($id);
        if ($user) {
            $user->delete();
        }
    }
});

// API Route Group
$router->group(['prefix' => 'api'], function () use ($router) {
    // Public route for login
    $router->post('login', 'AuthController@iniciarSesion');
    $router->get('all', 'UsuarioController@index');

    // Protected routes that require JWT authentication
    $router->group(['middleware' => 'auth.jwt'], function () use ($router) {
        // Routes for user management
        $router->get('usuarios', 'UsuarioController@index');
        $router->post('usuarios', 'UsuarioController@store');
        $router->get('usuarios/{id}', 'UsuarioController@show');
        $router->put('usuarios/{id}', 'UsuarioController@update');
        $router->delete('usuarios/{id}', 'UsuarioController@destroy');
    });
});