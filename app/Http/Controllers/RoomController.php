<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoomController extends Controller
{
    //
    public function create (Request $request){

        $validator = Validator::make($request->all(), [
            'name'=>'required',
            'pavilion_id'=>'required',
            'stand_id'=>'required'
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors(),
            ];
        }

        $data = $validator->validated();

        $room = new Room();
        $room->name = $data['name'];
        $room->pavilion_id = $data['pavilion_id'];
        $room->stand_id = $data['stand_id'];
        $room->save();

        return [
            'success' => 201,
            'data' => $room,
        ];
    }
}
