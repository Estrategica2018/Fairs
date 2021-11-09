<?php

namespace App\Http\Controllers;

use App\Models\ShoppingCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShoppingCartController extends Controller
{
    //

    public function store (Request $request) {

        $validator = Validator::make($request->all(), [
            'fair_id'=> 'required',
            //'user_id'=> 'required',
            'product_id'=> 'required',
            'product_price_id'=> 'required',
            'amount'=> 'required',
            //'references_id'=> '',
            //'state'=> 'required',
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors(),
            ];
        }
        $data = $validator->validated();

        $shoppingCart = new ShoppingCart();
        $shoppingCart->fair_id = $data['fair_id'];
        
        $user = auth()->guard('api')->user();
        if (!$user) {
            return response()->json([
                'message' => 'La sesión ha caducado.'
            ], 411);
        }
        
        $shoppingCart->user_id = $user->id;
        
        $shoppingCart->product_id = $data['product_id'];
        $shoppingCart->product_price_id = $data['product_price_id'];
        $shoppingCart->amount = $data['amount'];
        $shoppingCart->references_id = ' ';
        $shoppingCart->state = 'N';//$data['state'];
        $shoppingCart->save();

        return [
            'success' => 201,
            'data' => $shoppingCart
        ];
    }

    public function list (Request $request) {

        $validator = Validator::make($request->all(), [
            'fair_id'=> 'required'
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors(),
            ];
        }
        $data = $validator->validated();
		
		$user = auth()->guard('api')->user();
        if (!$user) {
            return response()->json([
                'message' => 'La sesión ha caducado.'
            ], 411);
        }
		
        $shoppingCarts = ShoppingCart::with('productPrice.product')
		->where([["user_id",$user->id],["fair_id",$data['fair_id']]])
		->get();
        return [
            'success' => 201,
            'data' => $shoppingCarts
        ];
    }
}
