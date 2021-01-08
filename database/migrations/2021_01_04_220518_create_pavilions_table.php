<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePavilionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pavilions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Código único del pabellón');
            $table->string('description')->comment('Nombre del pabellón.');
            $table->bigInteger('fair_id')->unsigned()->comment('Descripción del pabellón');
            $table->foreign('fair_id')->references('id')->on('fairs');
            $table->integer('stands_number')->comment('Código único del festival, evento o campaña.')->nullable();
            $table->integer('rooms_number')->comment('Número de stands en el pabellón')->nullable();
            $table->longText('resources')->comment('Número de salas del pabellón')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pavilions');
    }
}
