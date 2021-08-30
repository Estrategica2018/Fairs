<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_prices', function (Blueprint $table) {
            $table->id()->comment('Código único del precio de producto');
			$table->longText('resources')->comment('Imágenes y atributos del producto')->nullable();
			$table->bigInteger('price')->unsigned()->comment('Precio del producto');
			$table->bigInteger('product_id')->unsigned()->comment('Código del producto');
            $table->foreign('product_id')->references('id')->on('products');
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
        Schema::dropIfExists('product_prices');
    }
}
