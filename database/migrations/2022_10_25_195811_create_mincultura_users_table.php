<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMinculturaUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mincultura_users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('documento_tipo');
            $table->longText('documento_numero');
            $table->longText('correo_electronico_adicional');
            $table->string('numero_celular');
            $table->string('pais_inscripcion');
            $table->string('zona_se_encuentra_en');
            $table->string('zona_se_encuentra_en_otra')->default('');
            $table->string('sexo_se_reconoce_como');
            $table->string('sexo_se_reconoce_como_otro')->default('');
            $table->string('sexo_registro_civil');
            $table->string('sexo_registro_civil_otro')->default('');
            $table->string('cultura_se_reconoce_como');
            $table->string('cultura_se_reconoce_como_otro')->default('');
            $table->string('discapacidad');
            $table->string('discapacidad_cual')->default('');
            $table->string('relacion_sector_rol');
            $table->string('relacion_sector_rol_otro')->default('');
            $table->string('institucion_vinculo');
            $table->longText('codigo_cbu')->nullable();
            $table->string('institucion_ubicacion');
            $table->string('escolaridad_nivel');
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
        Schema::dropIfExists('mincultura_users');
    }
}
