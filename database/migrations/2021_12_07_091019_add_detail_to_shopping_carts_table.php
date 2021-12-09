<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDetailToShoppingCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shopping_carts', function (Blueprint $table) {
            $table->longText('price')->after('amount')->nullable()->comment('Precio de venta');
            $table->longText('detail')->after('amount')->nullable()->comment('Atributos seleccionados del producto');
        });
        Schema::table('payments', function (Blueprint $table) {
            $table->longText('detail')->after('reference')->nullable()->comment('Atributos seleccionados del producto');
            $table->longText('price')->after('reference')->nullable()->comment('Precio de venta');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shopping_carts', function (Blueprint $table) {
            //
        });
    }
}
