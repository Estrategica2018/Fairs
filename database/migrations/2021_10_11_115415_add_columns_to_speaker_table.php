<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToSpeakerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('speakers', function (Blueprint $table) {
            //
            $table->string('profile_picture')->nullable();
            $table->string('company_logo')->nullable();
            $table->longText('description_one')->nullable();
            $table->longText('description_two')->nullable();
            $table->string('position')->nullable();
            $table->string('profession')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('speakers', function (Blueprint $table) {
            //
        });
    }
}
