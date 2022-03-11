<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\ShoppingCart;
use File;
use Illuminate\Support\Facades\App;

class WompiController extends Controller
{
    public function index (Request $request) {
        
        ///LOG
        $input = $request->all();
        $id = isset($input['id']) ? $input['id'] : $request->id;
        $log_path = public_path(). '/payments-logs/';
        File::isDirectory($log_path) or File::makeDirectory($log_path, 0777, true, true);
        $file = 'payment-bash-'.date('YmdHis').'.txt';
        $this->writeLog($log_path.'/'.$file,'----- Wompi Id ['.$id.']');
        $this->writeLog($log_path.'/'.$file,$request);
        
        $test = new TestApiWompiController();
        $request->id = $input['id'];
        $response = $test->auth($request, 'php');
		if(isset($response['sucess'])) {
          $reference = $response['sucess']['data']['reference'];
		}
		else {
		   dd($response);
		}
        
        $validateShopping = ShoppingCart::with('fair')->where('references_id',$reference)->first();
        $references_id = $validateShopping->references_id;
        
        $environment = App::environment();
        if (App::environment('local')) {
            $href = 'http://localhost:8100/payment/' . $references_id;
        }
        else {
			dd($href);
            $href = 'https://' . $validateShopping->fair->name . '.e-logic.com.co/Fair-website/#/payment/' . $reference;
        }

       return view('wompi.paymentViewer',['location'=>$href]);
       
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
}

