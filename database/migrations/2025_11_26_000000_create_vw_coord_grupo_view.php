<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateVwCoordGrupoView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE VIEW vw_coord_grupo AS
            SELECT
                cc.id_coordinador,
                g.id_grupo
            FROM
                carrera_coordinador cc
            JOIN
                grupo g ON cc.id_carrera = g.id_carrera
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS vw_coord_grupo");
    }
}
