<?php

namespace App\Http\Controllers\Zoom;

use App\Models\Agendas;
use App\Models\InvitedSpeaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class ViewerZoomController extends Controller
{
    public function index (Request $request, $fair_id, $meeting_id, $name = '', $speaker_id = '') {
		
        $speaker_id = $request->speaker_id;
		
		$agenda = Agendas::with('invited_speakers')->find($meeting_id);
		if($agenda) {
			if($agenda->audience_config == 1) {
				
			}
			else if($agenda->audience_config == 2) {
				return abort(403);	
			}
			else if($agenda->audience_config == 3) {
				return abort(404);
			}
			$role = 1;
			$email = '';
			foreach($agenda->invited_speakers as $invited_speaker) {
				if($invited_speaker->speaker_id === $speaker_id) {
					$role = 1;
					if(strlen($name)===0) {
						dd($invited_speaker);
						dd($email);
					}
					break;
				}
			}

			$API_SECRET = env('ZOOM_API_SECRET', '');
			$API_KEY = env('ZOOM_API_KEY', '');
			
			$signature = $this->generate_signature( $API_KEY, $API_SECRET, $agenda->zoom_code, $role);

			if(strlen($name)===0) {
				$name = 'guest01';
			}
			
			return view('zoom.zoomViewer',[
			  'name' => $name,
			  'mn'=> $agenda->zoom_code,
			  'email'=>$email,
			  'pwd'=> $agenda->zoom_password,
			  'role'=> $role,
			  'lang'=>'es-ES',
			  'signature'=>$signature,
			  'china'=>'0',
			  'apiKey'=>$API_KEY
			]);
		}
		else {
			return abort(404);
		}
    }
	

	public function generate_signature ( $api_key, $api_secret, $meeting_number, $role){

		$time = time() * 1000; //time in milliseconds (or close enough)
		$data = base64_encode($api_key . $meeting_number . $time . $role);
		$hash = hash_hmac('sha256', $data, $api_secret, true);
		$_sig = $api_key . "." . $meeting_number . "." . $time . "." . $role . "." . base64_encode($hash);
		//return signature, url safe base64 encoded
		return rtrim(strtr(base64_encode($_sig), '+/', '-_'), '=');
	}
}
