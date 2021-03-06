<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Speaker extends Model
{
    use HasFactory;

    protected $table = 'speakers';

    public function user () {

        return $this->belongsTo(User::class,'user_id','id');
    }


    public function agenda (){

        return $this->belongsToMany(Agendas::class,'invited_speakers','speaker_id','agenda_id');

    }
}
