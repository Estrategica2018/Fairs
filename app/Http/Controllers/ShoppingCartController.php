<?php

namespace App\Http\Controllers;

use App\Models\ShoppingCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShoppingCartController extends Controller
{
    //

    public function store (Request $request) {
        
        $stateNew = 'N';

        $validator = Validator::make($request->all(), [
            'fair_id'=> 'required',
            'product_id'=> '',
            'product_price_id'=> '',
            'amount'=> 'required',
            'agenda_id'=> '',
            'detail'=> '',
            'price'=> '',
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors(),
            ];
        }
        $data = $validator->validated();
        
        if(isset($data['agenda_id'])) {
           $shoppingCart = ShoppingCart::
           where([["agenda_id",$data['agenda_id']],["state",$stateNew]])
           ->first();
        }
        /*else if(isset($data['product_price_id'])) {
           $shoppingCart = ShoppingCart::
           where([ ["product_id",$data['product_id']],["product_price_id",$data['product_price_id']], ["state",$stateNew]])
           ->first();
        }*/
        
        if(!$shoppingCart) {
          $shoppingCart = new ShoppingCart();
        }
        
        $shoppingCart->fair_id = $data['fair_id'];
        
        $user = auth()->guard('api')->user();
        if (!$user) {
            return response()->json([
                'message' => 'La sesión ha caducado.'
            ], 411);
        }
        
        $shoppingCart->user_id = $user->id;
        if(isset($data['product_id']))
            $shoppingCart->product_id = $data['product_id'];
        if(isset($data['product_price_id']))
            $shoppingCart->product_price_id = $data['product_price_id'];
        if(isset($data['agenda_id']))
            $shoppingCart->agenda_id = $data['agenda_id'];
        if(isset($data['detail']))
            $shoppingCart->detail = $data['detail'];
        if(isset($data['price']))
            $shoppingCart->price = $data['price'];
        $shoppingCart->amount = $data['amount'];
        $shoppingCart->references_id = ' ';
        $shoppingCart->state = 'N';//$data['state'];
        $shoppingCart->save();

        return [
            'success' => 201,
            'data' => $shoppingCart
        ];
    }

    public function list (Request $request, $fair_id) {

        $validator = Validator::make($request->all(), [
            'fair_id'=> ''
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
        
        $shoppingCarts = ShoppingCart::with(['productPrice.product','agenda.category'])
        ->where([['state','N'],['user_id',$user->id], ['fair_id',$fair_id]])
        ->get();
        return [
            'success' => 201,
            'data' => $shoppingCarts
        ];
    }

    public function find(Request $request, $fair_id, $reference_id){

        $shoppingCart = ShoppingCart::with(['productPrice.product','agenda'])
        //->find($data['id']);
        ->where([['fair_id',$fair_id],['references_id',$reference_id]])->get();
        if(!$shoppingCart)
            return [
                'success' => 400,
                'data' => 'Código de carrito de compras no existe',
            ];
        return response()->json([
            'data' => $shoppingCart,
            'message', 'Registro encontrado',
            'success' => true,
        ], 201);
    }

    public function update(Request $request){

        $validator = Validator::make($request->all(), [
            'id'=> 'required'
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors(),
            ];
        }
        
        $user = auth()->guard('api')->user();
        if (!$user) {
            return response()->json([
                'message' => 'La sesión ha caducado.'
            ], 411);
        }

        $data = $validator->validated();
        $shoppingCart = ShoppingCart::where([['id',$data['id']],['user_id',$user->id]])->first();
        
        if(!$shoppingCart)
            return [
                'success' => 400,
                'data' => 'Código de carrito de compras no existe',
            ];

        if(isset($request['product_id']))
            $shoppingCart->product_id = $request['product_id'];
        if(isset($request['product_price_id']))
            $shoppingCart->product_price_id = $request['product_price_id'];
        if(isset($request['agenda_id']))
            $shoppingCart->agenda_id = $request['agenda_id'];
        if(isset($request['amount']))
            $shoppingCart->amount = $request['amount'];
        if(isset($request['references_id']))
            $shoppingCart->references_id = $request['references_id'];;
        if(isset($request['state']))
           $shoppingCart->state = $request['state'];
        $shoppingCart->save();

        return response()->json([
            'data' => $shoppingCart,
            'message', 'Registro actualizado',
            'success' => true,
        ], 201);
    }
}
