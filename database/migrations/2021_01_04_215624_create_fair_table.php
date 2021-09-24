<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFairTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fairs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->comment('Nombre del festival, evento o campaña promocional.');
            $table->integer('halls_number')->unsigned();
            $table->date('init_date')->comment('Descripción del rol del festival, evento o campaña promocional.');
            $table->date('end_date')->comment('Fecha de inicio del festival, evento o campaña.');
            $table->longText('resources')->comment('Recursos de visualización en el Frontend.');
            $table->longText('location')->comment('Campo de recursos usado en el front');
            $table->longText('social_media')->comment('Ubicación del local comercial');
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
        Schema::dropIfExists('fairs');
    }
}
