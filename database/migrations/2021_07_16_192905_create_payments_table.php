<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
			$table->bigInteger('user_id')->unsigned()->comment('Código del usuario que realiza la solicitud de pago');
            $table->foreign('user_id')->references('id')->on('users');
			$table->string('type_order')->comment('Código del tipo de orden: 1.Pago por toda la feria, 2.pago por evento, 3.pago por producto');
			$table->string('code_item_order')->comment('Código del elemento a pagar en la orden.. Código de la feria o código del evento o código del producto');
			$table->string('payment_status')->comment('Código del estado de pago. 1.Pendiente, 2.Rechazado, 3.Aprobado');
			$table->date('payment_date')->nullable()->comment('Fecha de pago realizado');
			$table->date('reference')->comment('Referencia de pago generada');
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
        Schema::dropIfExists('payments');
    }
}
