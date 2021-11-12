<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id()->comment('Código único de la categoría.');
			$table->string('type')->comment('Código del Tipo de categoría.');
            $table->string('name')->comment('Nombre de la categoría.');
            $table->longText('resources')->comment('Campo de recursos usado en el front.');
			$table->bigInteger('fair_id')->nullable()->unsigned()->comment('Código único de la feria.');
			$table->foreign('fair_id')->references('id')->on('fairs');
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
        Schema::dropIfExists('categories');
    }
}
