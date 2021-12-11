<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\ShoppingCart;
use App\Notifications\AccountRegistration;
use App\Notifications\SuccessfulPayment;
use App\Notifications\SuccessFulPaymentMechant;
use App\Notifications\UnsuccessfulPayment;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Notification;

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
                if(isset($response) && $response) {
                    //dd($response);
                  if($response['data']['status'] == 'APPROVED') {
                    $payment = Payment::where('reference',$response['data']['reference'])->first();
                    $payment->payment_status = 3;
                    $payment->save();
                    $validateShopping = ShoppingCart::where([['references_id',$response['data']['reference']],['state','N']])->first();
                    if($validateShopping && $validateShopping->state == 'N') {
                      $update = ShoppingCart::where([['references_id',$response['data']['reference']],['state','N']])
                      ->update(['state' => 'P' ]);
                    }
                      //if(!$payment->flag_notify){
                          //enviar notificación usuario que pago
                          //validar si tiene envio
                          //enviar notificación responsable del local
                        //$user = auth()->guard('api')->user();
                        //if($user){
                            try{
                                Notification::route('mail', 'jesaleja07@hotmail.com')
                                    ->notify(new SuccessfulPayment($response['data'] ));

                                /*ShoppingCart::with()->where('references_id',$response['data']['reference'])->get();
                                Notification::route('mail', 'cristianjojoa01@gmail.com')
                                    ->notify(new SuccessfulPaymentMechant($response['data'] ));
                                */
                                $payment->flag_notify = true;
                                $payment->save();
                                //dd('bien');

                            }catch (\Exception $e){
                                //dd('mal',$e);
                                return response()->json(['message' => 'Error enviando el correo electrónico .'.' '.$e], 403);
                            }
                        /*}else{
                            return response()->json(['message' => 'La sesión ha cadudcado.'], 403);
                        }*/

//                      }
                  }else if($response['data']['status'] == 'DECLINED') {
                     // dd($response['data']['status'] == 'DECLINED',$response['data']);
                        //$user = auth()->guard('api')->user();
                        //if($user){
                            $payment = Payment::where('reference',$response['data']['reference'])->first();
                            $payment->payment_status = 2;
                            $payment->save();
                            /*$validateShopping = ShoppingCart::where([['references_id',$response['data']['reference']],['state','N']])->first();
                            if($validateShopping && $validateShopping->state == 'N') {
                                $update = ShoppingCart::where([['references_id',$response['data']['reference']],['state','N']])
                                    ->update(['state' => 'P' ]);
                                //enviar notificación
                            }
                            */
                            //if(!$payment->flag_notify){
                            //enviar notificación rechazo
                            try{
                                Notification::route('mail','jesaleja07@hotmail.com' )
                                    ->notify(new UnsuccessfulPayment($response['data'] ));
                                $payment->flag_notify = true;
                                $payment->save();
                                dd('bien');

                            }catch (\Exception $e){
                                dd('mal',$e);
                                return response()->json(['message' => 'Error enviando el correo electrónico .'.' '.$e], 403);
                            }
                            //}
                      //}else{
                        //    return response()->json(['message' => 'La sesión ha cadudcado.'], 403);
                       // }

                      }

                }
                return ["sucess"=>$response]; 
              }
            }
        }
    }
}
