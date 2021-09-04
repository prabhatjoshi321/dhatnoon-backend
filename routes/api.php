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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('/login', 'App\Http\Controllers\Api\AuthController@login');
    Route::post('/signup', 'App\Http\Controllers\Api\AuthController@signup');

    Route::group([
        'middleware' => 'auth:api'
    ], function() {
        Route::get('/logout', 'App\Http\Controllers\Api\AuthController@logout');
        Route::post('/location_save', 'App\Http\Controllers\Api\LocationController@location_save');
        Route::post('/location_choice', 'App\Http\Controllers\Api\LocationController@location_choice');
        Route::post('/location_request', 'App\Http\Controllers\Api\LocationController@location_request');
        Route::post('/user_check', 'App\Http\Controllers\Api\LocationController@user_check');
        Route::get('/user_get', 'App\Http\Controllers\Api\AuthController@user_get');
    });
   // Route::get('/home', 'App\Http\Controllers\Api\HomeController@index')->name('home');
});
