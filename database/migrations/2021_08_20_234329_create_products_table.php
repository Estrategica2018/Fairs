<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id()->comment('Código único del producto');
			$table->longText('name')->comment('Nombre del producto');
			$table->longText('description')->comment('Descripción del producto')->nullable();
			$table->bigInteger('category_id')->unsigned()->comment('Código de la categoría');
		    $table->foreign('category_id')->references('id')->on('categories')->nullable();
			$table->bigInteger('stand_id')->unsigned()->comment('Código del local comercial');
            $table->foreign('stand_id')->references('id')->on('stands');
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
        Schema::dropIfExists('products');
    }
}
