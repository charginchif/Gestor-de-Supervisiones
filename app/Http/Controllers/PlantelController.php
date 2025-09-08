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

    public function index()
    {
        $planteles = Plantel::all();
        return RespuestaAPI::exito('Listado de planteles', $planteles);
    }

    public function show($id)
    {
        $plantel = Plantel::find($id);
        if (!$plantel) {
            return RespuestaAPI::error('Plantel no encontrado', 404);
        }
        return RespuestaAPI::exito('Plantel encontrado', $plantel);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nombre' => 'required|string|max:100',
            'ubicacion' => 'required|string|max:150',
        ]);

        try {
            DB::statement(
                'CALL sp_admin_registrar_plantel(?, ?)',
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

    public function update(Request $request, $id)
    {
        $plantel = Plantel::find($id);
        if (!$plantel) {
            return RespuestaAPI::error('Plantel no encontrado', 404);
        }

        $this->validate($request, [
            'nombre' => 'required|string|max:255',
            'ubicacion' => 'required|string|max:255',
        ]);

        $plantel->update($request->all());

        return RespuestaAPI::exito('Plantel actualizado exitosamente', $plantel);
    }

    public function destroy($id)
    {
        $plantel = Plantel::find($id);
        if (!$plantel) {
            return RespuestaAPI::error('Plantel no encontrado', 404);
        }

        $plantel->delete();

        return RespuestaAPI::exito('Plantel eliminado exitosamente');
    }

    /**
     * Muestra los planteles asociados al coordinador autenticado.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexCoordinadorPlanteles(Request $request)
    {
        $claims = $request->attributes->get('jwt_claims');
        $userId = $claims['sub']; // Assuming 'sub' contains the user ID

        $coordinador = Coordinador::where('id_usuario', $userId)->first();

        if (!$coordinador) {
            return RespuestaAPI::error('Coordinador no encontrado para el usuario autenticado', RespuestaAPI::HTTP_NO_AUTORIZADO);
        }

        $planteles = Plantel::getPlantelesByCoordinador($coordinador->id_coordinador);

        return RespuestaAPI::exito('Listado de planteles del coordinador', $planteles);
    }
}