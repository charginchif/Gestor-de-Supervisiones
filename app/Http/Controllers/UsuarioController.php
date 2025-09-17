<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Alumno;
use App\Models\Docente;
use App\Utils\RespuestaAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::all();
        return RespuestaAPI::exito('Lista de usuarios', $usuarios);
    }

    public function show($id)
    {
        $usuario = User::find($id);
        if (!$usuario) {
            return RespuestaAPI::error('Usuario no encontrado', RespuestaAPI::HTTP_NO_ENCONTRADO);
        }
        return RespuestaAPI::exito('Usuario encontrado', $usuario);
    }

    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'nombre' => 'required|string|max:100',
                'apellido_paterno' => 'required|string|max:100',
                'apellido_materno' => 'required|string|max:100',
                'correo' => 'required|email|unique:usuario,correo',
                'contrasena' => 'required|string|min:8',
                'rol' => 'required|int', // Ejemplo: "Alumno", "Docente"
            ]);
        } catch (ValidationException $e) {
            return RespuestaAPI::error('Datos inválidos', RespuestaAPI::HTTP_ERROR_VALIDACION, ['errors' => $e->errors()]);
        }

        try {
            User::crearUsuario(
                $request->input('nombre'),
                $request->input('apellido_paterno'),
                $request->input('apellido_materno'),
                $request->input('correo'),
                Hash::make($request->input('contrasena')),
                $request->input('rol')
            );

            return RespuestaAPI::exito('Usuario creado exitosamente', null, RespuestaAPI::HTTP_CREADO);
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al crear el usuario: ' . $e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id)
    {
    
        $usuario = User::find($id);
        if (!$usuario) {
            return RespuestaAPI::error('Usuario no encontrado', RespuestaAPI::HTTP_NO_ENCONTRADO);
        }

        try {
            $this->validate($request, [
                'nombre' => 'sometimes|required|string|max:100',
                'apellido_paterno' => 'sometimes|required|string|max:100',
                'apellido_materno' => 'sometimes|required|string|max:100',
                'correo' => 'sometimes|required|email|unique:usuario,correo,' . $id,
                'contrasena' => 'sometimes|string|min:8',
                'id_rol' => 'sometimes|required|integer|exists:rol,id',
            ]);
        } catch (ValidationException $e) {
            return RespuestaAPI::error('Datos inválidos', RespuestaAPI::HTTP_ERROR_VALIDACION, ['errors' => $e->errors()]);
        }

        $data = $request->all();
        if ($request->has('contrasena')) {
            $data['contrasena'] = Hash::make($data['contrasena']);
        }

        $usuario->update($data);

        return RespuestaAPI::exito('Usuario actualizado exitosamente', $usuario);
    }

    public function destroy($id)
    {
        $usuario = User::find($id);
        if (!$usuario) {
            return RespuestaAPI::error('Usuario no encontrado', RespuestaAPI::HTTP_NO_ENCONTRADO);
        }

        try {
            DB::statement('CALL sp_eliminar_usuario(?)', [$id]);
            return RespuestaAPI::exito('Usuario eliminado exitosamente', null, 200);
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al eliminar el usuario: ' . $e->getMessage(), 500);
        }
    }

    /**
     * ===================================================================
     * Métodos para la Gestión de Alumnos
     * ===================================================================
     */

    /**
     * Muestra una lista de todos los alumnos.
     */
    public function indexAlumnos()
    {
        $alumnos = Alumno::all();
        return RespuestaAPI::exito('Lista de alumnos', $alumnos);
    }

    /**
     * Muestra un alumno específico.
     */
    public function showAlumno($id)
    {
        $alumno = Alumno::find($id);

        if (!$alumno) {
            return RespuestaAPI::error('Alumno no encontrado', 404);
        }

        return RespuestaAPI::exito('Alumno encontrado', $alumno);
    }

    /**
     * Crea un nuevo alumno.
     */
    public function storeAlumno(Request $request)
    {
        $userData = $request->all();

        $validator = Validator::make($userData, [
            'matricula'        => 'required|string|max:15|unique:alumno,matricula',
            'nombre'           => 'required|string|max:100',
            'apellido_paterno' => 'required|string|max:100',
            'apellido_materno' => 'required|string|max:100',
            'correo'           => 'required|email|unique:usuario,correo',
            'contrasena'       => 'required|string|min:8',
            'id_carrera'       => 'required|integer|exists:carrera,id_carrera',
        ]);

        if ($validator->fails()) {
            return RespuestaAPI::error('Datos inválidos', 422, $validator->errors());
        }

        try {
            $result = User::crearAlumno(
                $userData['nombre'],
                $userData['apellido_paterno'],
                $userData['apellido_materno'],
                $userData['correo'],
                Hash::make($userData['contrasena']),
                $userData['matricula'],
                $userData['id_carrera']
            );
            return RespuestaAPI::exito('Alumno creado exitosamente', $result, 201);
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al crear el alumno: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Actualiza la información de un alumno.
     */
    public function updateAlumno(Request $request, $id)
    {
        $alumno = Alumno::find($id);
        if (!$alumno) {
            return RespuestaAPI::error('Alumno no encontrado', 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre'           => 'sometimes|string|max:100|regex:/^[\pL\s\-]+$/u',
            'apellido_paterno' => 'sometimes|string|max:100|regex:/^[\pL\s\-]+$/u',
            'apellido_materno' => 'sometimes|string|max:100|regex:/^[\pL\s\-]+$/u',
            'correo'           => 'sometimes|email|unique:usuario,correo,' . $alumno->id_usuario,
            'matricula'        => 'sometimes|string|max:15|unique:alumno,matricula,' . $id,
            'id_carrera'       => 'sometimes|integer|exists:carreras,id_carrera',
        ]);

        if ($validator->fails()) {
            return RespuestaAPI::error('Datos inválidos', 422, $validator->errors());
        }

        try {
            DB::statement(
                'CALL sp_actualizar_alumno(?, ?, ?, ?, ?, ?, ?, ?)',
                [
                    $id,
                    $alumno->id_usuario,
                    $request->input('nombre', $alumno->nombre),
                    $request->input('apellido_paterno', $alumno->apellido_paterno),
                    $request->input('apellido_materno', $alumno->apellido_materno),
                    $request->input('correo', $alumno->correo),
                    $request->input('matricula', $alumno->matricula),
                    $request->input('id_carrera', $alumno->id_carrera),
                ]
            );

            $updatedAlumno = Alumno::find($id);
            return RespuestaAPI::exito('Alumno actualizado exitosamente', $updatedAlumno);

        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al actualizar el alumno: ' . $e->getMessage(), 500);
        }
    }
   
    /**
     * ===================================================================
     * Métodos para la Gestión de Docentes
     * ===================================================================
     
     * Muestra una lista de todos los docentes.
     */
    public function indexDocentes()
    {
        $docentes = Docente::all();
        return RespuestaAPI::exito('Lista de docentes', $docentes);
    }

    /**
     * Muestra un docente específico.
     */
    public function showDocente($id)
    {
        $docente = Docente::find($id);

        if (!$docente) {
            return RespuestaAPI::error('Docente no encontrado', 404);
        }

        return RespuestaAPI::exito('Docente encontrado', $docente);
    }

    /**
     * Crea un nuevo docente.
     */
    public function storeDocente(Request $request)
    {
        $usersData = $request->all();
        $results = [];
        $errors = [];

        if (!is_array(reset($usersData))) {
            $usersData = [$usersData];
        }

        foreach ($usersData as $userData) {
            $validator = Validator::make($userData, [
                'nombre'           => 'required|string|max:100',
                'apellido_paterno' => 'required|string|max:100',
                'apellido_materno' => 'required|string|max:100',
                'correo'           => 'required|email|unique:usuario,correo',
                'contrasena'       => 'required|string|min:8',
                'grado_academico'  => 'nullable|string|max:80',
            ]);

            if ($validator->fails()) {
                $errors[] = [
                    'correo' => $userData['correo'] ?? 'N/A',
                    'errors' => $validator->errors()
                ];
                continue;
            }

            try {
                $result = User::crearDocente(
                    $userData['nombre'],
                    $userData['apellido_paterno'],
                    $userData['apellido_materno'],
                    $userData['correo'],
                    Hash::make($userData['contrasena']),
                    $userData['grado_academico'] ?? null
                );
                $results[] = $result;
            } catch (\Exception $e) {
                $errors[] = [
                    'correo' => $userData['correo'],
                    'error' => $e->getMessage()
                ];
            }
        }

        if (!empty($errors)) {
            return RespuestaAPI::error('Algunos docentes no pudieron ser creados', 422, ['errors' => $errors, 'created' => $results]);
        }

        return RespuestaAPI::exito('Docentes creados exitosamente', $results, 201);
    }

    /**
     * Actualiza la información de un docente.
     */
    public function updateDocente(Request $request, $id)
    {
        $docente = Docente::find($id);
        if (!$docente) {
            return RespuestaAPI::error('Docente no encontrado', 404);
        }
        
        $validator = Validator::make($request->all(), [
            'nombre'           => 'sometimes|string|max:100|regex:/^[\pL\s\-]+$/u',
            'apellido_paterno' => 'sometimes|string|max:100|regex:/^[\pL\s\-]+$/u',
            'apellido_materno' => 'sometimes|string|max:100|regex:/^[\pL\s\-]+$/u',
            'correo'           => 'sometimes|email|unique:usuario,correo,' . $docente->id_usuario,
            'grado_academico'  => 'sometimes|nullable|string|max:80',
        ]);

        if ($validator->fails()) {
            return RespuestaAPI::error('Datos inválidos', 422, $validator->errors());
        }

        try {
            DB::statement(
                'CALL sp_actualizar_docente(?, ?, ?, ?, ?, ?, ?)',
                [
                    $id,
                    $docente->id_usuario,
                    $request->input('nombre', $docente->nombre),
                    $request->input('apellido_paterno', $docente->apellido_paterno),
                    $request->input('apellido_materno', $docente->apellido_materno),
                    $request->input('correo', $docente->correo),
                    $request->input('grado_academico', $docente->grado_academico),
                ]
            );

            $updatedDocente = Docente::find($id);
            return RespuestaAPI::exito('Docente actualizado exitosamente', $updatedDocente);

        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al actualizar el docente: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Elimina un docente.
     */
    public function destroyDocente($id)
    {
        $docente = Docente::find($id);
        if (!$docente) {
            return RespuestaAPI::error('Docente no encontrado', 404);
        }

        try {
            DB::statement('CALL sp_eliminar_usuario(?)', [$docente->id_usuario]);
            return RespuestaAPI::exito('Docente eliminado exitosamente', null, 200);
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al eliminar el docente: ' . $e->getMessage(), 500);
        }
    }
}