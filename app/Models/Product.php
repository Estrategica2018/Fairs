<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function stand() {

        return $this->belongsTo(Stand::class,'stand_id','id');
    }
    
    public function prices(){

        return $this->hasMany(ProductPrice::class,'product_id','id');
    }

    public function category() {

        return $this->belongsTo(Category::class,'category_id','id');
    }
 
}
