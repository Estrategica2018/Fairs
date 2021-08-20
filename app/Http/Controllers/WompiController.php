<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use File;

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

		return view('wompi.paymentViewer');
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

