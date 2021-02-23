<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agendas extends Model
{
    use HasFactory;

    public function room (){

        return $this->hasOne(Room::class,'id','room_id');
    }

    public function invited_speakers (){

        return $this->hasMany(InvitedSpeaker::class,'agenda_id','id');
    }

}
