<?php

namespace App\Http\Controllers;

use App\Models\Plantel;
use Illuminate\Http\Request;
use App\Utils\RespuestaAPI;

class PlantelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:jwt');
    }

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
            'nombre' => 'required|string|max:255',
            'ubicacion' => 'required|string|max:255',
        ]);

        $plantel = Plantel::create($request->all());

        return RespuestaAPI::exito('Plantel creado exitosamente', $plantel, 201);
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
}
