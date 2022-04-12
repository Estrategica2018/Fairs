<?php

namespace App\Http\Controllers;

use App\Models\Audience;
use Illuminate\Http\Request;

class AudienceController extends Controller
{
    //
    public function audience_user (Request $request, $agenda_id){

        $user = auth()->guard('api')->user();

        if(!$user){
            return response()->json([
                'message' => 'La sesión ha caducado',
                'data' => '',
                'status' => 'unsuccessfull',
            ],403);
        }

        $audience_user = Audience::where([
            ['user_id',$user->id],
            ['agenda_id',$agenda_id]
        ])->first();

        if(!$audience_user){
            return response()->json([
                'message' => 'No fue posible encontrar el usuario en la conferencia.',
                'data' => '',
                'status' => 'unsuccessfull'
            ], 404);
        }

        return response()->json([
            'message' => 'Datos consultados exitosamente.',
            'data' => [
                'user'=> $user,
                'audience' =>$audience_user
            ],
            'status' => 'successfull',
        ],200);

    }

    public function get_audience_users (Request $request, $agenda_id){

        $audience_users = Audience::where([
            ['agenda_id',$agenda_id]
        ])->first();

        if(!$audience_users){
            return response()->json([
                'message' => 'No fue posible encontrar la conferencia.'
            ], 404);
        }

        return response()->json([
            'message' => 'Datos consultados exitosamente.',
            'data' => $audience_users,
            'status' => 'successfull',
        ],200);

    }
}
