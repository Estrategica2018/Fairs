<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //

    public function create(Request $request){

        $validator = Validator::make($request->all(), [
            'user_name'=>'required',
            'name'=>'required',
            'last_name'=>'required',
            'email'=>'required',
            'url_image'=>'required',
            'contact'=>'required',
            'password'=>'required'
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors(),
            ];
        }

        $data = $validator->validated();

        $user = new User();
        $user->user_name = $data['user_name'];
        $user->name = $data['name'];
        $user->last_name = $data['last_name'];
        $user->email = $data['email'];
        $user->url_image = $data['url_image'];
        $user->contact = $data['contact'];
        $user->password = Hash::make($data['password']);

        $user->save();

        return [
            'success' => 201,
            'data' => $user,
        ];

    }

    public function to_list(){

        return [
            'success' => 201,
            'data' => User::all(),
        ];

    }
}
