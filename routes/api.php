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
Route::post('password/reset', 'PasswordResetController@reset');


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

Route::post('/logout', 'LoginController@logout');

// Get list of meetings.
Route::get('/meetings', 'Zoom\MeetingController@list');

// Create meeting room using topic, agenda, start_time.
Route::post('/meetings', 'Zoom\MeetingController@create')->middleware('role:super_administrador');

// Get information of the meeting room by ID.
Route::get('/meetings/{id}', 'Zoom\MeetingController@get')->where('id', '[0-9]+');
Route::patch('/meetings/{id}', 'Zoom\MeetingController@update')->where('id', '[0-9]+')->middleware('role:super_administrador');
Route::delete('/meetings/{id}', 'Zoom\MeetingController@delete')->where('id', '[0-9]+')->middleware('role:super_administrador');

//
Route::post('/fair/create', 'FairController@create')->middleware('role:super_administrador');
Route::get('/fair/to_list', 'FairController@to_list');
Route::get('/fair/find/{id?}', 'FairController@find');
Route::post('/fair/update/{fair_id}', 'FairController@update')->middleware('role:super_administrador');
//*
//
Route::post('/pavilion/create', 'PavilionController@create')->middleware('role:super_administrador');
Route::post('/pavilion/create2', 'PavilionController@create')->middleware('role:super_administrador');
Route::get('/pavilion/find_by_fair/{fair_id?}', 'PavilionController@find_by_fair');
Route::post('/pavilion/update/{pavilionId}', 'PavilionController@update')->middleware('role:super_administrador');
Route::post('/pavilion/delete/{pavilionId}', 'PavilionController@delete')->middleware('role:super_administrador');

//*
//
Route::post('/merchant/create', 'MerchantController@create');//->middleware('role:super_administrador');
Route::post('/merchant/update', 'MerchantController@update');//->middleware('role:super_administrador');
Route::get('/merchant/to_list/{fair_id}', 'MerchantController@to_list');
Route::post('/merchant/get_merchant', 'MerchantController@get_merchant');//->middleware('role:super_administrador');
//*
//
Route::post('/stand/create', 'StandController@create')->middleware('role:super_administrador');
Route::post('/stand/update/{stand_id}', 'StandController@update')->middleware('role:super_administrador');
Route::post('/stand/delete/{stand_id}', 'StandController@delete')->middleware('role:super_administrador');
Route::get('/stand/to_list/{pavilion_id?}', 'StandController@to_list');
//*
//
Route::post('/user/create', 'UserController@create');
Route::post('/user/update', 'UserController@update');
Route::get('/user/to_list', 'UserController@to_list')->middleware('role:super_administrador');
Route::get('/user/activate/account/{user_id}', 'UserController@activate_account');
//*
//
Route::post('/operator_user/create', 'OperatorUserController@create')->middleware('role:super_administrador');

//*
//
Route::post('/speakers/create/', 'SpeakerController@create');
Route::get('/speakers/meetings/{fair_id?}', 'SpeakerController@list');
Route::post('/speakers/update/', 'SpeakerController@update');
Route::post('/speakers/delete/', 'SpeakerController@delete');
Route::post('/speakers/meetings/{fair_id?}/{meeting_id?}', 'AgendaController@update_speakers')->middleware('role:super_administrador');

Route::get('/agenda/list/{fair_id?}/{pavilion_id?}/{stand_id?}', 'AgendaController@list');

// super admin or admin role rules
Route::get('/agenda/getEmails/{fair_id}/{agenda_id}', 'AgendaController@getEmails')->middleware('role:super_administrador');

// super admin role rules
Route::post('/category/create', 'CategoryController@create')->middleware('role:super_administrador');
Route::post('/category/update', 'CategoryController@update')->middleware('role:super_administrador');
Route::post('/category/delete', 'CategoryController@delete')->middleware('role:super_administrador');
Route::get('/category/to_list/{fair_id}/{type}', 'CategoryController@to_list');
//*


Route::post('/audience/meetings/{fair_id?}/{meeting_id?}', 'AgendaController@update_audience');
Route::get('/meeting/generate-video-token/{fair_id?}/{meeting_id?}', 'AgendaController@generateVideoToken');

Route::post('/payment/generate', 'PaymentController@createNewReference');
Route::post('/payment/user/fair', 'PaymentController@getPaymentUser');


//contact support
Route::post('/fair/contactsupport/notification', 'ContactSupportController@notification');
Route::post('/stand/contactsupport/notification', 'ContactSupportController@notification');


//product
Route::get('/product/find_by/{fair_id?}/{pavilion_id?}/{stand_id?}/{product_id?}', 'ProductController@findBy');
Route::post('/product/create', 'ProductController@create');
Route::post('/product/update/{product_id}', 'ProductController@update');
Route::post('/product/delete/{product_id}', 'ProductController@delete');

//product price
Route::post('/product-price/create', 'ProductPriceController@create');
Route::post('/product-price/update/{product_price_id}', 'ProductPriceController@update');
Route::post('/product-price/delete/{product_price_id}', 'ProductPriceController@delete');


Route::post('/store/shopping-cart/{fair_id}', 'ShoppingCartController@store');
Route::get('/list/shopping-cart/{fair_id}', 'ShoppingCartController@list');
Route::get('/find/shopping-cart/{id}', 'ShoppingCartController@find');
Route::post('/update/shopping-cart/', 'ShoppingCartController@update');

