<?php

namespace App\Http\Controllers;

use App\Models\Plantel;
use Illuminate\Http\Request;
use App\Utils\RespuestaAPI;
use Illuminate\Support\Facades\DB;
use App\Models\Coordinador; // Added this line

class PlantelController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:jwt');
    //     $this->middleware('role:administrador', ['only' => ['store', 'update', 'destroy']]);
    // }

    /**
     * @OA\Get(
     *     path="/planteles",
     *     summary="Listar todos los planteles",
     *     tags={"Planteles"},
     *     @OA\Response(
     *         response=200,
     *         description="Listado de planteles",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Plantel")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $planteles = Plantel::all();
        return RespuestaAPI::exito('Listado de planteles', $planteles);
    }

    /**
     * @OA\Get(
     *     path="/planteles/{id}",
     *     summary="Obtener un plantel por su ID",
     *     tags={"Planteles"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del plantel",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Plantel encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/Plantel")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Plantel no encontrado"
     *     )
     * )
     */
    public function show($id)
    {
        $plantel = Plantel::find($id);
        if (!$plantel) {
            return RespuestaAPI::error('Plantel no encontrado', 404);
        }
        return RespuestaAPI::exito('Plantel encontrado', $plantel);
    }

    /**
     * @OA\Post(
     *     path="/planteles",
     *     summary="Crear un nuevo plantel",
     *     tags={"Planteles"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre","ubicacion"},
     *             @OA\Property(property="nombre", type="string", maxLength=100, example="Plantel Central"),
     *             @OA\Property(property="ubicacion", type="string", maxLength=150, example="Avenida Siempre Viva 123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Plantel creado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/Plantel")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al crear el plantel"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'nombre' => 'required|string|max:100',
            'ubicacion' => 'required|string|max:150',
        ]);

        try {
            DB::statement(
                'CALL sp_plantel_insertar(?, ?)',
                [$request->input('nombre'), $request->input('ubicacion')]
            );

            // Since the SP doesn't return the created model, we'll just return the input data.
            $plantelData = $request->all();

            return RespuestaAPI::exito('Plantel creado exitosamente', $plantelData, 201);
        } catch (\Illuminate\Database\QueryException $e) {
            // The SP signals a '45000' for validation errors.
            if ($e->getCode() === '45000') {
                return RespuestaAPI::error($e->errorInfo[2], 400);
            }
            return RespuestaAPI::error('Error al crear el plantel.', 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/planteles/{id}",
     *     summary="Actualizar un plantel existente",
     *     tags={"Planteles"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del plantel",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre","ubicacion"},
     *             @OA\Property(property="nombre", type="string", maxLength=255, example="Plantel Norte"),
     *             @OA\Property(property="ubicacion", type="string", maxLength=255, example="Calle Falsa 123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Plantel actualizado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/Plantel")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Plantel no encontrado"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al actualizar el plantel"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nombre' => 'required|string|max:255',
            'ubicacion' => 'required|string|max:255',
        ]);

        try {
            DB::statement(
                'CALL sp_plantel_actualizar(?, ?, ?)',
                [$id, $request->input('nombre'), $request->input('ubicacion')]
            );

            // Después de la actualización, obtenemos el plantel actualizado para devolverlo.
            $plantelActualizado = Plantel::find($id);

            return RespuestaAPI::exito('Plantel actualizado exitosamente', $plantelActualizado);

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '45000') {
                return RespuestaAPI::error($e->errorInfo[2], 400);
            }
            return RespuestaAPI::error('Error al actualizar el plantel: ' . $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/planteles/{id}",
     *     summary="Eliminar un plantel",
     *     tags={"Planteles"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del plantel",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Plantel eliminado exitosamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error al eliminar el plantel (e.g., dependencias)"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Plantel no encontrado"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al eliminar el plantel"
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            // Llama al procedimiento almacenado para eliminar el plantel.
            $resultado = DB::statement('CALL sp_plantel_eliminar(?)', [$id]);

            // El procedimiento almacenado podría no devolver un valor indicativo de éxito
            // o podría lanzar una excepción (que sería capturada por el bloque catch).
            // Si no hay excepción, asumimos que la operación fue exitosa.
            return RespuestaAPI::exito('Plantel eliminado exitosamente', null, 200);

        } catch (\Illuminate\Database\QueryException $e) {
            // El SP puede señalar un error con un código de estado SQL '45000'.
            if ($e->getCode() === '45000') {
                // El mensaje de error viene del procedimiento almacenado.
                return RespuestaAPI::error($e->errorInfo[2], 400);
            }
            // Para otros errores de base de datos.
            return RespuestaAPI::error('Error al eliminar el plantel: ' . $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/coordinador/planteles",
     *     summary="Listar los planteles de un coordinador",
     *     tags={"Planteles"},
     *     @OA\Response(
     *         response=200,
     *         description="Listado de planteles del coordinador",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Plantel")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado"
     *     )
     * )
     */
    public function indexCoordinadorPlanteles(Request $request)
    {
        $claims = $request->attributes->get('jwt_claims');
        $userId = $claims['sub']; // Assuming 'sub' contains the user ID

        $coordinador = Coordinador::where('usuario_id', $userId)->first();

        if (!$coordinador) {
            return RespuestaAPI::error('Coordinador no encontrado para el usuario autenticado', RespuestaAPI::HTTP_NO_AUTORIZADO);
        }

        $planteles = Plantel::getPlantelesByCoordinador($coordinador->id_coordinador);

        return RespuestaAPI::exito('Listado de planteles del coordinador', $planteles);
    }
}