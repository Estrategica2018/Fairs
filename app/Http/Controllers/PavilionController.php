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
            'stands_number'=>'required',
            'rooms_number'=>'required',
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
        $pavilion->stands_number = $data['stands_number'];
        $pavilion->rooms_number = $data['rooms_number'];
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
            'data' => Pavilion::with('fair')->where('fair_id',$data['fair_id'])->get(),
        ];

    }


}
