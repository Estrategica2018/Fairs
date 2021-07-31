<?php

namespace App\Http\Controllers;

use App\Models\Pavilion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class PavilionController extends Controller
{
    //
    public function create(Request $request){

        $validator = Validator::make($request->all(), [

            'name'=>'required',
            'description'=>'required',
            'fair_id'=>'required',
            'stands_number'=>'',
            'rooms_number'=>'',
            'resources'=>'required',
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors(),
            ];
        }

        $data = $validator->validated();

        $pavilion = new Pavilion();
        $pavilion->name = $data['name'];
        $pavilion->description = $data['description'];
        $pavilion->fair_id = $data['fair_id'];
        if(isset($data['stands_number'])) $pavilion->stands_number = $data['stands_number'];
        if(isset($data['rooms_number'])) $pavilion->rooms_number = $data['rooms_number'];
        $pavilion->resources = $data['resources'];
        $pavilion->save();

        return [
            'success' => 201,
            'data' => $pavilion,
        ];
    }

    public function find_by_fair(Request $request){
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
        return [
            'success' => 201,
            'data' => Pavilion::with('fair','stands.merchant')->where('fair_id',$data['fair_id'])->get(),
        ];
    }
    
    public function update(Request $request, $pavilionId){

        $validator = Validator::make($request->all(), [
            'fair_id'=>'required',
            'name'=>'',
            'description'=>'',
            'resources'=>'',
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors(),
            ];
        }

        $data = $validator->validated();

        $pavilion = Pavilion::find($pavilionId);
        if(isset($data['name'])) $pavilion->name = $data['name'];
        if(isset($data['description'])) $pavilion->description = $data['description'];
        if(isset($data['resources']))  $pavilion->resources = $data['resources'];
        $pavilion->save();

        return [
            'success' => 201,
            'data' => $pavilion,
        ];
    }
    
    public function delete(Request $request, $pavilionId){

        $validator = Validator::make($request->all(), [
            'pavilion_id'=>'required'
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors(),
            ];
        }

        $data = $validator->validated();

        $pavilion = Pavilion::find($pavilionId);
        $pavilion->delete();

        return [
            'success' => 201,
            'data' => $pavilion,
        ];
    }


}
