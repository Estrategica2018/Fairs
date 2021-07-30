<?php

namespace App\Http\Controllers;

use App\Notifications\ContactSupportRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class ContactSupportController extends Controller
{
    //

    public function notification (Request $request) {

        $request->validate([
            'name' => ['required'],
            'email' => ['required','email'],
            'message' => ['required'],
        ]);


        try{
            Notification::route('mail', $request->email)
                ->notify(new ContactSupportRequest($request));
            return response()->json([
                'success' => 201,
                'message' => 'Hemos enviado un correo electrónico al grupo de soporte'
            ]);
        }catch (\Exception $e){
            return response()->json(['message' => 'Error enviando el correo electrónico .'], 403);
        }



    }
}
