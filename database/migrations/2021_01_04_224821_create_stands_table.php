<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stands', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('merchant_id')->unsigned()->comment('Código de la entidad comercial.');
            $table->foreign('merchant_id')->references('id')->on('merchants');
            $table->bigInteger('pavilion_id')->unsigned()->comment('Código del pabellón asociado al stand');
            $table->foreign('pavilion_id')->references('id')->on('pavilions');
            $table->longText('resources')->comment('Campo de recursos usado en el front');
            $table->bigInteger('stand_type_id')->unsigned()->comment('Tipo de Stand');
            $table->foreign('stand_type_id')->references('id')->on('stand_types');
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
        Schema::dropIfExists('stands');
    }
}
