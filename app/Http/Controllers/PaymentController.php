<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ShoppingCart;
use App\Models\Payment;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    
    //
    /**
     * Create and generate reference for payment.
     *
     * @param  [string] type
     * @param  [string] id
     * @return [json] reference
     */
    public function createNewReference(Request $request)
    {
        
       $request->validate([
            'type' => 'required',
            'id' => 'required'
        ]);

        $user = auth()->guard('api')->user();
        if($user) {

            $payment = Payment::where([['user_id',$user->id],['type_order',$request->type],['code_item_order',$request->id],['payment_status',1]])->first();

            if(!$payment) {
                $payment = new Payment();
                $payment->user_id = $user->id;
                $payment->type_order = $request->type;
                $payment->code_item_order = $request->id;
                $payment->reference = time(). '-' . $user->id  .'-'. Str::random(2);
                $payment->payment_status = 1;
                $payment->save();
            }
            else {
                $test = new TestApiWompiController();
                $request->id = $payment->reference;
                $response = $test->auth($request, 'php');
                if(isset($response['error']) && $response['error']['type']=='NOT_FOUND_ERROR') {
                    $payment->reference = time(). '-' . $user->id  .'-'. Str::random(2);
                    $payment->save();
                } else {
                    dd('error',$response);
                }
            }
            
            $update = ShoppingCart::where([['user_id',$user->id],['state','N']])
            ->update(['references_id' => $payment->reference]);
            
            return response()->json([
              'success' => 201, 
              'publicKey'=>'pub_test_EbunIjUmrCtIyrh28fFqr9sFUVqI43XA',
              'reference'=> $payment->reference,
              'currency'=>'COP',
              'message' => 'Referencia de pago tipo ['.$request->type.'] codigo de producto ['.$request->id.']!'
            ]);

        }
        else {
           return response()->json(['message' => 'La sesión ha cadudcado.'], 403);
        }
    }
    
    public function getPaymentUser(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'id' => 'required'
        ]);

        $user = auth()->guard('api')->user();
        
        if ($user) {
          if($request->type == 'Event') {
            $payment = Payment::
            //with(['shoppingCart' => function ($query) use ($request) {
               //$query->where('references_id',$request->id);
            //}])
            with('shoppingCart')
            ->whereHas('shoppingCart',function ($query) use ($request) {
              $query->where('agenda_id', $request->id);
            })
            ->where(['user_id'=>$user->id,'payment_status'=>3])
            ->first();
            
          }
          else {
            $payment = Payment::where(['user_id'=>$user->id,'type_order'=>$request->type,'code_item_order'=>$request->id,'payment_status'=>3])->first();
          }
          
          if($payment){
            return response()->json([
            'success' => 201, 
            'message' => 'payment '.$payment->reference.'!'
            ]);  
          } else {
              return response()->json([
                'error' => 403,
                'message' => 'No se encontró pagos para ['.$request->type.']='.$request->id.' .'
              ]);
          }
        }
        else {
          return response()->json([
            'error' => 403, 
            'message' => 'La sesión ha caducado'
          ]);
        }
    }
}
