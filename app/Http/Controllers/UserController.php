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
            'email'=>'required|email|unique:users,email',
            'password'=>'required',
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors(),
            ];
        }

        $data = $validator->validated();
        $fileName = null;
        if (request()->hasFile('image')) {
            $image_name = date('mdYHis') . uniqid() . $request->file('image')->getClientOriginalName();
            $path = base_path() . '/public/images_users';
            $request->file('image')->move($path,$image_name);
            $fileName = $path.$image_name;
        }

        $user = new User();
        $user->user_name = $data['user_name'];
        $user->name = $data['name'];
        $user->last_name = $data['last_name'];
        $user->email = $data['email'];
        $user->url_image = $fileName;
        if(isset($data['contact'])){ 
		  $user->contact = $data['contact'];
		}
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

    public function update(Request $request){

		$validator = Validator::make($request->all(), [
            'user_name'=>'',
            'name'=>'',
            'last_name'=>'',
            'email'=>'email|unique:users,email',
            'password'=>'',
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors(),
            ];
        }

        $data = $validator->validated();
		
        $fileName = null;
        if (request()->hasFile('image')) {
            $image_name = date('mdYHis') . uniqid() . $request->file('image')->getClientOriginalName();
            $path = base_path() . '/public/images_users';
            $request->file('image')->move($path,$image_name);
            $fileName = $path.$image_name;
        }

        $user = auth()->guard('api')->user();

        if(isset($data['user_name']))  $user->user_name = $data['user_name'];
        if(isset($data['name'])) $user->name = $data['name'];
        if(isset($data['last_name'])) $user->last_name = $data['last_name'];
        if(isset($data['email'])) $user->email = $data['email'];
        if($fileName) $user->url_image = $fileName;
        if(isset($data['contact'])){ 
            $user->contact = $data['contact'];
        }
        if(isset($data['password'])) $user->password = Hash::make($data['password']);

        $user->save();

        return [
            'success' => 201,
            'data' => $user,
        ];

    }
}
