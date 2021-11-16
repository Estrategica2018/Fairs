<?php

namespace App\Http\Controllers;

use App\Models\ProductPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductPriceController extends Controller
{
    //
    public function create(Request $request){

        $validator = Validator::make($request->all(), [
            'fair_id'=>'required',
            'pavilion_id'=>'required',
            'stand_id'=>'required',
            'product_id'=>'required',
            'category_id'=>'',
            'resources'=>'required',
            'price'=>'',
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors(),
            ];
        }

        $data = $validator->validated();

        $productPrice = new ProductPrice();
        $productPrice->price = $data['price'];
        $productPrice->resources = $data['resources'];
        $productPrice->product_id = $data['product_id'];
        $productPrice->save();

        return [
            'success' => 201,
            'data' => $productPrice,
        ];
    }

    public function update(Request $request){

        $validator = Validator::make($request->all(), [
            'id'=>'required',
            'fair_id'=>'required',
            'pavilion_id'=>'required',
            'stand_id'=>'required',
            'product_id'=>'required',
            'category_id'=>'',
            'resources'=>'required',
            'price'=>'',
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors(),
            ];
        }

        $data = $validator->validated();

        $productPrice = ProductPrice::find($data['id']);
        $productPrice->product_id = $data['product_id'];
        $productPrice->price = $data['price'];
        $productPrice->resources = $data['resources'];
        $productPrice->save();

        return [
            'success' => 201,
            'data' => $productPrice,
        ];
    }

    public function delete(Request $request){

        $validator = Validator::make($request->all(), [
            'id'=>'required'
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors(),
            ];
        }

        $data = $validator->validated();

        $productPrice =  ProductPrice::find($data['id']);
        $productPrice->delete();
        
        return [
            'success' => 201
        ];
    }
}
