<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleUserFair extends Model
{
    use HasFactory;

    protected $table = 'role_user_fairs';

    public function user (){

        return $this->belongsTo(User::class,'user_id','id');
    }
}
