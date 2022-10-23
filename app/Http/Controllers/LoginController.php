<?php

namespace App\Http\Controllers;

use App\Models\Fair;
use App\Models\RoleUserFair;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use phpseclib\Crypt\Hash;
use App\Models\OauthAccessTokens;

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

		$auth = OauthAccessTokens::where('user_id',$user->id)->orderBy('created_at', 'desc')->first();
		$token = $user->createToken('Auth Token');
		$accessToken = $token->accessToken;
		$token_id = $token->token->id;
		return response()->json(['data' => $accessToken, 'message' => 'Token generado satisfactoriamente', 'user' => $user, 'auth' => $token_id], 200);
	}

    public function logout(Request $request){
        if($request->user()) {
           $request->user()->tokens()->delete();
        }

        return response()->json(['status'=>'successfull']);
    }


}
