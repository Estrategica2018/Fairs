<?php


use Illuminate\Http\Request;

Route::group([
    //'namespace' => 'Auth',
    //'middleware' => 'api',
    //'prefix' => 'password'
], function () {


});
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
use Illuminate\Support\Facades\Route;
Route::post('password/create', 'PasswordResetController@create');
Route::get('password/find/{token}', 'PasswordResetController@find');
Route::post('password/resett', 'PasswordResetController@reset');
Route::get('/', function () {
    return [
        'result' => true,
    ];
});
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Login
Route::post('/login', 'LoginController@login');

Route::post('/logout', 'LoginController@logout')->middleware('auth:api');

// Get list of meetings.
Route::get('/meetings', 'Zoom\MeetingController@list');

// Create meeting room using topic, agenda, start_time.
Route::post('/meetings', 'Zoom\MeetingController@create');

// Get information of the meeting room by ID.
Route::get('/meetings/{id}', 'Zoom\MeetingController@get')->where('id', '[0-9]+');
Route::patch('/meetings/{id}', 'Zoom\MeetingController@update')->where('id', '[0-9]+');
Route::delete('/meetings/{id}', 'Zoom\MeetingController@delete')->where('id', '[0-9]+');

//
Route::post('/fair/create', 'FairController@create');
Route::get('/fair/to_list', 'FairController@to_list');
Route::get('/fair/find/{id?}', 'FairController@find');

//*
//
Route::post('/pavilion/create', 'PavilionController@create');
Route::get('/fair/find_by_fair/{fair_id?}', 'PavilionController@find_by_fair');

//*
//
Route::post('/merchant/create', 'MerchantController@create');
Route::post('/merchant/update', 'MerchantController@update');
Route::get('/merchant/to_list', 'MerchantController@to_list');
//*
//
Route::post('/stand/create', 'StandController@create');
Route::post('/stand/update', 'StandController@update');
Route::get('/stand/to_list', 'StandController@to_list');
//*
//
Route::post('/user/create', 'UserController@create');
Route::post('/user/update', 'UserController@update');
Route::get('/user/to_list', 'UserController@to_list');
//*
//
Route::post('/operator_user/create', 'OperatorUserController@create');

//*
//
Route::get('/speakers/meetings', 'SpeakerController@list');


