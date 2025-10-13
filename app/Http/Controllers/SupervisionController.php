<?php

namespace App\Http\Controllers;

use App\Models\CriterioSupervisionContableModelo;
use App\Models\CriterioSupervisionNoContableModelo;
use App\Utils\RespuestaAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SupervisionController extends Controller
{
    // --- Criterios Contables ---

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

    public function showContable($id)
    {
        $criterio = CriterioSupervisionContableModelo::find($id);
        if ($criterio) {
            return RespuestaAPI::exito('Criterio de supervisión contable obtenido con éxito', $criterio);
        } else {
            return RespuestaAPI::error('Criterio de supervisión contable no encontrado', RespuestaAPI::HTTP_NOT_FOUND);
        }
    }

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

    // --- Criterios No Contables ---

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

    public function showNoContable($id)
    {
        $criterio = CriterioSupervisionNoContableModelo::find($id);
        if ($criterio) {
            return RespuestaAPI::exito('Criterio de supervisión no contable obtenido con éxito', $criterio);
        } else {
            return RespuestaAPI::error('Criterio de supervisión no contable no encontrado', RespuestaAPI::HTTP_NOT_FOUND);
        }
    }

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
