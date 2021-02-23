<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvitedSpeakerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invited_speakers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('agenda_id')->unsigned();
            $table->foreign('agenda_id')->references('id')->on('agendas');
            $table->bigInteger('speaker_id')->unsigned();
            $table->foreign('speaker_id')->references('id')->on('speakers');
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
        Schema::dropIfExists('invited_speakers');
    }
}
