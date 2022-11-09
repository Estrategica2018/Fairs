<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MinculturaUser;
use App\Models\Audience;
use App\Models\Agendas;

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
            if($agenda->audience_config == "5"|| substr($agenda->category->name, 0, 6) == 'Taller') {
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
            'meetings' => $meetings,
            'tx'=>substr($agenda->category->name, 0, 6)
        ];

    }

    public function register(Request $request){

        $user = auth()->guard('api')->user();

        //query mincultura user data
        $minculturaUpdate = false;
        $mincultura = MinculturaUser::where('user_id',$user->id)->first();
        if($mincultura) {
            $minculturaUser->documento_tipo = $request['documento_tipo'];
            $minculturaUser->documento_numero = $request['documento_numero'];
            $minculturaUser->correo_electronico_adicional = $request['correo_electronico_adicional'];
            $minculturaUser->save();
            $minculturaUpdate = true;
        }

        //query register in agenda
        $audience_user = null;
        $meetings = [];
        $newAudience = false;
        $audience = null;
        $agenda_id = $request['agenda_id'];        
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
                $audience->email = $audi['email'];
                $audience->user_id = $audi['email'];
                $audience->check = 1;
                $newAudience = true;                
                $audience->save();
            }            
        }
        
        //return data with state available meetings
        return [
            'success' => 201,
            'minculturaUpdate' => $minculturaUpdate,
            'audience' => $audience,
            'newAudience' => $newAudience
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

}
