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
        Route::get('/user', 'App\Http\Controllers\Api\AuthController@user');
        Route::get('/logout', 'App\Http\Controllers\Api\AuthController@logout');
        //location Stream from user device
        Route::post('/location_save', 'App\Http\Controllers\Api\LocationController@location_save');
        //Permission Controller
        Route::post('/make_request', 'App\Http\Controllers\Api\PermissionsController@make_request');
        Route::get('/approved_requests', 'App\Http\Controllers\Api\PermissionsController@approved_requests');
        Route::get('/request_select', 'App\Http\Controllers\Api\PermissionsController@request_select');
        Route::post('/get_user_location', 'App\Http\Controllers\Api\PermissionsController@get_user_location');
        Route::post('/allow_deny_controller', 'App\Http\Controllers\Api\PermissionsController@allow_deny_controller');
        //Camerastream Controller
        Route::post('/front_camera_post', 'App\Http\Controllers\Api\CamerastreamController@front_camera_post');
        Route::post('/rear_camera_post', 'App\Http\Controllers\Api\CamerastreamController@rear_camera_post');
        Route::get('/cam_request_check', 'App\Http\Controllers\Api\CamerastreamController@cam_request_check');
        Route::post('/get_user_frontcam', 'App\Http\Controllers\Api\CamerastreamController@get_user_frontcam');
        Route::post('/get_user_rearcam', 'App\Http\Controllers\Api\CamerastreamController@get_user_rearcam');
        //Video Stream Controller

    });
});


Route::group([
], function () {
    Route::post('/stream/on_publish', 'App\Http\Controllers\Api\VideostreamController@on_publish');
});
