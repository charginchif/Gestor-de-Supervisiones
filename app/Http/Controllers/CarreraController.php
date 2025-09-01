<?php

namespace App\Http\Controllers;

use App\Models\Carrera;
use Illuminate\Http\Request;
use App\Utils\RespuestaAPI;

class CarreraController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:jwt');
    }

    public function index()
    {
        $carreras = Carrera::all();
        return RespuestaAPI::exito('Listado de carreras', $carreras);
    }

    public function show($id)
    {
        $carrera = Carrera::find($id);
        if (!$carrera) {
            return RespuestaAPI::error('Carrera no encontrada', 404);
        }
        return RespuestaAPI::exito('Carrera encontrada', $carrera);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nombre' => 'required|string|max:255',
            'id_incorporacion' => 'nullable|integer',
        ]);

        $carrera = Carrera::create($request->all());

        return RespuestaAPI::exito('Carrera creada exitosamente', $carrera, 201);
    }

    public function update(Request $request, $id)
    {
        $carrera = Carrera::find($id);
        if (!$carrera) {
            return RespuestaAPI::error('Carrera no encontrada', 404);
        }

        $this->validate($request, [
            'nombre' => 'string|max:255',
            'id_incorporacion' => 'nullable|integer',
        ]);

        $carrera->update($request->all());

        return RespuestaAPI::exito('Carrera actualizada exitosamente', $carrera);
    }

    public function destroy($id)
    {
        $carrera = Carrera::find($id);
        if (!$carrera) {
            return RespuestaAPI::error('Carrera no encontrada', 404);
        }

        $carrera->delete();

        return RespuestaAPI::exito('Carrera eliminada exitosamente');
    }
}