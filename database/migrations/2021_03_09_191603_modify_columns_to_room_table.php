<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyColumnsToRoomTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //\Illuminate\Support\Facades\DB::statement('ALTER TABLE rooms MODIFY COLUMN pavilion_id bigint(20) NULL');
        //\Illuminate\Support\Facades\DB::statement('ALTER TABLE rooms MODIFY COLUMN stand_id bigint(20) NULL');
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agendas', function (Blueprint $table) {
            //
        });
    }
}
