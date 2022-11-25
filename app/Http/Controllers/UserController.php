<?php

namespace App\Http\Controllers;

use App\Models\ConfirmAccount;
use App\Models\Fair;
use App\Models\Audience;
use App\Models\MinculturaUser;
use App\Models\ShoppingCart;
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
use Illuminate\Support\Facades\App;

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
            'documento_tipo' => '',
            'documento_numero' => '',
            'correo_electronico_adicional' => '',
            'numero_celular' => '',
            'pais_inscripcion' => '',
            'zona_se_encuentra_en' => '',
            'zona_se_encuentra_en_otra' => '',
            'sexo_se_reconoce_como' => '',
            'sexo_se_reconoce_como_otro' => '',
            'sexo_registro_civil' => '',
            'sexo_registro_civil_otro' => '',
            'cultura_se_reconoce_como' => '',
            'cultura_se_reconoce_como_otro' => '',
            'discapacidad' => '',
            'discapacidad_cual' => '',
            'relacion_sector_rol' => '',
            'relacion_sector_rol_otro' => '',
            'institucion_vinculo' => '',
            'codigo_cbu' => '',
            'institucion_ubicacion' => '',
            'escolaridad_nivel' => '',
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

        $this->mincultura_user($user,$data);

        try{

            if (App::environment('production') || App::environment('sendEmail') ) {
              Notification::route('mail', $data['email'])
                ->notify(new SuccessfulRegistration($fair, $data['email']));
            }

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

    public function delete(Request $request, $email, $type, $agenda_id){

		
        $user = User::where('email', $email)->first();
		
        if($user) {
            if($type == 'all') {
                $confirm_account = ConfirmAccount::where('email',$email)->delete();
                $role_user_fairs = RoleUserFair::where('user_id',$user->id)->delete();
                $audiences = Audience::where('user_id',$user->id)->delete();
                $audiences = MinculturaUser::where('user_id',$user->id)->delete();
                $audiences = ShoppingCart::where('user_id',$user->id)->delete();
                $user->delete();

                return [
                    'success' => 201,
                    'data' => 'email-borrado ' . $email
                ];
            }
            if($type == 'audienceOne') {

                $agenda_id = isset( $request['agenda_id']) &&  $request['agenda_id'] ? $request['agenda_id'] :  0;
                $count = 0;
                if($agenda_id > 0) {
                    $count = $audiences = Audience::where([['user_id',$user->id],['agenda_id',$agenda_id]])->delete();            
                    
                }   
                
                return [
                    'success' => 201,
                    'data' => 'audiencia ['.$count.'] borrada para ' . $email,
                    'agenda_id' => $request['agenda_id']
                 ];
            }

            if($type == 'audience') {
                $audiences = Audience::where('user_id',$user->id)->delete();            
                return [
                    'success' => 201,
                    'data' => 'audiencia borrada para ' . $email
                ];
            }
            

            return [
                'success' => 201,
                'data' => 'nothing to do'
            ];
            
            
        }
        else {
            return response()->json(['message' => 'No existe el usuario.'. $email], 403);
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

    public function notifyConfirmEmail (Request $request, $fairName, $email ) {
        
        $code = '123456789';
        $code = substr(str_shuffle($code), 0, 6);
        $confirm_account = ConfirmAccount::where('email',$email)->first();
        if(!$confirm_account) {
          $confirm_account = new ConfirmAccount();
          $confirm_account->email = $email;
        }
        $confirm_account->code = $code;
        $confirm_account->save();
		
		$fair = Fair::where('name',$fairName)->first();
		if(!$fair ){
		  return [
			'success' => 400,
			'data' => $fair,
		  ];
		}
		
		try{
			$fair->social_media = json_decode($fair->social_media);
		
        	if (App::environment('production') || App::environment('sendEmail') ) {
              Notification::route('mail', $email)
                ->notify(new AccountRegistration($email, $code, $fair));
            }

        }catch (\Exception $e){
            return response()->json(['message' => 'Error enviando el correo electrónico .'.' '.$e], 403);
        }
        
        return response()->json([
            'success' => 201,
            'message' => 'Hemos enviado un correo electrónico',
            'code' => $code
        ]);
    }

    public function validateConfirmEmail (Request $request, $email,$code) {


        $confirm_account = ConfirmAccount::where([
            ['email',$email],
        ])->first();

        if($confirm_account){
            if($confirm_account->code == $code){
                $d1 = strtotime('now');
                $d2 = strtotime($confirm_account->updated_at);
                $totalSecondsDiff = abs($d1 - $d2);
                $totalMinutesDiff = $totalSecondsDiff / 60;
                if( $totalMinutesDiff > 15 ){
                
                    return response()->json(['message' => 'Error el código expiró, solicite otro código.'], 403);
                }else{
                    return response()->json([
                        'success' => 201,
                        'message' => 'Código validado exitósamente'
                    ]);
                }
            }
            return response()->json([
                'error' => 200,
                'message' => 'Código incorrecto. '
            ]);
        }else{
            return response()->json(['message' => 'Error no se encontró el correo'], 403);
        }


    }

    public function mincultura_user($user,$data){

        $minculturaUser = new MinculturaUser();
        $minculturaUser->user_id = $user->id;
        $minculturaUser->documento_tipo = $data['documento_tipo'];
        $minculturaUser->documento_numero = $data['documento_numero'];
        if($data['correo_electronico_adicional'] != null )
        {
            $minculturaUser->correo_electronico_adicional = $data['correo_electronico_adicional'];
        }  
        else{
            $minculturaUser->correo_electronico_adicional = '';
        }
        $minculturaUser->numero_celular = $data['numero_celular'];
        $minculturaUser->pais_inscripcion = $data['pais_inscripcion'];
        $minculturaUser->zona_se_encuentra_en = $data['zona_se_encuentra_en'];
        if($data['zona_se_encuentra_en'] == 'Otra'){
            $minculturaUser->zona_se_encuentra_en_otra = $data['zona_se_encuentra_en_otra'];
        }
        $minculturaUser->sexo_se_reconoce_como = $data['sexo_se_reconoce_como'];
        if($data['sexo_se_reconoce_como'] == 'Otro'){
            $minculturaUser->sexo_se_reconoce_como_otro = $data['sexo_se_reconoce_como_otro'];
        }
        $minculturaUser->sexo_registro_civil = $data['sexo_registro_civil'];
        if($data['sexo_registro_civil'] == 'Otro'){
            $minculturaUser->sexo_registro_civil_otro = $data['sexo_registro_civil_otro'];
        }
        $minculturaUser->cultura_se_reconoce_como = $data['cultura_se_reconoce_como'];
        if($data['cultura_se_reconoce_como'] == 'Otro'){
            $minculturaUser->cultura_se_reconoce_como_otro = $data['cultura_se_reconoce_como_otro'];
        }

        if($data['discapacidad_cual'] != null){
            $minculturaUser->discapacidad_cual = $data['discapacidad_cual'];
            $minculturaUser->discapacidad = 'si';
        }else{
            $minculturaUser->discapacidad_cual = '';
            $minculturaUser->discapacidad = 'no';
        }
        $minculturaUser->relacion_sector_rol = $data['relacion_sector_rol'];
        if($data['relacion_sector_rol'] == 'Otro'){
            $minculturaUser->relacion_sector_rol_otro = $data['relacion_sector_rol_otro'];
        }
        $minculturaUser->institucion_vinculo = $data['institucion_vinculo'];
        $minculturaUser->codigo_cbu = '';
        $minculturaUser->institucion_ubicacion = $data['institucion_ubicacion'];
        $minculturaUser->escolaridad_nivel = $data['escolaridad_nivel'];
        $minculturaUser->save();
    }

}
