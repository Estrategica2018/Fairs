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
	session_start();
	return view('redirect',['location' => $_SESSION["newFair"]]);
});


//zoom viewer client
Route::get('viewerZoom/meetings/{token}', 'Zoom\ViewerZoomController@index');

//WOMPI
Route::get('wompi/pagos/eventos/{id}', 'WompiController@index');
Route::get('wompi/pagos/eventos', 'WompiController@index');

