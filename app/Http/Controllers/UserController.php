<?php

namespace App\Http\Controllers;

use App\Models\RoleUserFair;
use App\Models\Speaker;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use File;

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
            'role_id'=>'required',
            'fair_id'=>'required',

        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors(),
            ];
        }

        $data = $validator->validated();
        $fileName = null;
        //if (request()->hasFile('image')) {
            //dd(request()->hasFile('image'));
            //$image_name = date('mdYHis') . uniqid() . $request->file('image')->getClientOriginalName();
            //$path = base_path() . '/public/images_users';
            //$request->file('image')->move($path,$image_name);
            //$fileName = $path.$image_name;
        //}
        $fileName = null;
        if(isset($data['image'])){
            $image = $request->image;  // your base64 encoded
            $extension = explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];   // .jpg .png .pdf
            $path = 'images_users';
            $fileName = $path . '/' . date('mdYHis') . uniqid() . '_user_' . $user->id .'.' .$extension;
            if(!Storage::exists($path)){
                Storage::makeDirectory($path);
            }
            
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace('data:image/jpeg;base64,', '', $image);
            $path = str_replace('\\\\', '/' , base_path());
            File::put($path. '/public/' . $fileName, base64_decode($image));
            $fileName = 'http://127.0.0.1:8000/' . $fileName;
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

        $user_rol_fair = new RoleUserFair();
        $user_rol_fair->user_id = $user->id;
        $user_rol_fair->role_id = $data['role_id'];
        $user_rol_fair->fair_id = $data['fair_id'];
        $user_rol_fair->save();

        if($data['role_id'] == 6){
            $speaker = new Speaker();
            $speaker->user_id = $user->id;
            $speaker->description = $request->speaker["description"];
            $speaker->title = $request->speaker["title"];
            $speaker->resources = $request->speaker["resources"];
            $speaker->save();
        }

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
            'image'=>'',
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors(),
            ];
        }


        $user = auth()->guard('api')->user();
        $data = $validator->validated();
        
        $fileName = null;
        if(isset($data['image'])){
            $image = $request->image;  // your base64 encoded
            $extension = explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];   // .jpg .png .pdf
            $path = 'images_users';
            $fileName = $path . '/' . date('mdYHis') . uniqid() . '_user_' . $user->id .'.' .$extension;
            if(!Storage::exists($path)){
                Storage::makeDirectory($path);
            }
            
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace('data:image/jpeg;base64,', '', $image);
            $path = str_replace('\\\\', '/' , base_path());
            File::put($path. '/public/' . $fileName, base64_decode($image));
            $fileName = 'http://127.0.0.1:8000/' . $fileName;
        }
        else {
           $fileName = $user->url_image;
        }
        
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
