<?php

# /app/Http/Controllers/Zoom/MeetingController.php

namespace App\Http\Controllers\Zoom;

use App\Http\Controllers\Controller;
use App\Models\Agendas;
use App\Models\InvitedSpeaker;
use App\Models\Audience;
use App\Traits\ZoomJWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MeetingController extends Controller
{
    use ZoomJWT;

    const MEETING_TYPE_INSTANT = 1;
    //const MEETING_TYPE_SCHEDULE = 2;
    const MEETING_TYPE_SCHEDULE = 1;
    const MEETING_TYPE_RECURRING = 3;
    const MEETING_TYPE_FIXED_RECURRING_FIXED = 8;

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

        $path = 'users/me/meetings';
        $response = $this->zoomGet($path);

        $data = json_decode($response->body(), true);

        $data['meetings'] = array_map(function (&$m) {
            $m['start_at'] = $this->toUnixTimeStamp($m['start_time'], $m['timezone']);
            $agenda = Agendas::where('zoom_code',$m['id'])->first();
            $m['room'] = [];
            if( $agenda != null ){
                $m['room'] = $agenda->room;
                $m['title'] = $agenda->title;
                $m['description'] = $agenda->description;
                $m['resources'] = $agenda->resources;
                $m['speakers'] = [];
                foreach ($agenda->invited_speakers as $speaker){
                    $user_speaker = array();
                    $user_speaker['data_speaker'] = $speaker->speaker()->get();
                    $user_speaker['data_user'] = $speaker->speaker->user;
                    array_push($m['speakers'],$user_speaker);
                }

            }
            return $m;
        }, $data['meetings']);
/*
        foreach ($data['meetings'] as $meeting){
            $meeting['room']
        }
     */
        return [
            'success' => $response->ok(),
            'data' => $data,
        ];
    }
    /**
     * @OA\Post(
     *  path="/api/meetings",

     *  operationId="crear reunión",

     *  summary="Crear una reunión",

     *  @OA\Parameter(name="topic",

     *    in="query",

     *    required=true,

     *    @OA\Schema(type="string")

     *  ),
     *  @OA\Parameter(name="topic2",

     *    in="query",

     *    required=true,

     *    @OA\Schema(type="string")

     *  ),
     *  @OA\Response(response="200",

     *    description="Validation Response",

     *  )

     * )

     */
    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
            'topic' => 'required|string',
            'start_time' => 'required|date',
            'duration_time' => 'required|numeric',
            'agenda' => 'string|nullable',
            'timezone' => 'required|string',
            'category_id' => 'required|numeric',
            'fair_id' => 'required|numeric',
            'resources' => 'nullable',
            'token' => 'string|nullable',
            'audience_config' => 'required|string'
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors(),
            ];
        }
        $data = $validator->validated();

        $path = 'users/me/meetings';
        $response = $this->zoomPost($path, [
            'topic' => $data['topic'], // titulo
            'type' => self::MEETING_TYPE_SCHEDULE,  // tipo agenda 2
            'start_time' => $this->toZoomTimeFormat($data['start_time']),
            'timezone' => $data['timezone'],
            'duration' =>  $data['duration_time'],
            'agenda' => $data['agenda'],
            'settings' => [
                'host_video' => false,
                'participant_video' => false,
                'waiting_room' => true,
            ]
        ]);
        
        if($response->status() === 201) {
            
            $meeting = json_decode($response->body(), true);
            
            $agenda = new Agendas();
            $agenda->title = $meeting['topic'];
            $agenda->description = $meeting['agenda'];
            $agenda->duration_time = $data['duration_time'];
            $agenda->start_at = isset($meeting['start_time']) ? strtotime($meeting['start_time']) : strtotime($data['start_time']);
            $agenda->fair_id = $data['fair_id'];
            if(isset($data['resources'])) {
                $agenda->resources = $data['resources'];
            }
            $agenda->timezone = $meeting['timezone'];
            $agenda->category_id = $data['category_id'];
            $agenda->audience_config = $data['audience_config'];
            $agenda->zoom_code = $meeting['id'];
            $agenda->price = isset($data['price']) ? $data['price'] : 0;
            $agenda->zoom_password = $meeting['encrypted_password'];
            if(isset($data['token'])) {
                $agenda->token = $data['token'];
            }
            
            $agenda->save();

            return response()->json([
                    'data' => json_decode($response->body(), true),
                    'agenda' => $agenda,
                    'message', 'Exito, Reunión Zoom creada',
                    'success' => $response->status() === 201,
            ], 201);
        }
        else {
            dd($response->status() ,$response->body()->code );
            return response()->json([
                    'data' => json_decode($response->body(), true),
                    'agenda' => $agenda,
                    'message', 'Error creando la reunion',
                    'success' => false,
            ], 201);
        }
    }

    public function get(Request $request, string $id)
    {
        $path = 'meetings/' . $id;
        $response = $this->zoomGet($path);

        $data = json_decode($response->body(), true);
        if ($response->ok()) {
            $data['start_at'] = $this->toUnixTimeStamp($data['start_time'], $data['timezone']);
        }

        return [
            'success' => $response->ok(),
            'data' => $data,
        ];
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
            'topic' => 'required|string',
            'start_time' => 'required|date',
            'duration_time' => 'required|numeric',
            'category_id' => 'required|numeric',
            'agenda' => 'string|nullable',
            'timezone' => 'required|string',
            'fair_id' => 'required|numeric',
            'price' => 'numeric',
            'resources' => 'nullable',
            'token' => 'string|nullable',
            'audience_config' => 'required|string'
        ]);


        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors(),
            ];
        }
        $data = $validator->validated();

        $path = 'meetings/' . $id;
        $response = $this->zoomPatch($path, [
            'topic' => $data['topic'], //titulo
            'type' => self::MEETING_TYPE_SCHEDULE, //tipo agenda 2
            'start_time' => (new \DateTime($data['start_time']))->format('Y-m-d\TH:i:s'),
            'timezone' => $data['timezone'],
            'duration' =>  $data['duration_time'],
            'agenda' => $data['agenda'],
            'settings' => [
                'host_video' => false,
                'participant_video' => false,
                'waiting_room' => true,
            ]
        ]);
        
        if($response->status() === 204) {
            
            $meeting = json_decode($response->body(), true);
            $agenda = Agendas::find($data['id']);
            $agenda->title = $data['topic'];
            $agenda->description = $data['agenda'];
            $agenda->duration_time = $data['duration_time'];
            $agenda->start_at = strtotime($data['start_time']);
            $agenda->fair_id = $data['fair_id'];
            $agenda->category_id = $data['category_id'];
            $agenda->resources = $data['resources'];
            $agenda->price = isset($data['price']) ? $data['price'] : 0;
            $agenda->timezone = $data['timezone'];
            $agenda->audience_config = $data['audience_config'];
            $agenda->save();
            
            return [
              'success' => $response->status() === 204,
              'data' => json_decode($agenda, true),
            ];
        }
        else {
           // return [
           // 'success' => false,
           // 'message' => json_decode($response, true),
           //];    
		   
		    
            $agenda = Agendas::find($data['id']);
            $agenda->title = $data['topic'];
            $agenda->description = $data['agenda'];
            $agenda->duration_time = $data['duration_time'];
            $agenda->start_at = strtotime($data['start_time']);
            $agenda->fair_id = $data['fair_id'];
            $agenda->category_id = $data['category_id'];
            $agenda->resources = $data['resources'];
            $agenda->price = isset($data['price']) ? $data['price'] : 0;
            $agenda->timezone = $data['timezone'];
            $agenda->audience_config = $data['audience_config'];
            $agenda->save();
            
            return [
              'success' => true,
              'data' => json_decode($agenda, true),
            ];
        }
        
        
    }

    public function delete(Request $request, string $id)
    {
        $path = 'meetings/' . $id;
        $response = $this->zoomDelete($path);
        
        $meeting = json_decode($response->body(), true);
        if($response->status() === 204 || $meeting['code'] === 3001) {
			
			
            $agenda = Agendas::where('zoom_code',$id);
			InvitedSpeaker::where('agenda_id',$agenda->id)->delete();
			Audience::where('agenda_id',$agenda->id)->delete();
			
            $agenda->delete();
			
			return [
            'success' => true,
            'data' => json_decode($response->body(), true),
            ];
        }
		
        return [
            'success' => true,
            'data' => null
        ];
    }
}

