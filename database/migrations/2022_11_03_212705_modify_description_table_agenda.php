<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyDescriptionTableAgenda extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agendas', function (Blueprint $table) {
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE agendas MODIFY COLUMN description LONGTEXT not NULL');
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE agendas MODIFY COLUMN title LONGTEXT not NULL');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
