<?php
namespace App\Http\Controllers;

use App\Models\Merchant;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ShoppingCart;
use App\Notifications\AccountRegistration;
use App\Notifications\Conference\SuccessfulRegistration;
use App\Notifications\SuccessfullPayment;
use App\Notifications\SuccessfulPaymentMerchant;
use App\Notifications\UnsuccessfulPayment;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Notification;

class TestApiWompiController extends Controller
{
    //
    public function auth(Request $request, $returnAction = 'html')
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://sandbox.wompi.co/v1/transactions/" . $request->id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer pub_test_EbunIjUmrCtIyrh28fFqr9sFUVqI43XA",
                "content-type: application/json"
            ) ,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err)
        {
            if ($returnAction == 'html')
            {
                echo "CURL Error #:" . $err;
            }
            else
            {
                return ["error" => $err];
            }
        }
        else
        {
            $response = json_decode($response, true);
            if (isset($response['error']))
            {
                if ($returnAction == 'html')
                {
                    echo "CURL Error #:" . $response;
                }
                else
                {
                    return ["error" => $response['error']];
                }
            }
            else
            {
				
                if ($returnAction == 'html')
                {
                    echo "CURL Susscess #:" . $response;
                }
                else
                {
                    if (isset($response) && $response)
                    {
						dd($response['data']);
                        if ($response['data']['status'] == 'APPROVED')
                        {
                            $payment = Payment::whereHas('user')->with('user')
                                ->where('reference', $response['data']['reference'])->first();

                            $payment->payment_status = 3;
                            $updateTime = new \DateTime();
                            $payment_date = $updateTime->format("Y-m-d H:i:s");
                            $payment->payment_date = $payment_date;
                            $payment->save();

                            $validateShopping = ShoppingCart::where([['references_id', $response['data']['reference']]])->first();
                            if ($validateShopping && $validateShopping->state == 'N')
                            {
                                $update = ShoppingCart::where([['references_id', $response['data']['reference']], ['state', 'N']])->update(['state' => 'P']);
                            }
                            $payment_agenda = false;
                            try
                            {
                                //$shoppingCart = ShoppingCart::with('product.stand.merchant', 'productPrice', 'agenda.fair')->where('references_id', $response['data']['reference'])->get();
                                $shoppingCart = ShoppingCart::with('product.stand.merchant', 'fair', 'productPrice', 'agenda.fair')->where('references_id', $response['data']['reference'])->get()
                                    ->unique('product_id');
							    
								$totalPrice = 0; 
								foreach ($shoppingCart as $data)
                                {
                                    $totalPrice += intval($data->price);
                                    if ($data->agenda != null) {
                                        $payment_agenda = true;
                                    }
                                }

                                ///try{
                                ///    Notification::route('mail', $payment->user->email)
                                ///        ->notify(new SuccessfulRegistration($shoppingCart));
                                ///}catch (\Exception $e){
                                ///    return response()->json(['message' => 'Error enviando el correo electrónico .'.' '.$e], 403);
                                ///}
                                ///
                                
                                if($payment_agenda){
								}
								else { 
                                    try{
										$fairIcon = json_decode($shoppingCart[0]->fair->social_media)->icon;
										Notification::route('mail', $payment->user->email)
                                            ->notify(new SuccessfullPayment($fairIcon, $response['data'],$shoppingCart ,$totalPrice));
                                    }catch (\Exception $e){
                                        return response()->json(['message' => 'Error enviando el correo electrónico .'.' '.$e], 403);
                                    }
                                }
								
                               $array_merchant = [];
                               foreach ($shoppingCart as $merchant)
                               {
                                   if ($merchant->product != null)
                                   {
                                       if (!in_array($merchant
                                           ->product
                                           ->stand
                                           ->merchant->id, $array_merchant))
                                       {
                                           array_push($array_merchant, $merchant
                                               ->product
                                               ->stand
                                               ->merchant
                                               ->id);
                                       }
                                   }
							   
                               }
                               $merchant_users = Merchant::with('stand')->whereIn('id', $array_merchant)->get();
                               $merchant_data = [];
                               foreach ($merchant_users as $index => $merchant_user)
                               {
                                   $temp_array = ['name' => $merchant_user->name, 'total' => 0];
                                   $merchant_list_products = Product::whereIn('stand_id', $merchant_user
                                       ->stand
                                       ->pluck('id'))
                                       ->get();
                                   $merchant_list_products_ids = Product::whereIn('stand_id', $merchant_user
                                       ->stand
                                       ->pluck('id'))
                                       ->get()
                                       ->pluck('id')
                                       ->toArray();
							   
                                   $shoppingCart = ShoppingCart::where('references_id', $response['data']['reference'])->get();
                                   $total = 0;
                                   foreach ($shoppingCart as $product)
                                   {
                                       if ($product->product_id) if (in_array($product->product_id, $merchant_list_products_ids))
                                       {
                                           $total += $merchant_list_products->where('id', $product->product_id)
                                               ->first()->price * $product->amount;
                                       }
                                   }
                                   $temp_array['total'] = $total;
								   
                                   array_push($merchant_data, $temp_array);
                                   //dd($merchant_user->email_contact);
                                   Notification::route('mail', $merchant_user->email_contact)
                                       ->notify(new SuccessfulPaymentMerchant($response['data'], $temp_array));
                               }
							   
                               $payment->flag_notify = true;
                               $payment->save();

                            }
                            catch(\Exception $e)
                            {
                                return response()->json(['message' => 'Error enviando el correo electrónico .' . ' ' . $e], 403);
                            }
                        }
                        else if ($response['data']['status'] == 'DECLINED')
                        {
                            //if($user){
                            $payment = Payment::whereHas('user')->with('user')
                                ->where('reference', $response['data']['reference'])->first();
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
                            try
                            {
                                Notification::route('mail', $payment
                                    ->user
                                    ->email)
                                    ->notify(new UnsuccessfulPayment($response['data']));
                                $payment->flag_notify = true;
                                $payment->save();

                            }
                            catch(\Exception $e)
                            {
                                return response()->json(['message' => 'Error enviando el correo electrónico .' . ' ' . $e], 403);
                            }
                            //}
                            //}else{
                            //    return response()->json(['message' => 'La sesión ha cadudcado.'], 403);
                            // }
                            
                        }

                    }
					
                    return ["success" => $response];
                }
            }
        }
    }
}

