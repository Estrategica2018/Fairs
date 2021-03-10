<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fair extends Model
{
    use HasFactory;
	
    public function pavilions (){

        return $this->hasMany(Pavilion::class,'fair_id','id');
    }
}
