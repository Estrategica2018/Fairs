<?php

namespace App\Http\Controllers;

use App\Notifications\Fair\ContactSupportRequest as ContactSupportRequestFair;
use App\Notifications\Stand\ContactSupportRequest as ContactSupportRequestStand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class ContactSupportController extends Controller
{
    //

    public function notification_support_fair (Request $request) {

        $request->validate([
            'name' => ['required'],
            'email' => ['required','email'],
            'message' => ['required'],
        ]);


        try{
            Notification::route('mail', $request->send_to)
                ->notify(new ContactSupportRequestFair($request));
            return response()->json([
                'success' => 201,
                'message' => 'Hemos enviado un correo electrónico al grupo de soporte'
            ]);
        }catch (\Exception $e){
            return response()->json(['message' => 'Error enviando el correo electrónico .'.' '.$e], 403);
        }

    }

    public function notification_support_stand (Request $request) {

        $request->validate([
            'name' => ['required'],
            'email' => ['required','email'],
            'message' => ['required'],
        ]);


        try{
            Notification::route('mail', 'cristianjojoa01@gmail.com')
                ->notify(new ContactSupportRequestStand($request));
            return response()->json([
                'success' => 201,
                'message' => 'Hemos enviado un correo electrónico al grupo de soporte'
            ]);
        }catch (\Exception $e){
            return response()->json(['message' => 'Error enviando el correo electrónico .'], 403);
        }

    }
}
