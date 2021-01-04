<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchants', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Nombre de la razón comercial.');
            $table->string('nick')->comment('Alias dentro del aplicativo.');
            $table->longText('resources')->comment('Campo de recursos usado en el front');
            $table->longText('social_media')->comment('Accesos de redes sociales');
            $table->longText('location')->comment('Ubicación del comercio');
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
        Schema::dropIfExists('merchants');
    }
}
