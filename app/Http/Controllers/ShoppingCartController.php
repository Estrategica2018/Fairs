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
            'user_id'=> 'required',
            'product_id'=> 'required',
            'product_price_id'=> 'required',
            'amount'=> 'required',
            'references_id'=> 'required',
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
        $shoppingCart->user_id = $data['user_id'];
        $shoppingCart->product_id = $data['product_id'];
        $shoppingCart->product_price_id = $data['product_price_id'];
        $shoppingCart->amount = $data['amount'];
        $shoppingCart->references_id = $data['references_id'];
        $shoppingCart->state = $data['state'];
        $shoppingCart->save();

        return [
            'success' => 201,
            'data' => $shoppingCart
        ];
    }
}
