<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToAudiencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audiences', function (Blueprint $table) {
            $table->boolean('check')->after('user_id')->default('1')->comment('Estado del invitado en la reunión 0: Inactivo 1: Activo.');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('audiences', function (Blueprint $table) {
            //
        });
    }
}
