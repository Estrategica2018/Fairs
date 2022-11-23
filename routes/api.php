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
Route::get('/token_zak/{user_id}', 'Zoom\MeetingController@token_zak');

// Create meeting room using topic, agenda, start_time.
Route::post('/meetings', 'Zoom\MeetingController@create')->middleware('role:super_administrador');

// Get information of the meeting room by ID.
Route::get('/meetings/{id}', 'Zoom\MeetingController@get');
Route::patch('/meetings/{id}', 'Zoom\MeetingController@update')->middleware('role:super_administrador');
Route::post('/meetings/delete/{id}', 'Zoom\MeetingController@delete')->middleware('role:super_administrador');

//
Route::post('/fair/create', 'FairController@create')->middleware('role:super_administrador');
Route::get('/fair/list_all', 'FairController@list_all')->middleware('role:super_administrador');
Route::get('/fair/to_list/{fair_name}', 'FairController@to_list');
Route::get('/fair/find/{id?}', 'FairController@find');
Route::post('/fair/update/{fair_id}', 'FairController@update')->middleware('role:super_administrador');
Route::post('/fair/delete/{fair_id}', 'FairController@delete')->middleware('role:super_administrador');
Route::post('/fair/add-admin/{fair_id}', 'FairController@addAdmin')->middleware('role:super_administrador');
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
Route::get('/user/delete/{email}/{type}', 'UserController@delete');//->middleware('role:super_administrador');
Route::get('/user/to_list', 'UserController@to_list')->middleware('role:super_administrador');
Route::get('/user/activate/account/{user_id}', 'UserController@activate_account');
Route::get('/user/find/{email}', 'UserController@find');
Route::get('/user/sendSignConfirm/notify/{fairName}/{email}', 'UserController@notifyConfirmEmail');
Route::get('/user/sendSignConfirm/validate/{email}/{code}', 'UserController@validateConfirmEmail');
Route::get('/user/sendSignConfirm/reset/{email}', 'UserController@resetNotifyConfirmEmail');
Route::get('/user/mincultura/{fair_id}', 'MinculturaUserController@index');
Route::post('/user/mincultura/register/{fair_id}', 'MinculturaUserController@register');
Route::get('/user/mincultura/show-register/{fair_id}', 'MinculturaUserController@showRegister');
Route::get('user/mincultura/nofify', 'MinculturaUserController@notify');
//*
//
Route::post('/operator_user/create', 'OperatorUserController@create')->middleware('role:super_administrador');

//*
//
Route::post('/speakers/create/', 'SpeakerController@create')->middleware('role:super_administrador');
Route::get('/speakers/meetings/{fair_id?}', 'SpeakerController@list');
Route::post('/speakers/update/', 'SpeakerController@update')->middleware('role:super_administrador');
Route::post('/speakers/delete/', 'SpeakerController@delete')->middleware('role:super_administrador');
Route::post('/speakers/meetings/{fair_id?}/{meeting_id?}', 'AgendaController@update_speakers')->middleware('role:super_administrador');

//create masive speakers
//Route::post('speakers/upload', 'SpeakerController@uploadFile');
//return all agenda list
Route::get('/agenda/list/{fair_id?}/{pavilion_id?}/{stand_id?}', 'AgendaController@list');
Route::get('/agenda/live/{fair_id?}', 'AgendaController@live');
//returns agenda list with quota enabled
Route::get('/agenda/available/list/{fair_id}/{agenda_id?}', 'AgendaController@availableList');
// super admin or admin role rules
Route::get('/agenda/getEmails/{fair_id}/{agenda_id}', 'AgendaController@getEmails')->middleware('role:super_administrador');
Route::get('/agenda/register/{fair_id}/{agenda_id}', 'AgendaController@register');

// super admin role rules
Route::post('/category/create', 'CategoryController@create');//->middleware('role:super_administrador');
Route::post('/category/update', 'CategoryController@update');//->middleware('role:super_administrador');
Route::post('/category/delete', 'CategoryController@delete')->middleware('role:super_administrador');
Route::get('/category/to_list/{fair_id}/{type}', 'CategoryController@to_list');
Route::get('/category/get/{category_id}', 'CategoryController@get');

Route::post('/subcategory/create', 'CategoryController@create_sub_category');//->middleware('role:super_administrador');
Route::post('/subcategory/update', 'CategoryController@update_sub_category');
Route::get('/subcategory/get/{category_id}', 'CategoryController@get_sub_category');
//*


Route::post('/audience/meetings/{fair_id?}/{meeting_id?}', 'AgendaController@update_audience');
Route::get('/meeting/generate-meeting-token/{fair_id}/{meeting_id}', 'AgendaController@generateMeetingToken');

Route::post('/payment/generate', 'PaymentController@createNewReference');
Route::post('/payment/user/fair', 'PaymentController@getPaymentUser');


//contact support
Route::post('/fair/contactsupport/notification', 'ContactSupportController@notification_support_fair');
Route::post('/stand/contactsupport/notification', 'ContactSupportController@notification_support_stand');


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
Route::get('/find/shopping-cart/{fair_id}/{reference_id}', 'ShoppingCartController@find');
Route::post('/update/shopping-cart/', 'ShoppingCartController@update');

Route::post('/wompi/auth/{id}', 'TestApiWompiController@auth');

//audience

Route::get('/audience/audience_user/{agenda_id}', 'AudienceController@audience_user');
Route::get('/audience/audience_users/{agenda_id}', 'AudienceController@audience_users');
