<?php

namespace App\Http\Controllers;

use App\Models\CriterioSupervisionContableModelo;
use App\Models\CriterioSupervisionNoContableModelo;
use App\Utils\RespuestaAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

/**
 * Controlador para gestionar los criterios de supervisión.
 *
 * Este controlador maneja la lógica de negocio para los criterios de supervisión,
 * tanto contables como no contables.
 */
class SupervisionController extends Controller
{
    /**
     * Devuelve una lista de todos los rubros contables y no contables.
     */
    public function listarRubrosContablesNoContables(Request $request)
    {
        $contables = CriterioSupervisionContableModelo::all();
        $noContables = CriterioSupervisionNoContableModelo::all();

        $rubros = [];
        return RespuestaAPI::exito('Criterios de supervisión contables y no contables obtenidos con éxito', [
            'contables' => $contables,
            'no_contables' => $noContables,
        ]);
    }

    // --- Criterios Contables ---

    /**
     * Devuelve todos los criterios de supervisión contables, agrupados por rubro.
     */
    public function indexContable()
    {
        $criterios = CriterioSupervisionContableModelo::all();
        $rubros = [];

        foreach ($criterios as $criterio) {
            if (!isset($rubros[$criterio->id_rubro])) {
                $rubros[$criterio->id_rubro] = [
                    'id_rubro' => $criterio->id_rubro,
                    'nombre' => $criterio->rubro,
                    'criterios' => [],
                ];
            }

            $rubros[$criterio->id_rubro]['criterios'][] = [
                'id_criterio' => $criterio->id_supcriterio,
                'criterio' => $criterio->criterio,
            ];
        }

        $datos = ['rubros' => array_values($rubros)];

        return RespuestaAPI::exito('Criterios de supervisión contables obtenidos con éxito', $datos);
    }

    /**
     * Muestra un criterio de supervisión contable específico.
     */
    public function showContable($id)
    {
        $criterio = CriterioSupervisionContableModelo::find($id);
        if ($criterio) {
            return RespuestaAPI::exito('Criterio de supervisión contable obtenido con éxito', $criterio);
        } else {
            return RespuestaAPI::error('Criterio de supervisión contable no encontrado', RespuestaAPI::HTTP_NOT_FOUND);
        }
    }

    /**
     * Almacena un nuevo criterio de supervisión contable.
     */
    public function storeContable(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'p_descripcion' => 'required|string',
            'p_id_rubro' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return RespuestaAPI::error('Datos inválidos', RespuestaAPI::HTTP_ERROR_VALIDACION, ['errors' => $validator->errors()]);
        }

        try {
            DB::statement(
                'CALL sp_criterio_supervision_insertar(?, ?)',
                [
                    $request->input('p_descripcion'),
                    $request->input('p_id_rubro'),
                ]
            );
            return RespuestaAPI::exito('Criterio contable creado con éxito');
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al crear el criterio contable: ' . $e->getMessage(), RespuestaAPI::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Actualiza un criterio de supervisión contable existente.
     */
    public function updateContable(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'p_descripcion' => 'required|string',
            'p_id_rubro' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return RespuestaAPI::error('Datos inválidos', RespuestaAPI::HTTP_ERROR_VALIDACION, ['errors' => $validator->errors()]);
        }

        try {
            DB::statement(
                'CALL sp_criterio_supervision_actualizar(?, ?, ?)',
                [
                    $id,
                    $request->input('p_descripcion'),
                    $request->input('p_id_rubro'),
                ]
            );
            return RespuestaAPI::exito('Criterio contable actualizado con éxito');
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al actualizar el criterio contable: ' . $e->getMessage(), RespuestaAPI::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Elimina un criterio de supervisión contable.
     */
    public function destroyContable($id)
    {
        try {
            DB::statement(
                'CALL sp_criterio_supervision_eliminar(?)',
                [$id]
            );
            return RespuestaAPI::exito('Criterio contable eliminado con éxito');
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al eliminar el criterio contable: ' . $e->getMessage(), RespuestaAPI::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Busca criterios de supervisión contables por id_rubro y/o nombre del rubro.
     */
    public function buscarContable(Request $request)
    {
        if (!$request->has('id_rubro') && !$request->has('nombre')) {
            return RespuestaAPI::error('Debe proporcionar al menos un parámetro de búsqueda (id_rubro o nombre).', RespuestaAPI::HTTP_ERROR_VALIDACION);
        }

        $query = CriterioSupervisionContableModelo::query();

        if ($request->has('id_rubro')) {
            $query->where('id_rubro', $request->input('id_rubro'));
        }

        if ($request->has('nombre')) {
            $query->where('rubro', 'like', '%' . $request->input('nombre') . '%');
        }

        $criterios = $query->get();

        if ($criterios->isEmpty()) {
            return RespuestaAPI::exito('No se encontraron criterios con los parámetros de búsqueda proporcionados.', []);
        }

        $rubros = [];

        foreach ($criterios as $criterio) {
            if (!isset($rubros[$criterio->id_rubro])) {
                $rubros[$criterio->id_rubro] = [
                    'id_rubro' => $criterio->id_rubro,
                    'nombre' => $criterio->rubro,
                    'criterios' => [],
                ];
            }

            $rubros[$criterio->id_rubro]['criterios'][] = [
                'id_criterio' => $criterio->id_supcriterio,
                'criterio' => $criterio->criterio,
            ];
        }

        $datos = ['rubros' => array_values($rubros)];

        return RespuestaAPI::exito('Criterios de supervisión contables filtrados obtenidos con éxito', $datos);
    }

    // --- Criterios No Contables ---

    /**
     * Devuelve todos los criterios de supervisión no contables, agrupados por rubro.
     */
    public function indexNoContable()
    {
        $criterios = CriterioSupervisionNoContableModelo::all();
        $rubros = [];

        foreach ($criterios as $criterio) {
            if (!isset($rubros[$criterio->id_nc_rubro])) {
                $rubros[$criterio->id_nc_rubro] = [
                    'id_nc_rubro' => $criterio->id_nc_rubro,
                    'nombre' => $criterio->rubro,
                    'criterios' => [],
                ];
            }

            $rubros[$criterio->id_nc_rubro]['criterios'][] = [
                'id_nc_criterio' => $criterio->id_nc_criterio,
                'criterio' => $criterio->criterio,
            ];
        }

        $datos = ['rubros' => array_values($rubros)];

        return RespuestaAPI::exito('Criterios de supervisión no contables obtenidos con éxito', $datos);
    }

    /**
     * Muestra un criterio de supervisión no contable específico.
     */
    public function showNoContable($id)
    {
        $criterio = CriterioSupervisionNoContableModelo::find($id);
        if ($criterio) {
            return RespuestaAPI::exito('Criterio de supervisión no contable obtenido con éxito', $criterio);
        } else {
            return RespuestaAPI::error('Criterio de supervisión no contable no encontrado', RespuestaAPI::HTTP_NOT_FOUND);
        }
    }

    /**
     * Almacena un nuevo criterio de supervisión no contable.
     */
    public function storeNoContable(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'p_descripcion' => 'required|string',
            'p_id_nc_rubro' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return RespuestaAPI::error('Datos inválidos', RespuestaAPI::HTTP_ERROR_VALIDACION, ['errors' => $validator->errors()]);
        }

        try {
            DB::statement(
                'CALL sp_criterio_supervision_no_contable_insertar(?, ?)',
                [
                    $request->input('p_descripcion'),
                    $request->input('p_id_nc_rubro'),
                ]
            );
            return RespuestaAPI::exito('Criterio no contable creado con éxito');
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al crear el criterio no contable: ' . $e->getMessage(), RespuestaAPI::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Actualiza un criterio de supervisión no contable existente.
     */
    public function updateNoContable(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'p_descripcion' => 'required|string',
            'p_id_nc_rubro' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return RespuestaAPI::error('Datos inválidos', RespuestaAPI::HTTP_ERROR_VALIDACION, ['errors' => $validator->errors()]);
        }

        try {
            DB::statement(
                'CALL sp_criterio_supervision_no_contable_actualizar(?, ?, ?)',
                [
                    $id,
                    $request->input('p_descripcion'),
                    $request->input('p_id_nc_rubro'),
                ]
            );
            return RespuestaAPI::exito('Criterio no contable actualizado con éxito');
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al actualizar el criterio no contable: ' . $e->getMessage(), RespuestaAPI::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Elimina un criterio de supervisión no contable.
     */
    public function destroyNoContable($id)
    {
        try {
            DB::statement(
                'CALL sp_criterio_supervision_no_contable_eliminar(?)',
                [$id]
            );
            return RespuestaAPI::exito('Criterio no contable eliminado con éxito');
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al eliminar el criterio no contable: ' . $e->getMessage(), RespuestaAPI::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}