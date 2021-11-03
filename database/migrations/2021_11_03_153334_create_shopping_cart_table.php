<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShoppingCartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('shopping_carts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('fair_id')->unsigned();
            $table->foreign('fair_id')->references('id')->on('fairs');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->bigInteger('product_id')->unsigned()->comment('Indice con la tabla products');
            $table->foreign('product_id')->references('id')->on('products');
            $table->bigInteger('product_price_id')->unsigned()->comment('Indice con la tabla product_prices');
            $table->foreign('product_price_id')->references('id')->on('product_prices');
            $table->integer('amount');
            $table->longText('references_id')->comment('Codigo de referencia para gateway de pago');
            $table->string('state')->nullable()->comment('Estado de carrito de compras: A: Anulado, C: Cancelado, R: Rechazado, P: pagado, N: nuevo');
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
        Schema::dropIfExists('shopping_carts');
    }
}
