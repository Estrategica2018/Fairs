<?php

namespace App\Http\Controllers;

use App\Models\OperatorUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OperatorUserController extends Controller
{
    //
    public function create(Request $request){

        $validator = Validator::make($request->all(), [
            'merchant_id'=>'required',
            'fair_id'=>'required',
            'user_id'=>'required',
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors(),
            ];
        }

        $data = $validator->validated();

        $operatorUser = new OperatorUser();
        $operatorUser->merchant_id = $data['merchant_id'];
        $operatorUser->fair_id = $data['fair_id'];
        $operatorUser->user_id = $data['user_id'];
        $operatorUser->save();

        return [
            'success' => 201,
            'data' => [
                'merchant'=>$operatorUser->merchant(),
                'pavilion'=>$operatorUser->pavilion(),
                'user'=>$operatorUser->user(),
            ],
        ];

    }


}
