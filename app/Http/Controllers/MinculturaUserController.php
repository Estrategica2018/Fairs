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
            if($agenda->audience_config == "5") {
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

            //array_push($meetings,$agenda);
        }

        //return data with state available meetings
        return [
            'success' => 201,
            'data' => $mincultura,
            'audience' => $audience_user,
            'meetings' => $meetings
        ];

    }
}
