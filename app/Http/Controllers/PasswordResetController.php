<?php

namespace App\Http\Controllers;

use App\Models\PasswordReset;
use App\Models\User;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    //
    /**
     * Create token password reset
     *
     * @param  [string] email
     * @return [string] message
     */
    public function create(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'origin' => 'string',
        ]);


        $user = User::where('email', $request->email)->first();
        if (!$user) {
          return response()->json(['message' => 'No se pudo encontrar un usuario con ese dirección de correo.'], 404);
        } 
        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => Str::random(60)
             ]
        );
        if ($user && $passwordReset) {
          $user->notify(  new PasswordResetRequest($passwordReset->token, $request->origin) );
          return response()->json([
            'success' => 201, 
            'message' => 'Hemos enviado un correo electrónico con el enlace de restablecimiento de contraseña.!'
          ]);
        }
        else {
            return response()->json(['message' => 'Error enviando el correo electrónico .'], 403);
        }
    }
    /**
     * Find token password reset
     *
     * @param  [string] $token
     * @return [string] message
     * @return [json] passwordReset object
     */
    public function find($token)
    {
        $passwordReset = PasswordReset::where('token', $token)
            ->first();
        if (!$passwordReset)
            return response()->json([
                'message' => 'Este token de restablecimiento de contraseña no es válido, por favor solicita de nuevo la recuperación de clave.'
            ], 404);
        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();
            return response()->json([
                'message' => 'Este token de restablecimiento de contraseña ha caducado, por favor solicita de nuevo la recuperación de clave.'
            ], 404);
        }
        return response()->json([
            'data' => $passwordReset,
            'status' => 'successfull',
        ],200);
    }
     /**
     * Reset password
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @param  [string] token
     * @return [string] message
     * @return [json] user object
     */
    public function reset(Request $request)
    {

        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|confirmed',
            'token' => 'required|string'
        ]);

        $passwordReset = PasswordReset::where([
            ['token', $request->token],
            ['email', $request->email]
        ])->first();
        if (!$passwordReset)
            return response()->json([
                'message' => 'Este token de restablecimiento de contraseña no es válido.'
            ], 404);
        $user = User::where('email', $passwordReset->email)->first();
        if (!$user)
            return response()->json([
                'message' => 'No se puedo encontrar un usuario con ese dirección de correo.'
            ], 404);
        $user->password = Hash::make($request->password);
        $user->save();
        $passwordReset->delete();
        $user->notify(new PasswordResetSuccess($passwordReset));


        return response()->json([
            'data' => $user,
            'status' => 'successfull',
        ],200);
    }
    
}
