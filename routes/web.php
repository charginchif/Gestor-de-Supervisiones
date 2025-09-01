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
        $router->delete('alumnos/{id}', 'UsuarioController@destroyAlumno');

        // Rutas para la gestión de docentes
        $router->get('docentes', 'UsuarioController@indexDocentes');
        $router->post('docentes', 'UsuarioController@storeDocente');
        $router->get('docentes/{id}', 'UsuarioController@showDocente');
        $router->put('docentes/{id}', 'UsuarioController@updateDocente');
        $router->delete('docentes/{id}', 'UsuarioController@destroyDocente');

        // Rutas para la gestión de coordinadores
        $router->get('coordinadores', 'CoordinadorController@index');
        $router->post('coordinadores', 'CoordinadorController@store');
        $router->get('coordinadores/{id}', 'CoordinadorController@show');
        $router->put('coordinadores/{id}', 'CoordinadorController@update');
        $router->delete('coordinadores/{id}', 'CoordinadorController@destroy');

    });

    

        // Rutas para Coordinador
    $router->group(['middleware' => ['auth.jwt', 'role:coordinador']], function () use ($router) {
        $router->get('carreras', 'CarreraController@index');
        $router->get('carreras/{id}', 'CarreraController@show');
        $router->post('carreras', 'CarreraController@store');
        $router->put('carreras/{id}', 'CarreraController@update');
        $router->delete('carreras/{id}', 'CarreraController@destroy');

        // Rutas para la gestión de horarios
        $router->get('horarios', 'HorarioController@index');
        $router->post('horarios', 'HorarioController@store');
        $router->get('horarios/{id}', 'HorarioController@show');
        $router->put('horarios/{id}', 'HorarioController@update');
        $router->delete('horarios/{id}', 'HorarioController@destroy');

        // Coordinador Docente
        $router->get('coordinador-docente', 'CoordinadorDocenteController@index');
        $router->get('coordinador-docente/{id}', 'CoordinadorDocenteController@show');
        $router->post('coordinador-docente', 'CoordinadorDocenteController@store');
        $router->put('coordinador-docente/{id}', 'CoordinadorDocenteController@update');
        $router->delete('coordinador-docente/{id}', 'CoordinadorDocenteController@destroy');

        // Coordinador Evaluacion
        $router->get('coordinador-evaluacion', 'CoordinadorEvaluacionController@index');
        $router->get('coordinador-evaluacion/{id}', 'CoordinadorEvaluacionController@show');
        $router->post('coordinador-evaluacion', 'CoordinadorEvaluacionController@store');
        $router->put('coordinador-evaluacion/{id}', 'CoordinadorEvaluacionController@update');
        $router->delete('coordinador-evaluacion/{id}', 'CoordinadorEvaluacionController@destroy');

        // Coordinador Supervision
        $router->get('coordinador-supervicion', 'CoordinadorSupervicionController@index');
        $router->get('coordinador-supervicion/{id}', 'CoordinadorSupervicionController@show');
        $router->post('coordinador-supervicion', 'CoordinadorSupervicionController@store');
        $router->put('coordinador-supervicion/{id}', 'CoordinadorSupervicionController@update');
        $router->delete('coordinador-supervicion/{id}', 'CoordinadorSupervicionController@destroy');

        // Coordinador Grupo
        $router->get('coordinador-grupo', 'CoordinadorGrupoController@index');
        $router->get('coordinador-grupo/{id}', 'CoordinadorGrupoController@show');
        $router->post('coordinador-grupo', 'CoordinadorGrupoController@store');
        $router->put('coordinador-grupo/{id}', 'CoordinadorGrupoController@update');
        $router->delete('coordinador-grupo/{id}', 'CoordinadorGrupoController@destroy');

        // Coordinador Agenda
        $router->get('coordinador-agenda', 'CoordinadorAgendaController@index');
        $router->get('coordinador-agenda/{id}', 'CoordinadorAgendaController@show');
        $router->post('coordinador-agenda', 'CoordinadorAgendaController@store');
        $router->put('coordinador-agenda/{id}', 'CoordinadorAgendaController@update');
        $router->delete('coordinador-agenda/{id}', 'CoordinadorAgendaController@destroy');
    });
});