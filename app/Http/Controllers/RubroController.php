<?php

namespace App\Http\Controllers;

use App\Utils\RespuestaAPI;
use Illuminate\Support\Facades\DB;

class RubroController extends Controller
{
    public function index()
    {
        try {
            $rubros = DB::select('SELECT * FROM cat_rubro_alumno_docente');
            return RespuestaAPI::exito('Listado de rubros', $rubros);
        } catch (\Illuminate\Database\QueryException $e) {
            return RespuestaAPI::error('Error al obtener los rubros: ' . $e->getMessage(), 500);
        }
    }

    public function indexContable()
    {
        try {
            $rubros = DB::select('SELECT * FROM cat_rubro');
            return RespuestaAPI::exito('Listado de rubros contables', $rubros);
        } catch (\Illuminate\Database\QueryException $e) {
            return RespuestaAPI::error('Error al obtener los rubros contables: ' . $e->getMessage(), 500);
        }
    }

    public function indexNoContable()
    {
        try {
            $rubros = DB::select('SELECT * FROM cat_rubro_no_contable');
            return RespuestaAPI::exito('Listado de rubros no contables', $rubros);
        } catch (\Illuminate\Database\QueryException $e) {
            return RespuestaAPI::error('Error al obtener los rubros no contables: ' . $e->getMessage(), 500);
        }
    }
}
