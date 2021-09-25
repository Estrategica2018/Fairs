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
            'nick'=>'',
            'resources'=>'required',
            'social_media'=>'',
            'location'=>'',
            'name_contact'=>'',
            'email_contact'=>''
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
        $merchant->name_contact = $data['name_contact'];
        $merchant->email_contact = $data['email_contact'];

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
            'nick'=>'',
            'resources'=>'required',
            'social_media'=>'',
            'location'=>'',
            'name_contact'=>'',
            'email_contact'=>''
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
        $merchant->nick = $data['nick'];
        $merchant->resources = $data['resources'];
        $merchant->social_media = $data['social_media'];
        $merchant->location = $data['location'];
        $merchant->name_contact = $data['name_contact'];
        $merchant->email_contact = $data['email_contact'];

        $merchant->save();
        return [
            'success' => 201,
            'data' => $merchant,
        ];
    }

    public function to_list ($fair_id){
        
        $list = Merchant::all();
        return [
            'success' => 201,
            'data' => $list
        ];
    }

    public function get_merchant(Request $request){

        $validator = Validator::make($request->all(), [
            'id'=>'required',
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors(),
            ];
        }
        $data = $validator->validated();

        $merchant = Merchant::find($data['id']);
        if($merchant){
            return [
                'success' => 201,
                'data' => $merchant
            ];
        }else{
            return [
                'success' => false,
                'data' => 'No se encontró el comercio',
            ];
        }

    }
}
