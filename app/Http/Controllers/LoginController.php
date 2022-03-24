<?php

namespace App\Http\Controllers;

use App\Models\Fair;
use App\Models\RoleUserFair;
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
            'password' => ['required'],
            'fair_id' => ['required'],
        ]);


        if($request->fair_id == "admin") {
			$user = User::with('user_roles_fair')->whereHas('user_roles_fair',function($query){
				$query->where('role_id', 1); //superAdmin
			})->where('email',$request->email)->first();
		}
		else {
			$fair = Fair::find($request->fair_id);
			if($fair == null){
				throw ValidationException::withMessages([
					'fair'=>['No se encontro la feria']
				]);
			}
			$user = User::with('user_roles_fair')->whereHas('user_roles_fair',function($query)use($fair){
				$query->where('fair_id',$fair->id);
			})->where('email',$request->email)->first();
		}

		if( !$user || !\Illuminate\Support\Facades\Hash::check($request->password, $user->password) ){

			throw ValidationException::withMessages([
				'email'=>['Credenciales incorrectas']
			]);
		}

		return response()->json(['data' => $user->createToken('Auth Token')->accessToken, 'message' => 'Token generado satisfactoriamente', 'user' => $user], 200);			
	

    }

    public function logout(Request $request){
        if($request->user()) {
           $request->user()->tokens()->delete();
        }

        return response()->json(['status'=>'successfull']);
    }


}
