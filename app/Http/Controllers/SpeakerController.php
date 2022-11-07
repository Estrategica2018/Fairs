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
use mysql_xdevapi\Collection;

class SpeakerController extends Controller
{
    //

    public function create (Request $request){

        $validator = Validator::make($request->all(), [
            'user_name'=>'required',
            'name'=>'required',
            'last_name'=>'required',
            'email'=>'required|email|unique:users,email',
            //'password'=>'required',
            'fair_id'=>'required',
            'origin'=>'required',
            'profile_picture'=>'',
            'company_logo'=>'',
            'description_one'=>'required',
            //'description_two'=>'required',
            'position'=>'',
            'profession'=>'',
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
        $user->url_image = $data['profile_picture'];
        if(isset($data['contact'])){
            $user->contact = $data['contact'];
        }
        $user->password = Hash::make(12345678);
        $user->save();

        $user_rol_fair = new RoleUserFair();
        $user_rol_fair->user_id = $user->id;
        $user_rol_fair->role_id = 6;
        $user_rol_fair->fair_id = $data['fair_id'];
        $user_rol_fair->save();


        $speaker = new Speaker();
        $speaker->user_id = $user->id;
        $speaker->description = '';
        $speaker->title = '';
        $speaker->resources = '{}';
        $speaker->profile_picture = $data['profile_picture'];
        $speaker->company_logo = $data['company_logo'];
        $speaker->description_one = $data['description_one'];
        //$speaker->description_two = $data['description_two'];
        $speaker->position = $data['position'];
        $speaker->profession = $data['profession'];
        $speaker->save();



        $user = collect($user);
        $speaker = collect($speaker);
        return [
            'success' => 201,
            'data' => $user->merge($speaker),
        ];
    }

    public function list (Request $request){

        //$speakers = Speaker::with('user')
            //->whereHas('user',function ($query) use ($request) {
            //  $query->whereHas('role_user_fairs',function ($queryRol) use ($request){
            //      $queryRol->where('fair_id','=',$request->fair_id);
            //  });
            //})
        //    ->with(['agenda'=>function ($queryAgenda) use ($request) {
        //      $queryAgenda->where('fair_id','=',$request->fair_id);
        //    }])->get();
           
        //$speakers = json_decode( $speakers,true);
        //
        //foreach ($speakers as $keyI => $speaker){
        //    foreach ($speaker['agenda'] as $key => $agenda) {
        //        $zoomController = new MeetingController();
        //        $zoom = $zoomController->get($request, $agenda['zoom_code']);
        //        $speakers[$keyI]['agenda'][$key]['zoom'] = [];
        //        if($zoom['success'] == true)
        //            $speakers[$keyI]['agenda'][$key]['zoom'] = $zoom['data'];
        //    }
        //} 

        /*$speakers = Speaker::with(['user.role_user_fairs' => function ($query) use ($request) {
            $query->where('role_id',6);
        }])->get();*/
        
        $speakers = Speaker::with('user')->whereHas('user.role_user_fairs',function ($query) use ($request) {
            $query->where('fair_id', $request->fair_id);
        })->with(['agenda'=>function ($queryAgenda) use ($request) {
              $queryAgenda->where('fair_id','=',$request->fair_id);
            }])->get();
        
        return response()->json([
            'data' => $speakers,
            'message'=> 'Lista de conferencista con agenda',
            'success' => true,
        ], 201);
    }

    public function update(Request $request){

            $validator = Validator::make($request->all(), [
                'id'=>'required',
                'user_name'=>'',
                'name'=>'required',
                'last_name'=>'required',
                'profile_picture'=>'',
                'company_logo'=>'',
                'description_one'=>'required',
                //'description_two'=>'required',
                'position'=>'',
                'profession'=>'',
            ]);

            if ($validator->fails()) {
                return [
                    'success' => false,
                    'data' => $validator->errors(),
                ];
            }

            $speaker = Speaker::find($request->id);

            if($speaker !== null) {
                $user = User::find($speaker->user_id);
                if($user !== null){
                    $data = $validator->validated();
                    $speaker->description = '';
                    $speaker->title = '';
                    $speaker->resources = '{}';
                    $speaker->profile_picture = $data['profile_picture'];
                    $speaker->company_logo = $data['company_logo'];
                    $speaker->description_one = $data['description_one'];
                    //$speaker->description_two = $data['description_two'];
                    $speaker->position = $data['position'];
                    $speaker->profession = $data['profession'];
                    $speaker->save();

                    $user->name = $data['name'];
                    $user->last_name = $data['last_name'];
                    $user->url_image = $data['profile_picture'];
                    $user->save();
                    return [
                        'success' => 201,
                        'data' => $speaker,
                    ];
                }else{
                    return response()->json(['message' => 'No se puedo encontrar el usuario.'], 403);
                }

            }
            else {
                return response()->json(['message' => 'No se puedo encontrar el conferencista.'], 403);
            }

        }

    public function delete(Request $request){

        $validator = Validator::make($request->all(), [
            'id'=>'required',
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors(),
            ];
        }

        $speaker = Speaker::find($request->id);
        if($speaker !== null) {
            $speaker->active = !$speaker->active;
            $speaker->save();
            return [
                'success' => 201,
                'data' => $speaker,
            ];
        }else{
            return response()->json(['message' => 'No se puedo encontrar el conferencista.'], 403);
        }
    }

    public function uploadFile(Request $request){

        $target_file = $_FILES["fileToUpload"]["tmp_name"];
        

        $handle = fopen($target_file, "r");
        $numberLine = 0;
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $numberLine ++;
                if($numberLine > 1) {
                    // process the line read.
                    $data = explode('|',$line);
                
                    $user_id = $data[0];
                    $user = User::where('id',$user_id)->first();
                    if(!$user) {
                        $user = new User();
                    }

                    $fair_name = $data[1];
                    $fair_id = Fair::where('name',$fair_name)->first()->id;

                    $position = $data[2];
                    $name = $data[3];
                    $last_name = $data[4];
                    $email = $data[5];
                    $urlProfile = $data[6];
                    $urlLogo = $data[7];
                    $description = $data[8];
                    $description_one = isset($data[9]) ? $data[9] : '';
                    $profession =  isset($data[10]) ? $data[10] : '';


                    $user->user_name = 'user_'.$fair_id.'_'.rand(0, 99999);
                    $user->name = $name;
                    $user->last_name = $last_name;
                    $user->email = $email;
                    $user->url_image = $urlProfile;
                    $user->password = Hash::make('congreso_2022');
                    $user->save();

                    $user_rol_fair = RoleUserFair::where('user_id',$user->id)->first();
                    if(!$user_rol_fair) {
                        $user_rol_fair = new RoleUserFair();
                    }
                    $user_rol_fair->user_id = $user->id;
                    $user_rol_fair->role_id = 6;
                    $user_rol_fair->fair_id = $fair_id;
                    $user_rol_fair->save();

                    $speaker = Speaker::where('user_id',$user->id)->first();
                    if(!$speaker) {
                        $speaker = new Speaker();
                    }
                    
                    $speaker->user_id = $user->id;
                    $speaker->description = $description;
                    $speaker->resources = '{}';
                    $speaker->profile_picture = $urlProfile;
                    $speaker->company_logo = $urlLogo;
                    $speaker->description = $description;
                    $speaker->description_one = $description_one;
                    
                    $speaker->position = $position;
                    $speaker->profession = $profession;
                    $speaker->save();
                }
            }
         }
        
         fclose($handle);

         return [
             'success' => 201,
             'data' => "usuarios adicionados",
         ];
        
        
    }

}
