<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Zoom\MeetingController;
use App\Models\Speaker;
use Illuminate\Http\Request;

class SpeakerController extends Controller
{
    //

    public function list (Request $request){

        $speakers = Speaker::with(['user','agenda'])->get()->toJson(true);
        $speakers = json_decode( $speakers,true);
        foreach ($speakers as $keyI => $speaker){
            foreach ($speaker['agenda'] as $key => $agenda) {
                $zoomController = new MeetingController();
                $zoom = $zoomController->get($request, $agenda['zoom_code']);
                $speakers[$keyI]['agenda'][$key]['zoom'] = [];
                if($zoom['success'] == true)
                    $speakers[$keyI]['agenda'][$key]['zoom'] = $zoom['data'];
            }
        }
        return response()->json([
            'data' => $speakers,
            'message'=> 'Lista de conferencista con agenda',
            'success' => true,
        ], 201);

    }
}
