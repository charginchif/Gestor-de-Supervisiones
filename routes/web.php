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

    // Rutas para la gestión de carreras
    $router->get('carreras', 'CarreraController@index');
    $router->post('carreras', 'CarreraController@store');
    $router->get('carreras/{id}', 'CarreraController@show');
    $router->put('carreras/{id}', 'CarreraController@update');
    $router->delete('carreras/{id}', 'CarreraController@destroy');

    // Rutas para la gestión de materias
    $router->get('materias', 'MateriaController@index');
    $router->post('materias', 'MateriaController@store');
    $router->get('materias/{id}', 'MateriaController@show');
    $router->put('materias/{id}', 'MateriaController@update');
    $router->delete('materias/{id}', 'MateriaController@destroy');

    // Rutas para la asignación de carreras a coordinadores
    $router->get('carrerasPorCoordinador/{id}', 'CarreraController@getCarrerasPorCoordinador');
    $router->get('carrerasPorCoordinador', 'CarreraController@getAllAsignaciones');
    $router->post('asignarCarreraCoordinador', 'CarreraController@asignarCarreraCoordinador');
    $router->put('asignarCarreraCoordinador', 'CarreraController@actualizarCarreraCoordinador');
    $router->delete('asignarCarreraCoordinador', 'CarreraController@eliminarCarreraCoordinador');

    // Rutas para la asignación de carreras a planteles
    $router->post('asignarCarreraPlantel', 'CarreraController@asignarCarreraPlantel');
    $router->delete('eliminarCarreraPlantel', 'CarreraController@eliminarCarreraPlantel');
    $router->get('carrerasPorPlantel', 'CarreraController@getAllCarrerasPorPlantel');
    $router->get('carrerasPorPlantel/{id}', 'CarreraController@getCarrerasPorPlantel');

    // Rutas para la asignación de turnos a planteles
    $router->post('plantel-turno', 'CarreraController@asignarTurnoPlantel');
    $router->delete('plantel-turno/{id}', 'CarreraController@eliminarTurnoPlantel');
    $router->put('plantel-turno/{id}', 'CarreraController@actualizarTurnoPlantel');

    // Rutas para la gestión de criterios de supervisión
    $router->get('supervision/contable', 'SupervisionController@indexContable');
    $router->get('supervision/contable/{id}', 'SupervisionController@showContable');
    $router->post('supervision/contable', 'SupervisionController@storeContable');
    $router->put('supervision/contable/{id}', 'SupervisionController@updateContable');
    $router->delete('supervision/contable/{id}', 'SupervisionController@destroyContable');

    $router->get('supervision/no-contable', 'SupervisionController@indexNoContable');
    $router->get('supervision/no-contable/{id}', 'SupervisionController@showNoContable');
    $router->post('supervision/no-contable', 'SupervisionController@storeNoContable');
    $router->put('supervision/no-contable/{id}', 'SupervisionController@updateNoContable');
    $router->delete('supervision/no-contable/{id}', 'SupervisionController@destroyNoContable');

    //Rubros para criterios de supervision
    $router->get('supervision/rubros/contable', 'RubroController@indexContable');
    $router->post('supervision/rubros/contable', 'RubroController@storeContable');
    $router->get('supervision/rubros/contable/{id}', 'RubroController@showContable');
    $router->put('supervision/rubros/contable/{id}', 'RubroController@updateContable');
    $router->delete('supervision/rubros/contable/{id}', 'RubroController@destroyContable');
    $router->get('supervision/rubros/no-contable', 'RubroController@indexNoContable');
    $router->post('supervision/rubros/no-contable', 'RubroController@storeNoContable');
    $router->get('supervision/rubros/no-contable/{id}', 'RubroController@showNoContable');
    $router->put('supervision/rubros/no-contable/{id}', 'RubroController@updateNoContable');
    $router->delete('supervision/rubros/no-contable/{id}', 'RubroController@destroyNoContable');

    // Rutas para la gestión de plan de estudios
    $router->get('plan-estudio', 'PlanEstudioController@indexAll');
    $router->get('plan-estudio/{id_carrera}', 'PlanEstudioController@index');
    $router->post('plan-estudio', 'PlanEstudioController@store');
    $router->put('plan-estudio', 'PlanEstudioController@update');
    $router->delete('plan-estudio', 'PlanEstudioController@destroy');

    //Criterios de evaluacion docente
    $router->get('rubros', 'RubroController@index');
    $router->post('rubros', 'RubroController@store');
    $router->get('rubros/{id}', 'RubroController@show');
    $router->put('rubros/{id}', 'RubroController@update');
    $router->delete('rubros/{id}', 'RubroController@destroy');
    $router->get('criterios-evaluacion', 'CriterioEvaluacionController@index');
    $router->post('criterios-evaluacion', 'CriterioEvaluacionController@store');
    $router->get('criterios-evaluacion/{id}', 'CriterioEvaluacionController@show');
    $router->put('criterios-evaluacion/{id}', 'CriterioEvaluacionController@update');
    $router->delete('criterios-evaluacion/{id}', 'CriterioEvaluacionController@destroy');

    
});

// Rutas para Coordinador
$router->group(['middleware' => ['auth.jwt', 'role:coordinador']], function () use ($router) {
    // Rutas para la gestión de alumnos (para Coordinador)
    $router->get('coordinador-alumnos', 'UsuarioController@indexAlumnos');
    $router->post('coordinador-alumnos', 'UsuarioController@storeAlumno');
    $router->get('coordinador-alumnos/{id}', 'UsuarioController@showAlumno');
    $router->put('coordinador-alumnos/{id}', 'UsuarioController@updateAlumno');

    // Rutas para la gestión de docentes (para Coordinador)
    $router->get('coordinador-docentes', 'UsuarioController@indexDocentes');
    $router->post('coordinador-docentes', 'UsuarioController@storeDocente');
    $router->get('coordinador-docentes/{id}', 'UsuarioController@showDocente');
    $router->put('coordinador-docentes/{id}', 'UsuarioController@updateDocente');

    // Rutas para la gestión de planteles (para Coordinador)
    $router->get('coordinador-planteles', 'PlantelController@indexCoordinadorPlanteles');

});

// Rutas para Alumno
$router->group(['middleware' => ['auth.jwt', 'role:alumno']], function () use ($router) {
    $router->get('mis-docentes', 'AlumnoDocenteController@index');
    $router->post('evaluar-docente', 'AlumnoDocenteController@evaluar');
    $router->post('inscribir-grupo', 'AlumnoDocenteController@inscribirGrupo');
    $router->get('mi-horario', 'HorarioController@getMiHorario');
});

//Soy un comentario


