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
	
    public function role_user_fairs (){

        return $this->belongsToMany(Role::class,'role_user_fairs','fair_id','role_id');

    }
}
