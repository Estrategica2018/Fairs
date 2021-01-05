<?php

namespace App\Http\Controllers;

use App\Models\Fair;
use App\Models\Pavilion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FairController extends Controller
{
    //

    public function create(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'required',
            'halls_number' => 'required',
            'init_date' => 'required',
            'end_date' => 'required',
            'resources' => 'required',
            'location' => 'required',
            'social_media' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors(),
            ];
        }
        $data = $validator->validated();

        $fair = new Fair();
        $fair->name = $data['name'];
        $fair->description = $data['description'];
        $fair->halls_number = $data['halls_number'];
        $fair->init_date = $data['init_date'];
        $fair->end_date = $data['end_date'];
        $fair->resources = $data['resources'];
        $fair->location = $data['location'];
        $fair->social_media = $data['social_media'];
        $fair->save();

        for($i=0 ; $i < $data['halls_number']; $i++){

            $pavilion = new Pavilion();
            $pavilion->name = 'nombre';
            $pavilion->description = 'description';
            $pavilion->fair_id = $fair->id;
            $pavilion->save();

        }


        return [
            'success' => 201,
            'data' => $fair,
        ];

    }
}
