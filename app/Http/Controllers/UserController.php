<?php

namespace App\Http\Controllers;

use App\Models\ConfirmAccount;
use App\Models\Fair;
use App\Models\RoleUserFair;
use App\Models\Speaker;
use App\Models\User;
use App\Notifications\AccountRegistration;
use App\Notifications\SuccessfulRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
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
            Notification::route('mail', $data['email'])
                ->notify(new SuccessfulRegistration());

        }catch (\Exception $e){
            return response()->json(['message' => 'Error enviando el correo electrónico .'.' '.$e], 403);
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
            'url_image'=>'',
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
            $app_url = env('APP_URL', 'http://127.0.0.1:8000');
            
            if(isset($data['image'])){
                $image = $request->image;  // your base64 encoded
                $extension = explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];   // .jpg .png .pdf
                
                $fileName = 'images_users/'. date('mdYHis') . uniqid() . '_user_' . $user->id .'.' .$extension;
                 
                $image = str_replace('data:image/png;base64,', '', $image);
                $image = str_replace('data:image/jpeg;base64,', '', $image);
                $path = str_replace('\\\\', '/' , base_path());
              
                if(!Storage::exists($path.'/images_users')){
                    Storage::makeDirectory($path.'/images_users');
                }
                File::put($path . '/public/' . $fileName, base64_decode($image));
                
                $speaker = Speaker::where('user_id', $user->id)->first();
                if($speaker) {
                    $speaker->profile_picture = $app_url .'/'. $fileName;
                    $speaker->save();
                }
				$user->url_image = $app_url .'/'. $fileName;
                
            }
			
			if(isset($data['url_image'])){

                $speaker = Speaker::where('user_id', $user->id)->first();
                if($speaker) {
                    $speaker->profile_picture = $data['url_image'];
                    $speaker->save();
                }
				$user->url_image = $data['url_image'];
			}
            
            if(isset($data['user_name']))  $user->user_name = $data['user_name'];
            if(isset($data['name'])) $user->name = $data['name'];
            if(isset($data['last_name'])) $user->last_name = $data['last_name'];
            if(isset($data['email'])) $user->email = $data['email'];
            
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

    public function delete(Request $request, $email){

		$confirm_account = ConfirmAccount::where('email',$email)->delete();
        $user = User::where('email', $email)->first();
		
        if($user) {
            $user->delete();

            return [
                'success' => 201,
                'data' => 'email-borrado' . $email
            ];
        }
        else {
            return response()->json(['message' => 'La sesión ha cadudcado.'. $email], 403);
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
    
    public function find (Request $request, $email){

        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'No se encuentra el usuario.',
                'status' => 404
            ], 200);
        }
        else {
            return response()->json([
                'message' => 'Esta cuenta ya ha sido activada.',
                'status' => 201
            ], 200);
        }
    }

    public function notifyConfirmEmail (Request $request, $email) {
        
        $code = '123456789';
        $code = substr(str_shuffle($code), 0, 6);
        $confirm_account = ConfirmAccount::where('email',$email)->first();
        if(!$confirm_account) {
          $confirm_account = new ConfirmAccount();
          $confirm_account->email = $email;
        }
        $confirm_account->code = $code;
        $confirm_account->save();

        try{
            Notification::route('mail', $email)
                ->notify(new AccountRegistration($email, $code));

        }catch (\Exception $e){
            return response()->json(['message' => 'Error enviando el correo electrónico .'.' '.$e], 403);
        }
        
        return response()->json([
            'success' => 201,
            'message' => 'Hemos enviado un correo electrónico'
        ]);
    }

    public function validateConfirmEmail (Request $request, $email,$code) {


        $confirm_account = ConfirmAccount::where([
            ['email',$email],
        ])->first();

        if($confirm_account){
            if($confirm_account->code == $code){
                $d1 = strtotime('now');
                $d2 = strtotime($confirm_account->created_at);
                $totalSecondsDiff = abs($d1 - $d2);
                $totalMinutesDiff = $totalSecondsDiff / 60;
                //if( $totalMinutesDiff > 15 ){
                if( true ){
                    return response()->json(['message' => 'Error el código expiró, solicite otro código.
					[$d1:'.$d1.']
					[$d2:'.$d2.']
					[$totalSecondsDiff:'.$totalSecondsDiff.']
					[$totalMinutesDiff:'.$totalMinutesDiff.']
					[$confirmAccountCoode:'.$confirm_account->code.']
					[$code:'.$code.']
					[$d1:'.$d1.']
					
					'], 403);
                }else{
                    return response()->json([
                        'success' => 201,
                        'message' => 'Código validado exitósamente'
                    ]);
                }
            }
            return response()->json([
                'error' => 200,
                'message' => 'Código incorrecto'
            ]);
        }else{
            return response()->json(['message' => 'Error no se encontró el correo'], 403);
        }


    }

}
