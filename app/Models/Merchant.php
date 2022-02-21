<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    use HasFactory;

    public function stand () {

       return $this->hasMany(Stand::class,'merchant_id','id');
    }


}
