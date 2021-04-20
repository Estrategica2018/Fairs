<?php

namespace App\Http\Controllers;

use App\Models\Agendas;
use App\Models\InvitedSpeaker;
use App\Models\Audience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors(),
            ];
        }
        $data = $validator->validated();
    
	    $query = Agendas::select('id','title','description','duration_time','start_at','timezone','audience_config','resources','category_id','zoom_code')
		->with('invited_speakers.speaker.user', 'category')->where('fair_id',$data['fair_id']);
		
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
		$meetings = $query->get();
		
        return [
            'success' => 201,
            'data' => $meetings,
        ];

    }
	
	public function update_speakers(Request $request) {
        $validator = Validator::make($request->all(), [
            'fair_id' => 'required',
            'meeting_id' => 'required',
			'invited_speakers' => 'required',
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
		
		foreach($data['audience'] as $email) {
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
	
	public function generateVideoToken(Request $request, $fair_id, $meeting_id ) {
		
		$agenda = Agendas::with('audience')->find($meeting_id);

		if($agenda->audience_config == 2) {
			$user = auth()->guard('api')->user();
			///if(Auth::check()){
			if($user) {	
               //$email = \auth()->user()->email;
			   $email = $user->email;
			   //dd($agenda->audience,$email);
			   foreach($agenda->audience as $audience) {
				  if($audience->email === $email) {
					
					$audience->token = 'ASsdfASDFASDFasdfasdfasdf';
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
				return [
					  'success' => 403,
					  'message' => 'La reunión requiere un login válido',
					];
			}
        }
		else {
			return [
              'success' => 404,
              'message' => 'agenda no configurada con restricción de lista de correo',
          ];
		}
	}
}
