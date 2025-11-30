<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarreraCoordinadorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carrera_coordinador', function (Blueprint $table) {
            $table->unsignedBigInteger('id_carrera');
            $table->unsignedBigInteger('id_coordinador');
            $table->primary(['id_carrera', 'id_coordinador']);

            $table->foreign('id_carrera')->references('id_carrera')->on('carrera')->onDelete('cascade');
            $table->foreign('id_coordinador')->references('id_coordinador')->on('coordinador')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carrera_coordinador');
    }
}
