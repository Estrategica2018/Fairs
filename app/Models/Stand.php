<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stand extends Model
{
    use HasFactory;

    public function merchant(){

        return $this->belongsTo(Merchant::class,'merchant_id','id');
    }

    public function pavilion(){

        return $this->belongsTo(Pavilion::class,'pavilion_id','id');
    }
}
