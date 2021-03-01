<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToAgendasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		\Illuminate\Support\Facades\DB::statement('ALTER TABLE agendas MODIFY COLUMN room_id bigint(20) NULL');
		\Illuminate\Support\Facades\DB::statement('ALTER TABLE agendas CHANGE duration_day start_at int(11) NOT NULL');
		
		
        Schema::table('agendas', function (Blueprint $table) {
            $table->string('timezone')->after('room_id');
			$table->longText('resources')->after('zoom_password')->nullable();
        });
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
