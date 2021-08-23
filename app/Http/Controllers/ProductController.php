<?php

namespace App\Http\Controllers;

use App\Models\Stand;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    //
    public function create(Request $request){

        $validator = Validator::make($request->all(), [
            'fair_id'=>'required',
            'pavilion_id'=>'required',
			'category_id'=>'',
			'stand_id'=>'required',
			'name'=>'required',
			'description'=>'required',
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors(),
            ];
        }

        $data = $validator->validated();

        $product = new Product();
        $product->name = $data['name'];
		$product->description = $data['description'];
        if(isset($data['category_id'])) $product->category_id = $data['category_id'];
        $product->stand_id = $data['stand_id'];
        $product->save();

        return [
            'success' => 201,
            'data' => $product,
        ];
    }

    public function update(Request $request){

        $validator = Validator::make($request->all(), [
            'id'=>'required',
            'merchant_id'=>'required',
            'pavilion_id'=>'required',
            'resources'=>'required',
            'stand_type_id'=>'required',
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors(),
            ];
        }

        $data = $validator->validated();

        $stand =  Stand::find($data['id']);
        $stand->merchant_id = $data['merchant_id'];
        $stand->pavilion_id = $data['pavilion_id'];
        $stand->resources = $data['resources'];
        $stand->stand_type_id = $data['stand_type_id'];
        $stand->save();

        return [
            'success' => 201,
            'data' => $stand,
        ];
    }

    public function findBy(Request $request){
        
        $validator = Validator::make($request->all(), [
            'fair_id'=>'required',
			'pavilion_id'=>'',
			'stand_id'=>'',
			'product_id'=>''
			
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors(),
            ];
        }

        $data = $validator->validated();

        /*$product = Product::with(['pavilion' => function ($query) use ($request) {
            $query->where('id',$request->pavilion_id);
        }])->get();*/
		$products = Product::with('prices')->get();

        return [
            'success' => 201,
            'data' => $products,
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

        $stand =  Stand::find($data['id']);
        $stand->delete();
        
        return [
            'success' => 201
        ];
    }
}
