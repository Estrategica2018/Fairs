<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audience extends Model
{
    use HasFactory;
    
	public function user() {

        return $this->belongsTo(User::class,'user_id','id');
    }

    public function agenda() {

        return $this->belongsTo(Agendas::class,'agenda_id','id');
    }
	
}


