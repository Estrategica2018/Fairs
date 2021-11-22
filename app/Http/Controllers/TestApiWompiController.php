<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestApiWompiController extends Controller
{
    //
    public function auth(Request $request, $returnAction = 'html'){

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://sandbox.wompi.co/v1/transactions/".$request->id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer pub_test_EbunIjUmrCtIyrh28fFqr9sFUVqI43XA",
                "content-type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            if($returnAction == 'html') { echo "CURL Error #:" . $err; }
            else { return ["error"=>$err]; }
        } else {
            $response = json_decode($response,true);
            if(isset($response['error'])){
              if($returnAction == 'html') { echo "CURL Error #:" . $response; }
              else { return ["error"=>$response['error']]; }
            }
            else {
              if($returnAction == 'html') { echo "CURL Susscess #:" . $response; }
              else {
                if(isset($response) && $response && $response['sucess']) {
                  if($response['sucess']['data']['status'] == 'APPROVED') {
                    $payment = Payment::where('reference',$response['sucess']['data']['reference'])->first();
                    $payment->payment_status = 3;
                    $payment->save();
                    $validateShopping = ShoppingCart::where([['references_id',$response['sucess']['data']['reference']],['state','N']])->first();
                    if($validateShopping && $validateShopping->state == 'N') {
                      $update = ShoppingCart::where([['references_id',$response['sucess']['data']['reference']],['state','N']])
                      ->update(['state' => 'P' ]);
                    }
                  }
                }
                return ["sucess"=>$response]; 
              }
            }
        }
    }
}
