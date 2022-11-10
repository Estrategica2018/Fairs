<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MinculturaUser;
use App\Models\Audience;
use App\Models\Agendas;
use Illuminate\Support\Facades\App;

class MinculturaUserController extends Controller
{
    public function index(Request $request){

        $user = auth()->guard('api')->user();

        //query mincultura user data
        $mincultura = MinculturaUser::where('user_id',$user->id)->first();
        
        $audience_user = Audience::with('agenda.category')
        ->with('user.user_roles_fair')
        ->where('user_id',$user->id)->get();
        
        //query available list 
        $queryMeeting = Agendas::select('id','title','description', 'description_large','duration_time','start_at','timezone','audience_config','resources','category_id','price');
        $queryMeeting = $queryMeeting->with('audience.user.user_roles_fair', 'invited_speakers.speaker.user', 'category' );
        $queryMeeting = $queryMeeting->where('fair_id',$request['fair_id'])->get();

        $meetings = [];

        forEach($queryMeeting as $agenda) {
            if($agenda->audience_config == "5" || $agenda->category->name == 'Taller' || $agenda->category->name == 'Taller_M'||$agenda->category->name == 'Taller_T') {
                $count = 0;
                $guest = 25;
                forEach($agenda->audience as $audience) {
                    if($audience->user->user_roles_fair) {
                        forEach($audience->user->user_roles_fair as $rol) {
                            if($rol->pivot->role_id == 4) {
                                $count++;
                            }
                        }
                    }
                }
                unset($agenda->audience);
                $agenda->full = $count >= $guest ? '1': '0';
                array_push($meetings,$agenda);
            }

            
        }

        //return data with state available meetings
        return [
            'success' => 201,
            'data' => $mincultura,
            'audience' => $audience_user,
            'meetings' => $meetings
        ];

    }

    public function register(Request $request){

        $user = auth()->guard('api')->user();
        $sendMail = false;

        //query mincultura user data
        $minculturaUpdate = false;
        $mincultura = MinculturaUser::where('user_id',$user->id)->first();
        if($mincultura) {
            $mincultura->documento_tipo = $request['docType'];
            $mincultura->documento_numero = $request['docNumber'];
            $mincultura->correo_electronico_adicional = $request['emailAdditional'];
            $mincultura->save();
            $minculturaUpdate = true;
        }

        //query register in agenda
        $audience_user = null;
        $meetings = [];
        $newAudience = false;
        $audience = null;
        $agenda_id = $request['agendaId'];        
        if($agenda_id) {
            $audience_user = Audience::where('user_id',$user->id)->get();
            if(!$audience_user) {
                $newAudience = true;
            }
            else {
                $newAudience = true;
                foreach ($audience_user as $aud) {
                    if($aud->agenda_id == $agenda_id) {
                        $audience = $aud;
                        $newAudience = false;
                    }
                }
            }

            if($newAudience) {
                Audience::where('user_id',$user->id)->delete();
                $audience = new Audience();
                $audience->agenda_id = $agenda_id;
                $audience->email = $user->email;
                $audience->user_id = $user->id;
                $audience->check = 1;
                $newAudience = true;
                $audience->save();

                try{
                    $agenda = Agendas::find($agenda_id);

                    $date = date("d/m/Y", $agenda->start_at);
                    $dateHour = date("H:i", $agenda->start_at);
                    $day = array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado");
                    $dayFormat = $day[date("w",$agenda->start_at)]. ', '. $date;

                    $duration = ['15'=>'15 min','30'=>'30 min','45'=>'45 min','60'=>'1 hora','90'=>'1 hora y 30 min','120'=>'2 horas','150'=>'2 horas y 30 min','180'=>'3 horas','210'=>'3 horas y 30 min','240'=>'4 horas'];
                    $durationStr = $duration[$agenda->duration_time]; 

                    if (App::environment('production') || App::environment('sendEmail') ) {
                      Notification::route('mail', $user->email)
                        ->notify(new SuccessAgendaRegistration($fair, $data['email'], $agenda, $dayFormat, $durationStr));
                      $sendMail = true;
                    }
        
                }catch (\Exception $e){
                    return response()->json(['message' => 'Error enviando el correo electrónico .'.' '.$e], 403);
                }
            }            
        }
        
        //return data with state available meetings
        return [
            'success' => 201,
            'minculturaUpdate' => $minculturaUpdate,
            'audience' => $audience,
            'newAudience' => $newAudience,
            'sendMail' => $sendMail
        ];
    }

    public function agendaAvailability(Request $request){

        $agenda_id = $request['agenda_id'];
        
        $agenda = Agendas::select('id','title','description', 'description_large','duration_time','start_at','timezone','audience_config','resources','category_id','price');
        $agenda = $agenda->with('audience.user.user_roles_fair', 'invited_speakers.speaker.user', 'category' );
        $agenda = $agenda->where([['fair_id',$fair_id],['agenda_id',$agenda_id]])->first();

        if($agenda->audience_config == "5"||$agenda->category->name == 'Taller') {
            $count = 0;
            $guest = 25;
            forEach($agenda->audience as $audience) {
                if($audience->user->user_roles_fair) {
                    forEach($audience->user->user_roles_fair as $rol) {
                        if($rol->pivot->role_id == 4) {
                            $count++;
                        }
                    }
                }
            }
            
            return $count >= $guest;
        }
        
        return true;
    } 

    public function showRegister(Request $request){

        $fair_id = $request['fair_id'];
        if($fair_id==1) {
        $array = [];
        $audiences = Audience::
        with('user','agenda.category')->get();
        forEach($audiences as $audience) {
            array_push($array, [
                'user'=> $audience->user->name .' '.$audience->user->last_name,
                'emall'=> $audience->user->email,
                'taller' => $audience->agenda->title
            ]);
        }

        dd($array);
    }
    else {
        $array = []; 

        $audiences = MinculturaUser::with('user')->get();

        forEach($audiences as $audience) {
            array_push($array, [
                'user'=> $audience->user->name .' '.$audience->user->last_name,
                'emall'=> $audience->user->email
            ]);
        }

        dd($array);
    }
        return true;
    } 

}
