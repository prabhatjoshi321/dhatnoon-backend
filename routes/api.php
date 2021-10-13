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
        //Agora token generator
        // Route::get('/generateToken', 'App\Http\Controllers\Api\AgoraController@generateToken');
        //Video Stream Controller
        Route::get('/stream_check', 'App\Http\Controllers\Api\VideostreamController@stream_check');
        Route::post('/get_user_frontstream_start', 'App\Http\Controllers\Api\VideostreamController@get_user_frontstream_start');
        Route::post('/get_user_frontstream_stop', 'App\Http\Controllers\Api\VideostreamController@get_user_frontstream_stop');
        Route::post('/get_user_rearstream_start', 'App\Http\Controllers\Api\VideostreamController@get_user_rearstream_start');
        Route::post('/get_user_rearstream_stop', 'App\Http\Controllers\Api\VideostreamController@get_user_rearstream_stop');
        //Video Stream 10 sec apis
        //Audio Stream Controller
        Route::get('/stream_check_audio', 'App\Http\Controllers\Api\AudiotreamController@stream_check');
        Route::post('/get_user_audiostream_start', 'App\Http\Controllers\Api\AudiostreamController@get_user_audiostream_start');
        Route::post('/get_user_sudiostream_stop', 'App\Http\Controllers\Api\AudiostreamController@get_user_audiostream_stop');
        //Audio Stream 10 sec apis
        Route::post('/audio10secstream', 'App\Http\Controllers\Api\AudiotreamController@audio10secstream');
        Route::get('/audio10secstream_request_check', 'App\Http\Controllers\Api\AudiotreamController@audio10secstream_request_check');
        Route::post('/get_10secaudio', 'App\Http\Controllers\Api\AudiotreamController@get_10secaudio');


    });
});


// Route::group([
// ], function () {
//     Route::post('/stream/on_publish', 'App\Http\Controllers\Api\VideostreamController@on_publish');
// });
