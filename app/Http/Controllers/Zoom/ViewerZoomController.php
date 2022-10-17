<?php

namespace App\Http\Controllers\Zoom;

use App\Models\Agendas;
use App\Models\InvitedSpeaker;
use App\Models\RoleUserFair;
use App\Models\Audience;
use App\Models\Fair;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;

class ViewerZoomController extends Controller
{
    public function index (Request $request, $token = '') {        
		
        $audience = Audience::with('user')->where('token',$token)->first();
        $agenda_id = explode(".",$token)[1];
        $fair_id = explode(".",$token)[2];
        
        if($audience) {

            $agenda = Agendas::with('invited_speakers.speaker.user','audience')->find($audience->agenda_id);
            
            $user = $audience->user;
            
            $email = $user->email;
            $name = $user->name .' '.$user->last_name;
            
            $role = '0';
            //admin validation
            $rol_admin = 1;
            $roleUser = RoleUserFair::where([['role_id',$rol_admin],['user_id',$user->id],['fair_id',$agenda->fair_id]])->first();
            if($roleUser) {
              $role = '1';
            }
            
            if($role == '0'){
              
              //Invited speakers validation
              foreach($agenda->invited_speakers as $invited_speaker) {
                if($invited_speaker->speaker->user->email === $email) {
                  $role = '1';
                  break;
                }
              }
                
              if($role == '0'){				
                  
                $valid = false;
                foreach($agenda->audience as $audience) {
                  if($audience->email === $email) {
                  $valid = true;
                  }
                }
                if(!$valid) {
                  return abort(401);
                }
              }
            }
			
            $API_SECRET = env('ZOOM_API_SECRET', '');
            $API_KEY = env('ZOOM_API_KEY', '');
            
            $signature = $this->generate_signature( $API_KEY, $API_SECRET, $agenda->zoom_code, $role);

            $opt = [
              'name' => $name,
              'mn'=> $agenda->zoom_code,
              'email'=>$email,
              'pwd'=> $agenda->zoom_password,
              'role'=> $role,
              'lang'=>'es-ES',
              'signature'=>$signature,
              'china'=>'0',
              'apiKey'=>$API_KEY
            ];
            
            if(!isset($_SESSION)) {
              session_start();
            }

			      $fair = Fair::find($agenda->fair_id);


            if (App::environment('production')) {
              $href = 'https://'.$fair->name.'.e-logic.com.co/website/agenda/'.$audience->agenda_id;
            }
            else {
              $href = 'http://localhost:8100/agenda/' . $audience->agenda_id;
            }

      			$_SESSION["newFair"]=$href;
            $audience->delete();

            return view('zoom.zoomViewer',$opt);
        }
        else {
            //$_SESSION["newFair"]='https://'.$fair->name.'.e-logic.com.co/website/#/agenda/';
            //return abort(404);

            $fair = Fair::find($fair_id);

            if (App::environment('production')) {
              $href = 'https://'.$fair->name.'.e-logic.com.co/website/agenda/'.$agenda_id;
            }
            else {
              $href = 'http://localhost:8100/agenda/' . $agenda_id;
            }
            
            $opt = [
              'name' => '',
              'mn'=> '',
              'email'=>'',
              'pwd'=> '',
              'role'=> '',
              'lang'=>'es-ES',
              'signature'=> '',
              'china'=>'0',
              'apiKey'=> '',
              'url_redirect'=> $href
            ];
            
            return view('zoom.zoomViewer',$opt);
        }
    }
    
    function generate_signature ( $api_key, $api_secret, $meeting_number, $role){

        date_default_timezone_set("UTC");
        $time = time() * 1000 - 30000;//time in milliseconds (or close enough)
        $data = base64_encode($api_key . $meeting_number . $time . $role);
        $hash = hash_hmac('sha256', $data, $api_secret, true);
        $_sig = $api_key . "." . $meeting_number . "." . $time . "." . $role . "." . base64_encode($hash);
        //return signature, url safe base64 encoded
        return rtrim(strtr(base64_encode($_sig), '+/', '-_'), '=');
   }
}
