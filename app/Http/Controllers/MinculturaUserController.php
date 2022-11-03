<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MinculturaUser;

class MinculturaUserController extends Controller
{
    public function index(Request $request){

        $user = auth()->guard('api')->user();

        $mincultura = MinculturaUser::where('user_id',$user->id)->first();

        return [
            'success' => 201,
            'data' => $mincultura
        ];
    }

}
