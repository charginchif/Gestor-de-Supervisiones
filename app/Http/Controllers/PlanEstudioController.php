<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Utils\RespuestaAPI;
use Illuminate\Validation\ValidationException;

class PlanEstudioController extends Controller
{
    public function index($id_carrera)
    {
        // Primero, verificar si la carrera existe
        $carrera = DB::table('carrera')->where('id_carrera', $id_carrera)->first();
        if (!$carrera) {
            return RespuestaAPI::error('La carrera no existe', 404);
        }

        $planEstudio = DB::table('vw_admin_plan_estudio')->where('id_carrera', $id_carrera)->get();

        if ($planEstudio->isEmpty()) {
            return RespuestaAPI::error('No se encontró un plan de estudio para la carrera especificada', 204);
        }

        $grouped = [];
        foreach ($planEstudio as $item) {
            $key = $item->id_carrera . '-' . $item->id_modalidad;
            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'id_carrera' => $item->id_carrera,
                    'id_modalidad' => $item->id_modalidad,
                    'materias' => [],
                ];
            }
            $grouped[$key]['materias'][] = [
                'id_materia' => $item->id_materia,
                'id_cat_nivel' => $item->id_cat_nivel,
            ];
        }

        return RespuestaAPI::exito('Éxito', array_values($grouped));
    }

    public function indexAll()
    {
        $planEstudio = DB::table('vw_admin_plan_estudio')->get();

        $grouped = [];
        foreach ($planEstudio as $item) {
            $key = $item->id_carrera . '-' . $item->id_modalidad;
            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'id_carrera' => $item->id_carrera,
                    'id_modalidad' => $item->id_modalidad,
                    'materias' => [],
                ];
            }
            $grouped[$key]['materias'][] = [
                'id_materia' => $item->id_materia,
                'id_cat_nivel' => $item->id_cat_nivel,
            ];
        }

        return RespuestaAPI::exito('Éxito', array_values($grouped));
    }

    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'id_carrera' => 'required|integer',
                'id_modalidad' => 'required|integer',
                'materias' => 'required|array',
                'materias.*.id_materia' => 'required|integer',
                'materias.*.id_cat_nivel' => 'required|integer',
            ]);

            $json_data = json_encode($request->all());

            $resultado = DB::select('CALL sp_plan_estudio_crear(?)', [$json_data]);

            return RespuestaAPI::exito('Plan de estudio creado con éxito', $resultado, 201);
        } catch (ValidationException $e) {
            return RespuestaAPI::error('Datos de entrada no válidos', 422, $e->errors());
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al crear el plan de estudio: ' . $e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id_plan_estudio)
    {
        try {
            $this->validate($request, [
                'id_carrera' => 'integer|nullable',
                'id_modalidad' => 'integer|nullable',
                'id_materia' => 'integer|nullable',
                'id_cat_nivel' => 'integer|required_with:id_materia',
            ]);

            // Check if at least one field is present
            if (!$request->hasAny(['id_carrera', 'id_modalidad', 'id_materia'])) {
                // Using a custom message, but this will be caught by the ValidationException handler
                throw ValidationException::withMessages(['fields' => 'Debe proporcionar al menos un campo para actualizar (id_carrera, id_modalidad, id_materia).']);
            }

            $resultado = DB::select(
                'CALL sp_plan_estudio_actualizar(?, ?, ?, ?, ?)',
                [
                    $id_plan_estudio,
                    $request->input('id_carrera'),
                    $request->input('id_modalidad'),
                    $request->input('id_materia'),
                    $request->input('id_cat_nivel'),
                ]
            );

            return RespuestaAPI::exito('Plan de estudio actualizado con éxito', $resultado);
        } catch (ValidationException $e) {
            return RespuestaAPI::error('Datos de entrada no válidos', 422, $e->errors());
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al actualizar el plan de estudio: ' . $e->getMessage(), 500);
        }
    }

    public function destroy($id_plan_estudio)
    {
        try {
            $resultado = DB::select('CALL sp_plan_estudio_eliminar(?)', [$id_plan_estudio]);
            
            return RespuestaAPI::exito('Plan de estudio eliminado con éxito', $resultado);
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al eliminar el plan de estudio: ' . $e->getMessage(), 500);
        }
    }

    public function destroyMateria(Request $request)
    {
        try {
            $this->validate($request, [
                'id_plan_estudio' => 'required|integer',
                'id_cat_nivel'    => 'required|integer',
                'id_materia'      => 'required|integer',
            ]);

            DB::select('CALL sp_plan_estudio_eliminar_materia(?, ?, ?)', [
                $request->id_plan_estudio,
                $request->id_cat_nivel,
                $request->id_materia
            ]);
            
            return RespuestaAPI::exito('Materia eliminada del plan de estudio con éxito');
        } catch (ValidationException $e) {
            return RespuestaAPI::error('Datos de entrada no válidos', 422, $e->errors());
        } catch (\Exception $e) {
            return RespuestaAPI::error('Error al eliminar la materia del plan de estudio: ' . $e->getMessage(), 500);
        }
    }
}
