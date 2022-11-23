<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/index.html', function () {
	if(session_status() !== PHP_SESSION_ACTIVE) session_start();
	return view('redirect',['location' => $_SESSION["newFair"]]);
});


//zoom viewer client
Route::get('viewerZoom/meetings/{token}', 'Zoom\ViewerZoomController@index');
Route::get('viewerZoom/saveResgister/{fair_id}/{agenda_id}', 'Zoom\ViewerZoomController@saveResgister');
Route::get('viewerZoom/callback/callback.php', 'Zoom\ViewerZoomController@callback');
Route::get('zoomverify/verifyzoom.html', function () { return 'bb48983b33b04b52b459a74ad4570e69'; } );

//WOMPI
Route::get('wompi/pagos/eventos/{id}', 'WompiController@index');
Route::get('wompi/pagos/eventos', 'WompiController@index');
Route::get('user/mincultura/nofify', 'MinculturaUserController@notify');