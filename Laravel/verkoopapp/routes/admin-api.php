<?php

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

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


Route::middleware('auth:admin')->get('/admin', function (Request $request) {
    return $request->user();
});


Route::group([
    'middleware' => 'admin'
], function () {

    Route::post('Adminlogin', 'Api\AuthController@login');
    Route::post('logout', 'Api/AuthController@logout');
    Route::post('refresh', 'Api/AuthController@refresh');
    Route::post('me', 'Api/AuthController@me');

});