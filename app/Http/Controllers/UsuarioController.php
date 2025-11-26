<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Alumno;
use App\Models\Docente;
use App\Models\Coordinador;
use App\Utils\RespuestaAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UsuarioController extends Controller
{
    /**
     * @OA\Get(
     *     path="/usuarios",
     *     summary="Lista de todos los usuarios",
     *     tags={"Usuarios"},
     *     security={{"jwt":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Una lista de usuarios.",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="Apps/Models/User")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $usuarios = User::all();
        return RespuestaAPI::exito('Lista de usuarios', $usuarios);
    }

    /**
     * @OA\Get(
     *     path="/usuarios/{id}",
     *     summary="Mostrar un usuario",
     *     tags={"Usuarios"},
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del usuario",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario encontrado.",
     *         @OA\JsonContent(ref="Apps/Models/User")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado."
     *     )
     * )
     */
    public function show($id)
    {
        $usuario = User::find($id);
        if (!$usuario) {
            return RespuestaAPI::error('Usuario no encontrado', RespuestaAPI::HTTP_NO_ENCONTRADO);
        }
        return RespuestaAPI::exito('Usuario encontrado', $usuario);
    }

    /**
     * @OA\Post(
     *     path="/usuarios",
     *     summary="Crear un nuevo usuario",
     *     tags={"Usuarios"},
     *     security={{"jwt":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre", "apellido_paterno", "apellido_materno", "correo", "contrasena", "rol"},
     *             @OA\Property(property="nombre", type="string", maxLength=100),
     *             @OA\Property(property="apellido_paterno", type="string", maxLength=100),
     *             @OA\Property(property="apellido_materno", type="string", maxLength=100),
     *             @OA\Property(property="correo", type="string", format="email"),
     *             @OA\Property(property="contrasena", type="string", format="password", minLength=8),
     *             @OA\Property(property="rol", type="integer", description="ID del rol")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuario creado exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Usuario creado exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Datos inválidos."
     *     ),
     *      @OA\Response(
     *         response=500,
     *         description="Error al crear el usuario."
     *     )
     * )
     */
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

    /**
     * @OA\Put(
     *     path="/usuarios/{id}",
     *     summary="Actualizar un usuario existente",
     *     tags={"Usuarios"},
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del usuario a actualizar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="nombre", type="string", maxLength=100),
     *             @OA\Property(property="apellido_paterno", type="string", maxLength=100),
     *             @OA\Property(property="apellido_materno", type="string", maxLength=100),
     *             @OA\Property(property="correo", type="string", format="email"),
     *             @OA\Property(property="contrasena", type="string", format="password", minLength=8),
     *             @OA\Property(property="id_rol", type="integer", description="ID del rol")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario actualizado exitosamente.",
     *         @OA\JsonContent(ref="Apps/Models/User")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado."
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Datos inválidos."
     *     )
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/usuarios/{id}",
     *     summary="Eliminar un usuario",
     *     tags={"Usuarios"},
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del usuario a eliminar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario eliminado exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Usuario eliminado exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al eliminar el usuario."
     *     )
     * )
     */
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
    private function getCarrerasDelCoordinador($idUsuario)
    {
        $coordinador = DB::table('coordinador')->where('usuario_id', $idUsuario)->first();
        if (!$coordinador) {
            return null; // O manejar como un error si se espera que siempre exista
        }

        return DB::table('coordinador_carrera')
                 ->where('id_coordinador', $coordinador->id_coordinador)
                 ->pluck('id_carrera')->toArray();
    }

    /**
     * @OA\Get(
     *     path="/alumnos",
     *     summary="Listar alumnos",
     *     description="Muestra una lista de alumnos. El resultado depende del rol del usuario (administrador o coordinador).",
     *     tags={"Alumnos"},
     *     security={{"jwt":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de alumnos.",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Alumno")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Usuario no autenticado."
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="No tienes permiso para ver esta lista."
     *     )
     * )
     */
    public function indexAlumnos()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return RespuestaAPI::error('Usuario no autenticado.', 401);
            }

            $rol = strtolower($user->rol);

            if ($rol === 'administrador') {
                $alumnos = Alumno::all();
                return RespuestaAPI::exito('Lista de todos los alumnos para el administrador', $alumnos);
            }

            if ($rol === 'coordinador') {
                $carreraIds = $this->getCarrerasDelCoordinador($user->id);

                if (empty($carreraIds)) {
                    return RespuestaAPI::exito('El coordinador no tiene carreras asignadas.', []);
                }

                $alumnos = Alumno::whereIn('id_carrera', $carreraIds)->get();
                return RespuestaAPI::exito('Lista de alumnos de las carreras coordinadas', $alumnos);
            }
            
            return RespuestaAPI::error('No tienes permiso para ver esta lista de alumnos.', 403);

        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al obtener la lista de alumnos: ' . $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/alumnos/{id}",
     *     summary="Mostrar un alumno específico",
     *     tags={"Alumnos"},
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del alumno",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Alumno encontrado."
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="No tienes permiso para ver este alumno."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Alumno no encontrado."
     *     )
     * )
     */
    public function showAlumno($id)
    {
        $user = Auth::user();
        $rol = strtolower($user->rol);

        $alumno = Alumno::find($id);

        if (!$alumno) {
            return RespuestaAPI::error('Alumno no encontrado', 404);
        }

        if ($rol === 'coordinador') {
            $carreraIds = $this->getCarrerasDelCoordinador($user->id);
            if (!in_array($alumno->id_carrera, $carreraIds)) {
                return RespuestaAPI::error('No tienes permiso para ver este alumno.', 403);
            }
        }

        return RespuestaAPI::exito('Alumno encontrado', $alumno);
    }

    /**
     * @OA\Post(
     *     path="/alumnos",
     *     summary="Crear un nuevo alumno",
     *     tags={"Alumnos"},
     *     security={{"jwt":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"matricula", "nombre", "apellido_paterno", "apellido_materno", "correo", "contrasena", "id_carrera"},
     *             @OA\Property(property="matricula", type="string", maxLength=15),
     *             @OA\Property(property="nombre", type="string", maxLength=100),
     *             @OA\Property(property="apellido_paterno", type="string", maxLength=100),
     *             @OA\Property(property="apellido_materno", type="string", maxLength=100),
     *             @OA\Property(property="correo", type="string", format="email"),
     *             @OA\Property(property="contrasena", type="string", format="password", minLength=8),
     *             @OA\Property(property="id_carrera", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Alumno creado exitosamente."
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="No tienes permiso para registrar alumnos en esta carrera."
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Datos inválidos."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al crear el alumno."
     *     )
     * )
     */
    public function storeAlumno(Request $request)
    {
        $user = Auth::user();
        $rol = strtolower($user->rol);

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

        if ($rol === 'coordinador') {
            $carreraIds = $this->getCarrerasDelCoordinador($user->id);
            if (!in_array($request->input('id_carrera'), $carreraIds)) {
                return RespuestaAPI::error('No tienes permiso para registrar alumnos en esta carrera.', 403);
            }
        }

        try {
            $result = Alumno::crearAlumno(
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
     * @OA\Put(
     *     path="/alumnos/{id}",
     *     summary="Actualizar la información de un alumno",
     *     tags={"Alumnos"},
     *     security={{"jwt":{}}},
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del alumno a actualizar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="matricula", type="string", maxLength=15),
     *             @OA\Property(property="nombre", type="string", maxLength=100),
     *             @OA\Property(property="apellido_paterno", type="string", maxLength=100),
     *             @OA\Property(property="apellido_materno", type="string", maxLength=100),
     *             @OA\Property(property="correo", type="string", format="email"),
     *             @OA\Property(property="contrasena", type="string", format="password", minLength=8),
     *             @OA\Property(property="id_carrera", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Alumno actualizado exitosamente."
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="No se proporcionaron datos para actualizar."
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="No tienes permiso para modificar este alumno."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Alumno no encontrado."
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Datos inválidos."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al actualizar el alumno."
     *     )
     * )
     */
    public function updateAlumno(Request $request, $id)
    {
        if (empty($request->all())) {
            return RespuestaAPI::error('No se proporcionaron datos para actualizar.', 400);
        }

        $user = Auth::user();
        $rol = strtolower($user->rol);

        $alumno = Alumno::find($id);
        if (!$alumno) {
            return RespuestaAPI::error('Alumno no encontrado', 404);
        }

        if ($rol === 'coordinador') {
            $carreraIds = $this->getCarrerasDelCoordinador($user->id);
            if (!in_array($alumno->id_carrera, $carreraIds)) {
                return RespuestaAPI::error('No tienes permiso para modificar este alumno.', 403);
            }
            // Si se intenta cambiar la carrera, verificar que la nueva carrera también sea coordinada
            if ($request->has('id_carrera') && !in_array($request->input('id_carrera'), $carreraIds)) {
                return RespuestaAPI::error('No tienes permiso para transferir alumnos a esta carrera.', 403);
            }
        }

        $validator = Validator::make($request->all(), [
            'nombre'           => 'sometimes|string|max:100|regex:/^[\pL\s\-]+$/u',
            'apellido_paterno' => 'sometimes|string|max:100|regex:/^[\pL\s\-]+$/u',
            'apellido_materno' => 'sometimes|string|max:100|regex:/^[\pL\s\-]+$/u',
            'correo'           => 'sometimes|email|unique:usuario,correo,' . $alumno->id_usuario,
            'contrasena'       => 'sometimes|string|min:8',
            'matricula'        => 'sometimes|string|max:15|unique:alumno,matricula,' . $id,
            'id_carrera'       => 'sometimes|integer|exists:carrera,id_carrera',
        ]);

        if ($validator->fails()) {
            return RespuestaAPI::error('Datos inválidos', 422, $validator->errors());
        }

        try {
            $contrasenaHash = $request->has('contrasena') && $request->input('contrasena')
                ? Hash::make($request->input('contrasena'))
                : null;

            DB::statement(
                'CALL sp_actualizar_alumno(?, ?, ?, ?, ?, ?, ?, ?)',
                [
                    $id,
                    $request->input('nombre', $alumno->nombre),
                    $request->input('apellido_paterno', $alumno->apellido_paterno),
                    $request->input('apellido_materno', $alumno->apellido_materno),
                    $request->input('correo', $alumno->correo),
                    $contrasenaHash,
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
     
    /**
     * @OA\Get(
     *     path="/docentes",
     *     summary="Listar todos los docentes",
     *     tags={"Docentes"},
     *     security={{"jwt":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Una lista de todos los docentes."
     *     )
     * )
     */
    public function indexDocentes()
    {
        $docentes = Docente::all();
        return RespuestaAPI::exito('Lista de docentes', $docentes);
    }

    /**
     * @OA\Get(
     *     path="/docentes/{id}",
     *     summary="Mostrar un docente específico",
     *     tags={"Docentes"},
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del docente",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Docente encontrado."
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="No tienes permiso para ver este docente."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Docente no encontrado."
     *     )
     * )
     */
    public function showDocente($id)
    {
        $user = Auth::user();
        $rol = strtolower($user->rol);

        $docente = Docente::find($id);

        if (!$docente) {
            return RespuestaAPI::error('Docente no encontrado', 404);
        }

        if ($rol === 'coordinador') {
            $carrerasCoordinador = $this->getCarrerasDelCoordinador($user->id);

            // Obtener las carreras asociadas al docente a través de los grupos que tiene asignados.
            // Esta es una suposición de la estructura de la base de datos. 
            // Se asume que la tabla 'horarios' o una similar vincula docentes a grupos.
            $carrerasDocente = DB::table('horarios as h')
                ->join('grupo as g', 'h.id_grupo', '=', 'g.id_grupo')
                ->where('h.id_docente', $id)
                ->pluck('g.id_carrera')
                ->unique()
                ->toArray();

            $hasAccess = !empty(array_intersect($carrerasCoordinador, $carrerasDocente));

            if (!$hasAccess) {
                return RespuestaAPI::error('No tienes permiso para ver este docente.', 403);
            }
        }

        return RespuestaAPI::exito('Docente encontrado', $docente);
    }

    /**
     * @OA\Post(
     *     path="/docentes",
     *     summary="Crear uno o más docentes",
     *     description="Crea un nuevo docente. Puede recibir un único objeto de docente o un arreglo de objetos.",
     *     tags={"Docentes"},
     *     security={{"jwt":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 required={"nombre", "apellido_paterno", "apellido_materno", "correo", "contrasena"},
     *                 @OA\Property(property="nombre", type="string", maxLength=100),
     *                 @OA\Property(property="apellido_paterno", type="string", maxLength=100),
     *                 @OA\Property(property="apellido_materno", type="string", maxLength=100),
     *                 @OA\Property(property="correo", type="string", format="email"),
     *                 @OA\Property(property="contrasena", type="string", format="password", minLength=8),
     *                 @OA\Property(property="grado_academico", type="string", maxLength=80, nullable=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Docente(s) creado(s) exitosamente."
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Datos inválidos o algunos docentes no pudieron ser creados."
     *     )
     * )
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
                $result = Docente::crearDocente(
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
     * @OA\Put(
     *     path="/docentes/{id}",
     *     summary="Actualizar la información de un docente",
     *     tags={"Docentes"},
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del docente a actualizar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="nombre", type="string", maxLength=100),
     *             @OA\Property(property="apellido_paterno", type="string", maxLength=100),
     *             @OA\Property(property="apellido_materno", type="string", maxLength=100),
     *             @OA\Property(property="correo", type="string", format="email"),
     *             @OA\Property(property="grado_academico", type="string", maxLength=80, nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Docente actualizado exitosamente."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Docente no encontrado."
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Datos inválidos."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al actualizar el docente."
     *     )
     * )
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
                'CALL sp_actualizar_docente(?, ?, ?, ?, ?, ?)',
                [
                    $id,
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
     * @OA\Delete(
     *     path="/docentes/{id}",
     *     summary="Eliminar un docente",
     *     tags={"Docentes"},
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del docente a eliminar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Docente eliminado exitosamente."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Docente no encontrado."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al eliminar el docente."
     *     )
     * )
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

    /**
     * @OA\Get(
     *     path="/docentes/mi-perfil",
     *     summary="Obtener el perfil del docente autenticado",
     *     tags={"Docentes"},
     *     security={{"jwt":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Perfil de docente encontrado."
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Usuario no autenticado."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Perfil de docente no encontrado."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener el perfil del docente."
     *     )
     * )
     */
    public function getMiPerfilDocente()
    {
        try {
            $idUsuario = Auth::id();
            if (!$idUsuario) {
                return RespuestaAPI::error('Usuario no autenticado.', 401);
            }

            // Asumiendo que la tabla 'docente' tiene una columna 'id_usuario' que la relaciona con 'usuario'
            $docente = Docente::where('id_usuario', $idUsuario)->first();

            if (!$docente) {
                return RespuestaAPI::error('Perfil de docente no encontrado para el usuario autenticado.', 404);
            }

            return RespuestaAPI::exito('Perfil de docente encontrado', $docente);
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al obtener el perfil del docente: ' . $e->getMessage(), 500);
        }
    }
}