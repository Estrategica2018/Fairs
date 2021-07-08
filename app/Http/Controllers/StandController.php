<?php

namespace App\Http\Controllers;

use App\Models\Stand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StandController extends Controller
{
    //

    public function create(Request $request){

        $validator = Validator::make($request->all(), [
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

        $stand = new Stand();
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

    public function to_list(Request $request){
        
        $validator = Validator::make($request->all(), [
            'fair_id'=>'required'
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors(),
            ];
        }

        $data = $validator->validated();

        $stands = Stand::with(['pavilion' => function ($query) use ($request) {
            $query->where('id',$request->pavilion_id);
        }])->get();

        return [
            'success' => 201,
            'data' => $stands,
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
