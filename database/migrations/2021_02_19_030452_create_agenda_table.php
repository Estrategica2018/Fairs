<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgendaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agendas', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->integer('duration_time');
            $table->longText('duration_day');
            $table->bigInteger('room_id')->nullable();
            //$table->foreign('room_id')->references('id')->on('rooms');
			$table->bigInteger('category_id')->unsigned()->comment('Código de la categoría');
            $table->foreign('category_id')->references('id')->on('categories');
			
            $table->longText('audience_config')->comment('Tipo de agenda, valores 1:publica, 2:lista correos 3:token');
            $table->string('token')->nullable()->comment('Csódigo de token de acceso');
            $table->string('zoom_code');
            $table->string('zoom_password');
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
        Schema::dropIfExists('agendas');
    }
}
