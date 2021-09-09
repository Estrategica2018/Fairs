<?php

namespace App\Http\Controllers;

use App\Models\Fair;
use App\Models\RoleUserFair;
use App\Models\Speaker;
use App\Models\User;
use App\Notifications\AccountRegistration;
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
            'origin'=>'required',
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors(),
            ];
        }

        $data = $validator->validated();
         
        $fair = Fair::find($request->fair_id);
        if(!$fair)
            return [
                'success' => 400,
                'data' => 'Código de feria no existe',
            ];

        $user = new User();
        $user->user_name = $data['user_name'];
        $user->name = $data['name'];
        $user->last_name = $data['last_name'];
        $user->email = $data['email']; 
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

        try{
            $user->notify(  new AccountRegistration($user,$fair, $origin) );
        }catch (\Exception $exception){
            /*return [
                'success' => 400,
                'data' => $exception,
            ];*/
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
        if($user) {
            $data = $validator->validated();
            
            $fileName = null;
            if(isset($data['image'])){
                $image = $request->image;  // your base64 encoded
                $extension = explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];   // .jpg .png .pdf
                
                $fileName = '/images_users/'. date('mdYHis') . uniqid() . '_user_' . $user->id .'.' .$extension;
                 
                $image = str_replace('data:image/png;base64,', '', $image);
                $image = str_replace('data:image/jpeg;base64,', '', $image);
                $path = str_replace('\\\\', '/' , base_path());
              
                if(!Storage::exists($path.'/images_users')){
                    Storage::makeDirectory($path.'/images_users');
                }
                File::put($path . '/public' . $fileName, base64_decode($image));
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
        else {
            return response()->json(['message' => 'La sesión ha cadudcado.'], 403);
        }

    }

    public function activate_account (Request $request, $user_id){

        $user = User::where('id', $user_id)
            ->first();

        if (!$user)
            return response()->json([
                'message' => 'No fue posible encontrar el usuario.'
            ], 404);

        if($user->activate_account)
            return response()->json([
                'message' => 'Esta cuenta ya ha sido activada.'
            ], 404);


        $user->activate_account = true;
        $user->save();

        return response()->json([
            'data' => $user,
            'status' => 'successfull',
        ],200);
    }
}
