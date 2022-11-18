<?php

namespace App\Http\Controllers;

use App\Models\Agendas;
use App\Models\InvitedSpeaker;
use App\Models\Audience;
use App\Models\Fair;
use App\Notifications\Conference\SuccessFulRegistrationFree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\App;
use App\Notifications\SuccessAgendaRegistration;
use Carbon\Carbon;

class AgendaController extends Controller
{
     /**
     * @OA\Get(
     *  path="/api/meetings",

     *  operationId="listar reuniones",

     *  summary="Obtener la lista de reuniones",


     *  @OA\Response(response="200",

     *    description="Validation Response",

     *  )

     * )

     */
    public function list (Request $request) {
        $validator = Validator::make($request->all(), [
            'fair_id' => '',
            'pavilion_id' => '',
            'stand_id' => '',
            'zoom_auth' => '',
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors(),
            ];
        }
        $data = $validator->validated();
    
        
        if(isset($data['zoom_auth']) ) {
            $querySelect = Agendas::select('id','title','description', 'description_large','duration_time','start_at','timezone','audience_config','resources','category_id','zoom_code','zoom_password','price');
        }
        else {
            $querySelect= Agendas::select('id','title','description','description_large','duration_time','start_at','timezone','audience_config','resources','category_id','price');
        }

        $query = $querySelect->with('invited_speakers.speaker.user', 'category')->where('fair_id',$data['fair_id']);
        
        if(isset($data['fair_id'])) {
          $query = $query->where('fair_id',$data['fair_id']);
        }
        else if(isset($data['pavilion_id'])) {
          $query = $query->whereHas('room', function ($queryRoom) use ($data) {
              $queryRoom->where('pavilion_id','=',$data['pavilion_id']);
           });
        }
        else if(isset($data['stand_id'])) {
          $query = $query->whereHas('room', function ($queryRoom) use ($data) {
              $queryRoom->where('stand_id','=',$data['stand_id']);
           });
        }
        $meetings = $query->orderBy('start_at')->get();
        
        return [
            'success' => 201,
            'data' => $meetings,
        ];

    }
    
    public function update_speakers(Request $request) {
        $validator = Validator::make($request->all(), [
            'fair_id' => 'required',
            'meeting_id' => 'required',
            'invited_speakers' => '',
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors(),
            ];
        }
        $data = $validator->validated();
        
        InvitedSpeaker::where('agenda_id',$data['meeting_id'])->delete();
        $list = [];
        
        foreach($data['invited_speakers'] as $speaker) {
            $invitedSpeaker = new InvitedSpeaker();
            $invitedSpeaker->agenda_id = $data['meeting_id'];
            $invitedSpeaker->speaker_id = $speaker['id'];
            $invitedSpeaker->save();
            array_push($list, $invitedSpeaker);
        }
        
        return [
            'success' => 201,
            'data' => $list,
        ];
    }

    public function update_audience(Request $request) {
        $validator = Validator::make($request->all(), [
            'fair_id' => 'required',
            'meeting_id' => 'required',
            'audience' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors(),
            ];
        }
        $data = $validator->validated();
        
        Audience::where('agenda_id',$data['meeting_id'])->delete();
        $list = [];
        
        foreach($data['audience'] as $audi) {
            $audience = new Audience();
            $audience->agenda_id = $data['meeting_id'];
            $audience->email = $audi['email'];
            $audience->check = $audi['check'];
            $audience->save();
            array_push($list, $audience);
        }
        
        return [
            'success' => 201,
            'data' => $list,
        ];
    }
    
    public function generateMeetingToken(Request $request, $fair_id, $meeting_id ) {
        
        $agenda = Agendas::with('audience')->find($meeting_id);
		$user = auth()->guard('api')->user();

        if($user) {
			function microtime_float() {
			  list($usec, $sec) = explode(" ", microtime());
			  return ((float)$usec + (float)$sec);
			}
			$time = microtime_float();
			$token = uniqid('user_').$user->id.'.'.$meeting_id.'.'.$fair_id;
			
			if($agenda->audience_config == 2) {
               $email = $user->email;
               foreach($agenda->audience as $audience) {
                  if($audience->email === $email) {
                      $audience->token = $token;
                      $audience->user_id = $user->id;
                      $audience->save();
                    return [
                      'success' => 201,
                      'data' => $audience->token,
                    ];
                  }
                }
                return [
                  'success' => 402,
                  'message' => 'agenda no configurada para el usuario',
                ];
			}
			else {
				$audience = Audience::where([['agenda_id',$agenda->id],['email',$user->email]])->first();
				
				if(!$audience) {
				  $audience = new Audience();
				  $audience->agenda_id = $agenda->id;
				  $audience->email = $user->email;
				  $audience->user_id = $user->id;
				}
				$audience->check = 1;
				$audience->token = $token;
				$audience->save();

				
                /*try{
                    Notification::route('mail', $user->email)
                        ->notify(new SuccessFulRegistrationFree($agenda,$user));
                }catch (\Exception $e){
                    return response()->json(['message' => 'Error enviando el correo electrónico .'.' '.$e], 403);
                }*/

				return [
				  'success' => 201,
				  'data' => $audience->token,
				];

			}
		}
		else {
			return [
				  'success' => 403,
				  'message' => 'La sesión ha caducado',
				];
		}
    }
    
    // super admin or admin role rules
    public function getEmails(Request $request,$fair_id,$agenda_id) {
        
        $validator = Validator::make($request->all(), [
            'agenda_id' => '',
            'fair_id' => '',
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors(),
            ];
        }
        $data = $validator->validated();
    
        $agenda = Agendas::with('audience')
        ->where(['fair_id'=>$fair_id,'id'=>$agenda_id])    
        ->first();
        
        return [
            'success' => 201,
            'data' => ['audience' => $agenda->audience],
        ];

    }

    public function availableList (Request $request) {
        $validator = Validator::make($request->all(), [
            'fair_id' => '',
            'agenda_id' => ''
        ]);

        $user = auth()->guard('api')->user();

        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors(),
            ];
        }
        $data = $validator->validated();
        
        $querySelect = Agendas::select('id','title','description', 'description_large','duration_time','start_at','timezone','audience_config','category_id','price','resources');
        $query = $querySelect->with('audience.user.user_roles_fair', 'invited_speakers.speaker.user', 'category')
        ->where('fair_id',$request['fair_id']);
        
        
        if(isset($request['agenda_id'])) {
          $query = $query->where('id',$request['agenda_id']);
        }

        $meetings = $query->orderBy('id')->get();

        forEach($meetings as $agenda) {
            if($agenda->audience_config == 5) {
                $agenda->disableSelection = false;
                $agenda->resources = json_decode($agenda->resources, true);
                $guests = $agenda->resources['guests'];
                $count = 0;
                $agenda->count = 0;

                forEach($agenda->audience as $audience) {
                    
                    if($audience->user->id == $user->id ){
                        $agenda->disableSelection = true;
                    }

                    if($agenda->disableSelection == false) {
                        forEach($audience->user->user_roles_fair as $rol) {
                            if($rol->pivot->role_id == 4){
                                $count ++;
                                $agenda->count ++;                                
                                $agenda->full = $count >= $guests;
                            }
                        }
                    }
                }
            }


            unset($agenda->audience);
        }
        
        return [
            'success' => 201,
            'data' => $meetings,
        ];

    }

    public function register (Request $request) {
        $validator = Validator::make($request->all(), [
            'fair_id' => '',
            'agenda_id' => ''
        ]);

        $user = auth()->guard('api')->user();

        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors(),
            ];
        }
        $data = $validator->validated();
        
        $meetings = $this->availableList($request);
        $agenda = $meetings['data'][0];
        $audience = null;
        if($agenda->full == false && !$agenda->disableSelection){
            
            //register user in agenda
            $audience = new Audience();
            $audience->agenda_id = $agenda->id;
            $audience->email = $user->email;
            $audience->user_id = $user->id;
            $audience->check = 1;
            $audience->save();

            $fair_id = $request['fair_id'];
            $agenda_id = $request['agenda_id'];
            $fair = Fair::find($fair_id);
            $agenda = Agendas::find($agenda_id);
            $date = date("d/m/Y", $agenda->start_at);
            $dateHour = date("H:i", $agenda->start_at);
            $day = array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado");
            $dayFormat = $day[date("w",$agenda->start_at)]. ', '. $date;

            $duration = ['15'=>'15 min','30'=>'30 min','45'=>'45 min','60'=>'1 hora','90'=>'1 hora y 30 min','120'=>'2 horas','150'=>'2 horas y 30 min','180'=>'3 horas','210'=>'3 horas y 30 min','240'=>'4 horas'];
            $durationStr = $duration[$agenda->duration_time]; 

            //if (App::environment('production') || App::environment('sendEmail') ) {
                Notification::route('mail', $user->email)
                ->notify(new SuccessAgendaRegistration($fair, $user->email, $agenda, $dayFormat, $durationStr, $dateHour));
                $sendMail = true;
            //}
        }
        
        return [
            'success' => 201,
            'data' => $agenda,
            'audience'=> $audience,
            'sendMail'=> $sendMail
        ];

    }

    public function live (Request $request) {
        
        $validator = Validator::make($request->all(), [
            'fair_id' => ''
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors(),
            ];
        }
        $data = $validator->validated();    
        
        $query = Agendas:://select('id','duration_time','start_at','timezone','audience_config','resources')
        //->
        with('category')
        ->where('fair_id',$data['fair_id'])
        ->get();

        foreach($query as $meeting) {
            if($meeting->category->name != 'Taller_M' && $meeting->category->name != 'Taller_T') {
                $start = date('Y-m-d H:i', strtotime('-15 min',$meeting->start_at));
                $end = date('Y-m-d H:i', strtotime('+'.$meeting->duration_time.' min',$meeting->start_at));
                $date = date('Y-m-d H:i');
                if (($date >= $start  && $date <= $end)) {                
                    return [
                        'success' => 201,
                        'data' => $meeting,
                        'start'=> $start,
                        'end'=> $end
                    ];
                }
            }
        }  

        return [
            'success' => 201,
            'data' => null
        ];

    }

}
