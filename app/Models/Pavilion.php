<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pavilion extends Model
{
    use HasFactory;

    public function fair(){

        return $this->belongsTo(Fair::class,'fair_id','id');
    }
    
    public function stands(){

        return $this->hasMany(Stand::class,'pavilion_id','id');
    }
}
