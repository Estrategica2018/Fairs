<?php

namespace App\Http\Controllers;


use App\Models\Speaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SpeakerController extends Controller
{
    //

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
        })->get();

        
        return response()->json([
            'data' => $speakers,
            'message'=> 'Lista de conferencista con agenda',
            'success' => true,
        ], 201);
    }

    public function update(Request $request){

            $validator = Validator::make($request->all(), [
                'user_id'=>'',
                'description'=>'',
                'title'=>'',
                'resources'=>''
            ]);

            if ($validator->fails()) {
                return [
                    'success' => false,
                    'data' => $validator->errors(),
                ];
            }

            $speaker = Speaker::find($request->user_id);
            if(!$speaker) {
                $data = $validator->validated();

                $speaker->user_name = $data['user_name'];
                $speaker->description = $data['description'];
                $speaker->title = $data['title'];
                $speaker->resources = $data['resources'];
                $speaker->save();

                return [
                    'success' => 201,
                    'data' => $speaker,
                ];
            }
            else {
                return response()->json(['message' => 'No se puedo encontrar el conferencista.'], 403);
            }

        }

}
