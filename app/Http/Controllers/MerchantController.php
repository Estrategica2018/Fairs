<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MerchantController extends Controller
{
    //
    public function create (Request $request){

        $validator = Validator::make($request->all(), [

            'name'=>'required',
            'nick'=>'required',
            'resources'=>'required',
            'social_media'=>'required',
            'location'=>'required'
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors(),
            ];
        }

        $data = $validator->validated();

        $merchant = new Merchant();
        $merchant->name = $data['name'];
        $merchant->nick = $data['nick'];
        $merchant->resources = $data['resources'];
        $merchant->social_media = $data['social_media'];
        $merchant->location = $data['location'];

        $merchant->save();

        return [
            'success' => 201,
            'data' => $merchant,
        ];

    }

    public function update(Request $request){

        $validator = Validator::make($request->all(), [
            'id'=>'required',
            'name'=>'required',
            'nick'=>'required',
            'resources'=>'required',
            'social_media'=>'required',
            'location'=>'required'
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors(),
            ];
        }
        $data = $validator->validated();

        $merchant = Merchant::find($data['id']);

        $merchant->name = $data['name'];
        $merchant->name = $data['nick'];
        $merchant->name = $data['resources'];
        $merchant->name = $data['social_media'];
        $merchant->name = $data['location'];

        $merchant->save();
        return [
            'success' => 201,
            'data' => $merchant,
        ];
    }

    public function to_list (){
        return [
            'success' => 201,
            'data' => Merchant::all(),
        ];
    }

}
