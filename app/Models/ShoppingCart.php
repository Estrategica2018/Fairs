<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingCart extends Model
{
    use HasFactory;
	
    public function productPrice() {

        return $this->belongsTo(productPrice::class,'product_price_id','id');
    }

    public function agenda() {

        return $this->belongsTo(Agendas::class,'agenda_id','id');

    }

}
