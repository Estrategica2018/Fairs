<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use phpseclib\Crypt\Hash;

class LoginController extends Controller
{
    //
    /**
     * @OA\Get(
     *  path="/api/login",

 *  operationId="login",

 *  summary="Generación de token",

 *  @OA\Parameter(name="email",

 *    in="query",

 *    required=true,

 *    @OA\Schema(type="string")

 *  ),
 *  @OA\Parameter(name="password",

 *    in="query",

 *    required=true,

 *    @OA\Schema(type="string")

 *  ),

 *  @OA\Response(response="200",

 *    description="Validation Response",

 *  )

 * )

     */
    public function login (Request $request){

        $request->validate([
            'email' => ['required','email'],
            'password' => ['required']
        ]);
        $user = User::where('email',$request->email)->first();
        if( !$user || !\Illuminate\Support\Facades\Hash::check($request->password, $user->password) ){
            throw ValidationException::withMessages([
                'email'=>['Credenciales incorrectas']
            ]);
        }
        return response()->json(['data' => $user->createToken('Auth Token')->accessToken, 'message', 'Token generado satisfactoriamente'], 200);
        return $user->createToken('Auth Token')->accessToken;
    }

    public function logout(Request $request){

        $request->user()->tokens()->delete();

        return response()->json(['status'=>'successfull']);
    }


}
