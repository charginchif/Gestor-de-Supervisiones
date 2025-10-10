<?php

namespace App\Http\Controllers;

use App\Models\Coordinador;
use App\Utils\RespuestaAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CoordinadorController extends UsuarioController
{
    /**
     * Muestra una lista de todos los coordinadores.
     */
    public function index()
    {
        $coordinadores = Coordinador::all();
        return RespuestaAPI::exito('Lista de coordinadores', $coordinadores);
    }

    /**
     * Muestra un coordinador específico.
     */
    public function show($id)
    {
        $coordinador = Coordinador::find($id);

        if (!$coordinador) {
            return RespuestaAPI::error('Coordinador no encontrado', 404);
        }

        return RespuestaAPI::exito('Coordinador encontrado', $coordinador);
    }

    /**
     * Crea un nuevo coordinador.
     */
    public function store(Request $request)
    {
        return parent::storeCoordinador($request);
    }

    /**
     * Actualiza la información de un coordinador.
     */
    public function update(Request $request, $id)
    {
        $coordinador = Coordinador::find($id);
        if (!$coordinador) {
            return RespuestaAPI::error('Coordinador no encontrado', 404);
        }
        
        $validator = Validator::make($request->all(), [
            'nombre'           => 'sometimes|string|max:100|regex:/^[\pL\s\-]+$/u',
            'apellido_paterno' => 'sometimes|string|max:100|regex:/^[\pL\s\-]+$/u',
            'apellido_materno' => 'sometimes|string|max:100|regex:/^[\pL\s\-]+$/u',
            'correo'           => 'sometimes|email|unique:usuario,correo,' . $coordinador->id_usuario,
        ]);

        if ($validator->fails()) {
            return RespuestaAPI::error('Datos inválidos', 422, $validator->errors());
        }

        try {
            DB::statement(
                'CALL sp_actualizar_coordinador(?, ?, ?, ?, ?, ?)',
                [
                    $id,
                    $coordinador->id_usuario,
                    $request->input('nombre', $coordinador->nombre),
                    $request->input('apellido_paterno', $coordinador->apellido_paterno),
                    $request->input('apellido_materno', $coordinador->apellido_materno),
                    $request->input('correo', $coordinador->correo),
                ]
            );

            $updatedCoordinador = Coordinador::find($id);
            return RespuestaAPI::exito('Coordinador actualizado exitosamente', $updatedCoordinador);

        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al actualizar el coordinador: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Elimina un coordinador.
     */
    public function destroy($id)
    {
        $coordinador = Coordinador::find($id);
        if (!$coordinador) {
            return RespuestaAPI::error('Coordinador no encontrado', 404);
        }

        try {
            DB::statement('CALL sp_eliminar_usuario(?)', [$coordinador->id_usuario]);
            return RespuestaAPI::exito('Coordinador eliminado exitosamente', null, 200);
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al eliminar el coordinador: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Muestra una lista de todos los criterios de supervisión contables.
     */
    public function criteriosContables()
    {
        $criterios = CriterioSupervision::where('contable', 1)->get();
        return RespuestaAPI::exito('Criterios de supervisión contables obtenidos con éxito', $criterios);
    }

    /**
     * Muestra una lista de todos los criterios de supervisión no contables.
     */
    public function criteriosNoContables()
    {
        $criterios = CriterioSupervision::where('contable', 0)->get();
        return RespuestaAPI::exito('Criterios de supervisión no contables obtenidos con éxito', $criterios);
    }
}