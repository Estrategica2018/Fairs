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
Route::get('/pavilion/find_by_fair/{fair_id?}', 'PavilionController@find_by_fair');
Route::post('/pavilion/update/{pavilionId}', 'PavilionController@update');
Route::post('/pavilion/delete/{pavilionId}', 'PavilionController@delete');

//*
//
Route::post('/merchant/create', 'MerchantController@create');
Route::post('/merchant/update', 'MerchantController@update');
Route::get('/merchant/to_list/{fair_id}', 'MerchantController@to_list');
//*
//
Route::post('/stand/create', 'StandController@create');
Route::post('/stand/update/{stand_id}', 'StandController@update');
Route::post('/stand/delete/{stand_id}', 'StandController@delete');
Route::get('/stand/to_list/{pavilion_id?}', 'StandController@to_list');
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
Route::get('/speakers/meetings/{fair_id?}', 'SpeakerController@list');
Route::post('/speakers/meetings/{fair_id?}/{meeting_id?}', 'AgendaController@update_speakers');

Route::get('/agenda/list/{fair_id?}/{pavilion_id?}/{stand_id?}', 'AgendaController@list');

// super admin or admin role rules
Route::get('/agenda/getEmails/{fair_id}/{agenda_id}', 'AgendaController@getEmails');

// super admin role rules
Route::post('/category/create', 'CategoryController@create');
Route::post('/category/update', 'CategoryController@update');
Route::post('/category/delete', 'CategoryController@delete');
Route::get('/category/to_list/{type}', 'CategoryController@to_list');
//*

// super admin role rules
Route::post('/fair/create', 'FairController@create');
Route::post('/fair/update/{fair_id}', 'FairController@update');
Route::post('/fair/delete', 'FairController@delete');

Route::post('/audience/meetings/{fair_id?}/{meeting_id?}', 'AgendaController@update_audience');
Route::get('/meeting/generate-video-token/{fair_id?}/{meeting_id?}', 'AgendaController@generateVideoToken');

