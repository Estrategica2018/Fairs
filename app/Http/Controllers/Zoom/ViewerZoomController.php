<?php

namespace App\Http\Controllers\Zoom;

use App\Models\Agendas;
use App\Models\InvitedSpeaker;
use App\Models\RoleUserFair;
use App\Models\Audience;
use App\Models\Fair;
use App\Models\OauthAccessTokens;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use File;

class ViewerZoomController extends Controller
{
   public function index (Request $request, $token = '') {  
		
        $audience = Audience::with('user')->where('token',$token)->first();
        $agenda_id = explode(".",$token)[1];
        $fair_id = explode(".",$token)[2];

        if(session_status() !== PHP_SESSION_ACTIVE) {
          session_start();
        }

        $user = null;
        if($audience) {
          $user = $audience->user;
          $agenda_id = $audience->agenda_id;
          $_SESSION['user'] = $user;
          $_SESSION['token'] = $token;
        }
        if($user) {

            $agenda = Agendas::with('invited_speakers.speaker.user','audience')->find($agenda_id);
            
            $email = $user->email;
            $name = $user->name .' '.$user->last_name;
            
            $role = '0';
            //admin validation
            $rol_admin = 1;
            $roleUser = RoleUserFair::where([['role_id',$rol_admin],['user_id',$user->id],['fair_id',$agenda->fair_id]])->first();
            if($roleUser) {
              $role = '1';
            }

            //dd([['role_id',$rol_admin],['user_id',$user->id],['fair_id',$agenda->fair_id]]);
            
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
                foreach($agenda->audience as $aud) {
                  if($aud->email === $email) {
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

            $fair = Fair::find($agenda->fair_id);
            if (App::environment('production')) {
              $href = 'https://'.$fair->name.'.e-logic.com.co/website/agenda/'.$agenda_id;
              $saveResgisterUrl = '/viewerZoom/saveResgister/'.$fair->id.'/'.$agenda_id;
            }
            else {
              $href = 'http://localhost:8100/agenda/' . $agenda_id;
              $saveResgisterUrl = '/viewerZoom/saveResgister/'.$fair->id.'/'.$agenda_id;
            }

            $opt = [
              'name' => $name,
              'mn'=> $agenda->zoom_code,
              'email'=>$email,
              'pwd'=> $agenda->zoom_password,
              'role'=> $role,
              'lang'=>'es-ES',
              'signature'=>$signature,
              'china'=>'0',
              'apiKey'=>$API_KEY,
              'url_redirect'=>$href
            ];
            
            $opt['saveResgisterUrl'] = $saveResgisterUrl;
            
            if($audience) {
              $audience->token = '';
              $audience->save();
            }

            $_SESSION["newFair"]=$href;

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
            
            return view('redirect',['location' => $href]);
        }
   }
    
   public function generate_signature ( $api_key, $api_secret, $meeting_number, $role){

        date_default_timezone_set("UTC");
        $time = time() * 1000 - 30000;//time in milliseconds (or close enough)
        $data = base64_encode($api_key . $meeting_number . $time . $role);
        $hash = hash_hmac('sha256', $data, $api_secret, true);
        $_sig = $api_key . "." . $meeting_number . "." . $time . "." . $role . "." . base64_encode($hash);
        //return signature, url safe base64 encoded
        return rtrim(strtr(base64_encode($_sig), '+/', '-_'), '=');
   }

   public function callback(Request $request){

     try {

        $client = new \GuzzleHttp\Client(['base_uri' => 'https://zoom.us']);
    
        $log_path = public_path(). '/payments-logs/';
        File::isDirectory($log_path) or File::makeDirectory($log_path, 0777, true, true);
        $file = 'zoom-'.date('YmdHis').'.txt';
        $this->writeLog($log_path.'/'.$file, $request);
        $code = '';
        if(isset($_GET['code'])) {
           $this->writeLog($log_path.'/'.$file, $_GET['code']);
           $code = $_GET['code'];
        }

        $response = $client->request('POST', '/oauth/token', [
          "headers" => [
              "Authorization" => "Basic ". base64_encode(env('CLIENT_ID', '').':'.env('CLIENT_SECRET', ''))
          ],
          'form_params' => [
              "grant_type" => "authorization_code",
              "code" => $code,
              "redirect_uri" => env('REDIRECT_URI', '')
          ],
        ]);
    
        $token = json_decode($response->getBody()->getContents(), true);
    
        echo "Access token inserted successfully " . $token;

      } catch(Exception $e) {
        echo $e->getMessage();
      }
   }

   public function writeLog($filename, $string) {

      if (!file_exists($filename)) {
          touch($filename, strtotime('-1 days'));
      }
      if(gettype($string) == "object") {
          $string = json_encode ($string,true);
      }
      if(gettype($string) == "array") {
          $string = json_encode($string);
      }
      file_put_contents($filename, $string . PHP_EOL, FILE_APPEND);
  }

  public function saveResgister(Request $request, $fair_id, $agenda_id) {

      $agenda = Agendas::where([['agenda_id',$agenda_id],['fair_id',$fair_id]]);
      if(!$agenda) {
        return [
          'error' => true,
          'message' => 'agenda no válida'
        ];
      }
      
      if(session_status() !== PHP_SESSION_ACTIVE) session_start();
      
      $user = null;
      $audience = null;
      if(isset($_SESSION["user"])) {
        $user = $_SESSION['user'];

        $audience = Audience::where([['user_id',$user->id], ['agenda_id',$agenda_id]])->first();
        
        $lastDate = substr($audience->updated_at,0,16);
        $newDate = date("Y-m-d").' '.date("H:i");;
        if($lastDate != $newDate) {
          $audience->attendance = $audience->attendance ?  $audience->attendance + 1 : 1;
          $audience->save();
        }
      
        return [
          'success' => true,
          'lastDate' => $lastDate,
          'newDate' => $newDate,
          'data' => $audience
        ];
      }
      else {
        return [
          'success' => false,
          'message' => 'session not found'
        ];
      }
      
  }

}
