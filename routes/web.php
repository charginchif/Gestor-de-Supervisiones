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

//==========================================================================
// RUTAS PÚBLICAS
//==========================================================================

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('login', 'AuthController@iniciarSesion');


//==========================================================================
// RUTAS PARA ADMINISTRADOR
//==========================================================================

$router->group(['middleware' => ['auth.jwt', 'role:administrador']], function () use ($router) {
    // Gestión de usuarios
    $router->get('usuario', 'UsuarioController@index');
    $router->post('usuario', 'UsuarioController@store');
    $router->get('usuario/{id}', 'UsuarioController@show');
    $router->put('usuario/{id}', 'UsuarioController@update');
    $router->delete('usuario/{id}', 'UsuarioController@destroy');

    // Gestión de planteles
    $router->get('planteles', 'PlantelController@index');
    $router->post('planteles', 'PlantelController@store');
    $router->get('planteles/{id}', 'PlantelController@show');
    $router->put('planteles/{id}', 'PlantelController@update');
    $router->delete('planteles/{id}', 'PlantelController@destroy');

    // Gestión de alumnos
    $router->get('alumnos', 'UsuarioController@indexAlumnos');  
    $router->post('alumnos', 'UsuarioController@storeAlumno');
    $router->get('alumnos/{id}', 'UsuarioController@showAlumno');
    $router->put('alumnos/{id}', 'UsuarioController@updateAlumno');

    // Gestión de docentes
    $router->get('docentes', 'UsuarioController@indexDocentes');
    $router->post('docentes', 'UsuarioController@storeDocente');
    $router->get('docentes/{id}', 'UsuarioController@showDocente');
    $router->put('docentes/{id}', 'UsuarioController@updateDocente');

    // Gestión de coordinadores
    $router->get('coordinadores', 'CoordinadorController@index');
    $router->post('coordinadores', 'CoordinadorController@store');
    $router->get('coordinadores/{id}', 'CoordinadorController@show');
    $router->put('coordinadores/{id}', 'CoordinadorController@update');

    // Gestión de carreras
    $router->get('carreras', 'CarreraController@index');
    $router->post('carreras', 'CarreraController@store');
    $router->get('carreras/{id}', 'CarreraController@show');    
    $router->put('carreras/{id}', 'CarreraController@update');
    $router->delete('carreras/{id}', 'CarreraController@destroy');

    // Gestión de carrera-modalidad
    $router->get('carrera-modalidad', 'CarreraController@indexCarreraModalidad');
    $router->post('carrera-modalidad', 'CarreraController@storeCarreraModalidad');
    $router->delete('carrera-modalidad', 'CarreraController@destroyCarreraModalidad');

    // Gestión de materias
    $router->get('materias', 'MateriaController@index');
    $router->post('materias', 'MateriaController@store');
    $router->get('materias/{id}', 'MateriaController@show');
    $router->put('materias/{id}', 'MateriaController@update');
    $router->delete('materias/{id}', 'MateriaController@destroy');

    // Asignación de carreras a coordinadores
    $router->get('carrerasPorCoordinador/{id}', 'CarreraController@getCarrerasPorCoordinador');
    $router->get('carrerasPorCoordinador', 'CarreraController@getAllAsignaciones');
    $router->post('asignarCarreraCoordinador', 'CarreraController@asignarCarreraCoordinador');
    $router->put('asignarCarreraCoordinador', 'CarreraController@actualizarCarreraCoordinador');
    $router->delete('asignarCarreraCoordinador', 'CarreraController@eliminarCarreraCoordinador');
    $router->get('asignarCarreraCoordinador', 'CarreraController@getAllAsignaciones');

    // Asignación de carreras a planteles
    $router->post('asignarCarreraPlantel', 'CarreraController@asignarCarreraPlantel');
    $router->delete('eliminarCarreraPlantel', 'CarreraController@eliminarCarreraPlantel');
    $router->get('carrerasPorPlantel', 'CarreraController@getAllCarrerasPorPlantel');
    $router->get('carrerasPorPlantel/{id}', 'CarreraController@getCarrerasPorPlantel');

    // Asignación de turnos a planteles
    $router->post('plantel-turno', 'CarreraController@asignarTurnoPlantel');
    $router->delete('plantel-turno/{id}', 'CarreraController@eliminarTurnoPlantel');
    $router->put('plantel-turno/{id}', 'CarreraController@actualizarTurnoPlantel');

    // Gestión de Criterios de Supervisión
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

    // Gestión de Plan de Estudios
    $router->get('plan-estudio', 'PlanEstudioController@indexAll');
    $router->get('plan-estudio/{id_carrera}', 'PlanEstudioController@index');
    $router->post('plan-estudio', 'PlanEstudioController@store');
    $router->put('plan-estudio/{id_plan_estudio}', 'PlanEstudioController@update');
    $router->delete('plan-estudio/materia', 'PlanEstudioController@destroyMateria');
    $router->delete('plan-estudio/{id_plan_estudio}', 'PlanEstudioController@destroy');

    // Gestión de Evaluación Docente
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

    // Gestión de modalidades
    $router->get('modalidades', 'ModalidadController@list');
    $router->post('modalidades', 'ModalidadController@create');
    $router->get('modalidades/{id}', 'ModalidadController@get');
    $router->put('modalidades/{id}', 'ModalidadController@update');
    $router->delete('modalidades/{id}', 'ModalidadController@delete');
    $router->post('modalidades/bulk-upsert', 'ModalidadController@bulkUpsert');

    // Gestión de grupos
    $router->get('grupos', 'GrupoController@indexAdmin');
    $router->post('grupos', 'GrupoController@store');
    $router->get('grupos/{id}', 'GrupoController@show');
    $router->put('grupos/{id}', 'GrupoController@update');
    $router->delete('grupos/{id}', 'GrupoController@destroy');
    $router->post('grupos/asignar-plan', 'GrupoController@asignarPlan');
    $router->delete('grupos/{id_grupo}/quitar-plan', 'GrupoController@quitarPlan');

    // Gestión de horarios
    $router->get('horarios', 'HorarioController@index');
    $router->post('horarios', 'HorarioController@store');
    $router->put('horarios/{id}', 'HorarioController@update');
    $router->delete('horarios/{id}', 'HorarioController@destroy');

    // Resultados
    $router->group(['prefix' => 'resultados'], function () use ($router) {
        $router->get('supervision', 'ResultadosController@getResultadosSupervision');
        $router->get('evaluacion', 'ResultadosController@getResultadosEvaluacion');
    });
});


//==========================================================================
// RUTAS PARA COORDINADOR
//==========================================================================

$router->group(['middleware' => ['auth.jwt', 'role:coordinador']], function () use ($router) {
    // Gestión de alumnos
    $router->get('coordinador-alumnos', 'UsuarioController@indexAlumnos');
    $router->post('coordinador-alumnos', 'UsuarioController@storeAlumno');
    $router->get('coordinador-alumnos/{id}', 'UsuarioController@showAlumno');
    $router->put('coordinador-alumnos/{id}', 'UsuarioController@updateAlumno');

    // Gestión de docentes
    $router->get('coordinador-docentes', 'UsuarioController@indexDocentes');
    $router->post('coordinador-docentes', 'UsuarioController@storeDocente');
    $router->get('coordinador-docentes/{id}', 'UsuarioController@showDocente');
    $router->put('coordinador-docentes/{id}', 'UsuarioController@updateDocente');

    // Gestión de planteles
    $router->get('coordinador-planteles', 'PlantelController@indexCoordinadorPlanteles');

    // Asignar docente a materia
    $router->post('materias/asignar-docente', 'MateriaController@asignarDocente');

    // Gestión de grupos
    $router->get('coordinador-grupos', 'GrupoController@index');
    $router->post('coordinador-grupos', 'GrupoController@store');
    $router->get('coordinador-grupos/{id}', 'GrupoController@show');
    $router->put('coordinador-grupos/{id}', 'GrupoController@update');
    $router->delete('coordinador-grupos/{id}', 'GrupoController@destroy');
    $router->post('coordinador-grupos/asignar-plan', 'GrupoController@asignarPlan');
    $router->delete('coordinador-grupos/{id_grupo}/quitar-plan', 'GrupoController@quitarPlan');

    // Gestión de carreras
    $router->get('coordinador-carreras', 'CarreraController@index');
    $router->post('coordinador-carreras', 'CarreraController@store');
    $router->get('coordinador-carreras/{id}', 'CarreraController@show');
    $router->put('coordinador-carreras/{id}', 'CarreraController@update');
    $router->delete('coordinador-carreras/{id}', 'CarreraController@destroy');

    // Gestión de agenda de supervisión
    $router->group(['prefix' => 'agenda-supervision'], function () use ($router) {
        $router->get('/', 'AgendaSupervisionController@index');
        $router->post('/', 'AgendaSupervisionController@store');
        $router->put('/{id}', 'AgendaSupervisionController@update');
        $router->delete('/{id}', 'AgendaSupervisionController@destroy');
    });

    // Gestión de Criterios de Supervisión
    $router->group(['prefix' => 'coordinador-supervision'], function () use ($router) {
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
    });

    // Gestión de Evaluación Docente
    $router->group(['prefix' => 'coordinador-evaluacion-docente'], function () use ($router) {
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

    // Gestión de solicitudes de inscripción
    $router->get('solicitud-inscripcion', 'SolicitudInscripcionController@index');
    $router->post('solicitud-inscripcion/{id}/aprobar', 'SolicitudInscripcionController@approve');
    $router->delete('solicitud-inscripcion/{id}/rechazar', 'SolicitudInscripcionController@reject');
    $router->get('solicitud-inscripcion/buscar', 'HistorialSolicitudesController@search');
    $router->get('solicitud-inscripcion/todas', 'HistorialSolicitudesController@getAll');
    $router->get('solicitud-inscripcion/aprobadas', 'HistorialSolicitudesController@getApproved');
    $router->get('solicitud-inscripcion/rechazadas', 'HistorialSolicitudesController@getRejected');

    // Resultados
    $router->group(['prefix' => 'resultados'], function () use ($router) {
        $router->get('supervision', 'ResultadosController@getResultadosSupervision');
        $router->get('evaluacion', 'ResultadosController@getResultadosEvaluacion');
    });
});


//==========================================================================
// RUTAS PARA ALUMNO
//==========================================================================

$router->group(['middleware' => ['auth.jwt', 'role:alumno']], function () use ($router) {
    // Gestión de docentes
    $router->get('mis-docentes', 'AlumnoDocenteController@index');
    $router->post('evaluar-docente', 'AlumnoDocenteController@evaluar');
    
    // Inscripción a grupos
    $router->post('inscribir-grupo', 'AlumnoDocenteController@inscribirGrupo');
    
    // Horario
    $router->get('mi-horario', 'HorarioController@getMiHorario');

    // Gestión de solicitudes de inscripción
    $router->post('solicitud-inscripcion', 'SolicitudInscripcionController@store');
    $router->get('mis-solicitudes', 'SolicitudInscripcionController@getMisSolicitudes');
    $router->delete('solicitud-inscripcion/{id}/cancelar', 'SolicitudInscripcionController@cancel');
});


//==========================================================================
// RUTAS PARA DOCENTE
//==========================================================================

$router->group(['middleware' => ['auth.jwt', 'role:docente']], function () use ($router) {
    // Perfil
    $router->get('perfil/docente', 'UsuarioController@getMiPerfilDocente');
});