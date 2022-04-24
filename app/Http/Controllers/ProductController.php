<?php

namespace App\Http\Controllers;

use App\Models\Stand;
use App\Models\Product;
use App\Models\ProductPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    //
    public function create(Request $request){

        $validator = Validator::make($request->all(), [
            'fair_id'=>'required',
            'pavilion_id'=>'required',
            'category_id'=>'required',
            'stand_id'=>'required',
            'name'=>'required',
            'description'=>'required',
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

        $product = new Product();
        $product->name = $data['name'];
        $product->description = $data['description'];
        $product->category_id = $data['category_id'];
        $product->stand_id = $data['stand_id'];
        $product->price = $data['price'];
        $product->resources = $data['resources'];
        $product->save();
        
        $productPrice = new ProductPrice();
        $productPrice->resources = '{"images":[{"url_image":"https://dummyimage.com/114x105/EFEFEF/000.png"}]}';
        $productPrice->product_id = $product->id;
        $productPrice->price = 0;
        $productPrice->save();

        return [
            'success' => 201,
            'data' => $product,
        ];
    }

    public function update(Request $request){

        $validator = Validator::make($request->all(), [
            'id'=>'required',
            'fair_id'=>'required',
            'pavilion_id'=>'required',
            'category_id'=>'',
            'stand_id'=>'required',
            'name'=>'required',
            'description'=>'required',
            'price'=>'',
            'resources'=>'required',
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors(),
            ];
        }

        $data = $validator->validated();

        $product = Product::find($data['id']);
        $product->name = $data['name'];
        $product->description = $data['description'];
        $product->resources = $data['resources'];
        $product->category_id = $data['category_id'] ? $data['category_id'] : $product->category_id = 1;
        $product->stand_id = $data['stand_id'];
        $product->price = $data['price'];
        $product->save();
        
        return [
            'success' => 201,
            'data' => $product,
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
        $standId = $data['stand_id'];
        $pavilionId = $data['pavilion_id'];

        /*$product = Product::with(['pavilion' => function ($query) use ($request) {
            $query->where('id',$request->pavilion_id);
        }])->get();*/
        
        if(isset($data['product_id']) && $data['product_id'] != null && $data['product_id'] != 'null'){
           
           $products = Product::
           with('prices','stand','category')
           ->where('id', '=', $data['product_id'])
           ->get();        
        }
        else if(isset($data['stand_id']) && $data['stand_id'] != null && $data['stand_id'] != 'null'){
           $products = Product::with('prices','category')
            ->with(['stand'=>function($query) {
                $query->select('id','pavilion_id');
            }])
            ->whereHas('stand', function($q) use($standId) {
               $q->where('id', '=', $standId);
            })->get();
        }
        else if(isset($data['pavilion_id']) && $data['pavilion_id'] != null && $data['pavilion_id'] != 'null'){
           $products = Product::with('prices','stand','category')
            ->whereHas('stand', function($q) use($pavilionId) {
               $q->where('pavilion_id', '=', $pavilionId);
            })->get();
        }
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

        $product =  Product::find($data['id']);
        $product->delete();
        
        return [
            'success' => 201
        ];
    }
}
