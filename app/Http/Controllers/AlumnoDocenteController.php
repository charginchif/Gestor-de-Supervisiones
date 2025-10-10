<?php

namespace App\Http\Controllers;

use App\Models\VwAlumnoDocente;
use App\Utils\RespuestaAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;

class AlumnoDocenteController extends Controller
{
    /**
     * Muestra la lista de docentes para el alumno autenticado.
     */
    public function index()
    {
        try {
            $usuarioId = Auth::id();

            if (!$usuarioId) {
                return RespuestaAPI::error('No autorizado. No se pudo encontrar al usuario.', 401);
            }

            $docentes = VwAlumnoDocente::where('id_usuario', $usuarioId)->get();

            if ($docentes->isEmpty()) {
                return RespuestaAPI::exito('No tienes docentes asignados actualmente.', [], 200);
            }

            return RespuestaAPI::exito('Docentes del alumno obtenidos con éxito.', $docentes);

        } catch (\Exception $e) {
            return RespuestaAPI::error('Ocurrió un error al obtener los docentes.', 500, ['details' => $e->getMessage()]);
        }
    }

    /**
     * Almacena la evaluación de un docente realizada por un alumno.
     */
    public function evaluar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_grupo'   => 'bail|required|integer',
            'id_docente' => 'bail|required|integer',
            'evaluacion' => 'bail|required|json',
        ], [
            'id_grupo.required' => 'El ID del grupo es obligatorio.',
            'id_docente.required' => 'El ID del docente es obligatorio.',
            'evaluacion.required' => 'El JSON de la evaluación es obligatorio.',
            'evaluacion.json' => 'El formato de la evaluación debe ser un JSON válido.',
        ]);

        if ($validator->fails()) {
            return RespuestaAPI::error('Datos inválidos', 422, ['errors' => $validator->errors()]);
        }

        try {
            $validatedData = $validator->validated();
            
            $idGrupo = $validatedData['id_grupo'];
            $idDocente = $validatedData['id_docente'];
            $jsonEvaluacion = $validatedData['evaluacion'];

            // El procedimiento almacenado espera un string JSON.
            // El validador de Laravel ya decodifica el JSON si viene como string,
            // así que lo recodificamos si es necesario.
            if (is_array($jsonEvaluacion)) {
                $jsonEvaluacion = json_encode($jsonEvaluacion);
            }

            // Llamar al procedimiento almacenado
            $resultado = DB::select('CALL sp_evaluar_docente(?, ?, ?)', [
                $idGrupo,
                $idDocente,
                $jsonEvaluacion
            ]);

            // El SP devuelve un array con un objeto, lo extraemos.
            $datosResultado = $resultado[0] ?? null;

            return RespuestaAPI::exito('Evaluación guardada correctamente.', $datosResultado);

        } catch (QueryException $e) {
            // Capturar errores específicos de la base de datos (ej. SIGNAL SQLSTATE)
            $errorMessage = $e->errorInfo[2] ?? 'Error en la base de datos.';
            return RespuestaAPI::error($errorMessage, 400);
        } catch (\Exception $e) {
            // Otros errores inesperados
            return RespuestaAPI::error('Ocurrió un error al procesar la evaluación.', 500, ['details' => $e->getMessage()]);
        }
    }
    /**
     * Inscribe a un alumno en un grupo.
     */
    public function inscribirGrupo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_grupo' => 'bail|required|integer',
        ], [
            'id_grupo.required' => 'El ID del grupo es obligatorio.',
        ]);

        if ($validator->fails()) {
            return RespuestaAPI::error('Datos inválidos', 422, ['errors' => $validator->errors()]);
        }

        try {
            $idGrupo = $request->input('id_grupo');
            $idUsuario = Auth::id();

            // Busca el alumno correspondiente al usuario autenticado
            $alumno = DB::table('vw_alumno_perfil')->where('id_usuario', $idUsuario)->first();

            if (!$alumno) {
                return RespuestaAPI::error('No se encontró el alumno correspondiente al usuario autenticado.', 404);
            }

            $idAlumno = $alumno->id_alumno;

            // Llamar al procedimiento almacenado para inscribir al alumno.
            $resultadoInscripcion = DB::select('CALL sp_inscripcion_grupo_alumno(?, ?)', [
                $idGrupo,
                $idAlumno
            ]);

            // El procedimiento devuelve un array con un objeto de resultado, lo extraemos.
            $datosResultado = $resultadoInscripcion[0] ?? null;
x
            // Determinar el mensaje de éxito basado en el resultado del SP.
            $mensaje = 'Inscripción al grupo procesada.';
            if ($datosResultado && $datosResultado->resultado === 'YA_INSCRITO') {
                $mensaje = 'El alumno ya se encontraba inscrito en este grupo.';
            } elseif ($datosResultado && $datosResultado->resultado === 'INSCRIPCION_CREADA') {
                $mensaje = 'Inscripción al grupo exitosa.';
            }

            return RespuestaAPI::exito($mensaje, $datosResultado);

        } catch (QueryException $e) {
            // Capturar errores específicos de la base de datos (ej. SIGNAL SQLSTATE)
            $errorMessage = $e->errorInfo[2] ?? 'Error en la base de datos al inscribir al grupo.';
            return RespuestaAPI::error($errorMessage, 400);
        } catch (\Exception $e) {
            return RespuestaAPI::error('Ocurrió un error al inscribir al grupo.', 500, ['details' => $e->getMessage()]);
        }
    }
}