<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('register','App\Http\Controllers\Api\AuthController@register');
Route::post('login','App\Http\Controllers\Api\AuthController@login');
Route::get('email/verify/{id}', 'App\Http\Controllers\Api\VerificationApiController@verify')->name('verificationapi.verify');
Route::get('email/resend', 'App\Http\Controllers\Api\VerificationApiController@resend')->name('verificationapi.resend');


Route::group(['middleware' => 'auth:api'],function(){
   Route::get('travelling', 'App\Http\Controllers\Api\TravellingController@index');
    Route::get('travelling/{id}', 'App\Http\Controllers\Api\TravellingController@show');
    Route::post('travelling', 'App\Http\Controllers\Api\TravellingController@store');
    Route::put('travelling/{id}', 'App\Http\Controllers\Api\TravellingController@update');
    Route::delete('travelling/{id}', 'App\Http\Controllers\Api\TravellingController@destroy');
    
    Route::get('logout','Api\AuthController@logout');
    Route::get('detailuser','Api\AuthController@detailUser');

    Route::post('details', 'Api\AuthController@details')->middleware('verified');
    
});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
