<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAudienceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audiences', function (Blueprint $table) {
            $table->id()->comment('Código único la asistencia');
            $table->bigInteger('agenda_id')->unsigned();
            $table->foreign('agenda_id')->references('id')->on('agendas');
			$table->string('email')->comment('Correo del usuario asistente a la reunión.');
			$table->bigInteger('user_id')->nullable()->unsigned()->comment('Código único del usuario asistente a la reunión.');
            $table->foreign('user_id')->references('id')->on('users');
			$table->string('token')->nullable()->comment('Código de validación de acceso');
            $table->string('attendance_days')->nullable()->comment('Días de asistencia del usuario a la conferencia');
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
        Schema::dropIfExists('audiences');
    }
}
