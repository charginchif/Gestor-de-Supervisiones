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

    // Rutas para la gestión de carrera-modalidad
    $router->get('carrera-modalidad', 'CarreraController@indexCarreraModalidad');
    $router->post('carrera-modalidad', 'CarreraController@storeCarreraModalidad');
    $router->delete('carrera-modalidad', 'CarreraController@destroyCarreraModalidad');

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
    $router->get('asignarCarreraCoordinador', 'CarreraController@getAllAsignaciones');

    // Rutas para la asignación de carreras a planteles
    $router->post('asignarCarreraPlantel', 'CarreraController@asignarCarreraPlantel');
    $router->delete('eliminarCarreraPlantel', 'CarreraController@eliminarCarreraPlantel');
    $router->get('carrerasPorPlantel', 'CarreraController@getAllCarrerasPorPlantel');
    $router->get('carrerasPorPlantel/{id}', 'CarreraController@getCarrerasPorPlantel');

    // Rutas para la asignación de turnos a planteles
    $router->post('plantel-turno', 'CarreraController@asignarTurnoPlantel');
    $router->delete('plantel-turno/{id}', 'CarreraController@eliminarTurnoPlantel');
    $router->put('plantel-turno/{id}', 'CarreraController@actualizarTurnoPlantel');

    // Rutas para la gestión de Criterios de Supervisión
    $router->group(['prefix' => 'supervision'], function () use ($router) {
        // Criterios Contables
        $router->group(['prefix' => 'contable'], function () use ($router) {
            $router->get('/', 'SupervisionController@indexContable');
            $router->post('/', 'SupervisionController@storeContable');
            $router->get('buscar', 'SupervisionController@buscarContable');
            $router->get('{id}', 'SupervisionController@showContable');
            $router->put('{id}', 'SupervisionController@updateContable');
            $router->delete('{id}', 'SupervisionController@destroyContable');
        });

        // Criterios No Contables
        $router->group(['prefix' => 'no-contable'], function () use ($router) {
            $router->get('/', 'SupervisionController@indexNoContable');
            $router->post('/', 'SupervisionController@storeNoContable');
            $router->get('{id}', 'SupervisionController@showNoContable');
            $router->put('{id}', 'SupervisionController@updateNoContable');
            $router->delete('{id}', 'SupervisionController@destroyNoContable');
        });
        
        // Rubros de Supervisión
        $router->group(['prefix' => 'rubros'], function () use ($router) {
            $router->get('/', 'SupervisionController@listarRubrosContablesNoContables');
            
            // Rubros Contables
            $router->group(['prefix' => 'contable'], function () use ($router) {
                $router->get('/', 'RubroController@indexContable');
                $router->post('/', 'RubroController@storeContable');
                $router->get('{id}', 'RubroController@showContable');
                $router->put('{id}', 'RubroController@updateContable');
                $router->delete('{id}', 'RubroController@destroyContable');
            });

            // Rubros No Contables
            $router->group(['prefix' => 'no-contable'], function () use ($router) {
                $router->get('/', 'RubroController@indexNoContable');
                $router->post('/', 'RubroController@storeNoContable');
                $router->get('{id}', 'RubroController@showNoContable');
                $router->put('{id}', 'RubroController@updateNoContable');
                $router->delete('{id}', 'RubroController@destroyNoContable');
            });
        });
    });

    // Rutas para la gestión de Plan de Estudios
    $router->get('plan-estudio', 'PlanEstudioController@indexAll');
    $router->get('plan-estudio/{id_carrera}', 'PlanEstudioController@index');
    $router->post('plan-estudio', 'PlanEstudioController@store');
    $router->put('plan-estudio', 'PlanEstudioController@update');
    $router->delete('plan-estudio/{id_plan_estudio}', 'PlanEstudioController@destroy');
    $router->delete('plan-estudio/materia', 'PlanEstudioController@destroyMateria');

    // Rutas para la gestión de Evaluación Docente
    $router->group(['prefix' => 'evaluacion-docente'], function () use ($router) {
        // Rubros de Evaluación
        $router->group(['prefix' => 'rubros'], function () use ($router) {
            $router->get('/', 'RubroController@index');
            $router->post('/', 'RubroController@store');
            $router->get('{id}', 'RubroController@show');
            $router->put('{id}', 'RubroController@update');
            $router->delete('{id}', 'RubroController@destroy');
        });

        // Criterios de Evaluación
        $router->group(['prefix' => 'criterios'], function () use ($router) {
            $router->get('/', 'CriterioEvaluacionController@index');
            $router->post('/', 'CriterioEvaluacionController@store');
            $router->get('{id}', 'CriterioEvaluacionController@show');
            $router->put('{id}', 'CriterioEvaluacionController@update');
            $router->delete('{id}', 'CriterioEvaluacionController@destroy');
        });
    });

    // Rutas para la gestión de modalidades
    $router->get('modalidades', 'ModalidadController@list');
    $router->post('modalidades', 'ModalidadController@create');
    $router->get('modalidades/{id}', 'ModalidadController@get');
    $router->put('modalidades/{id}', 'ModalidadController@update');
    $router->delete('modalidades/{id}', 'ModalidadController@delete');
    $router->post('modalidades/bulk-upsert', 'ModalidadController@bulkUpsert');

    // Rutas para la gestión de grupos
    $router->get('grupos', 'GrupoController@indexAdmin');
    $router->post('grupos', 'GrupoController@store');
    $router->get('grupos/{id}', 'GrupoController@show');
    $router->put('grupos/{id}', 'GrupoController@update');
    $router->delete('grupos/{id}', 'GrupoController@destroy');
    $router->post('grupos/asignar-plan', 'GrupoController@asignarPlan');
    $router->delete('grupos/{id_grupo}/quitar-plan', 'GrupoController@quitarPlan');
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

    $router->post('materias/asignar-docente', 'MateriaController@asignarDocente');

    // listar las carreras asignadas al coordinador autenticado

    // Rutas para la gestión de grupos (para Coordinador)
    $router->get('coordinador-grupos', 'GrupoController@index');
    $router->post('coordinador-grupos', 'GrupoController@store');
    $router->get('coordinador-grupos/{id}', 'GrupoController@show');
    $router->put('coordinador-grupos/{id}', 'GrupoController@update');
    $router->delete('coordinador-grupos/{id}', 'GrupoController@destroy');
    $router->post('coordinador-grupos/asignar-plan', 'GrupoController@asignarPlan');
    $router->delete('coordinador-grupos/{id_grupo}/quitar-plan', 'GrupoController@quitarPlan');
});

// Rutas para Alumno
$router->group(['middleware' => ['auth.jwt', 'role:alumno']], function () use ($router) {
    $router->get('mis-docentes', 'AlumnoDocenteController@index');
    $router->post('evaluar-docente', 'AlumnoDocenteController@evaluar');
    $router->post('inscribir-grupo', 'AlumnoDocenteController@inscribirGrupo');
    $router->get('mi-horario', 'HorarioController@getMiHorario');
});

// Rutas para Docente
$router->group(['middleware' => ['auth.jwt', 'role:docente']], function () use ($router) {
    $router->get('perfil/docente', 'UsuarioController@getMiPerfilDocente');
});

//Soy un comentario