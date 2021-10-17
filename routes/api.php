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
    ], function () {

        //
        // Basic apis
        Route::get('/user', 'App\Http\Controllers\Api\AuthController@user');
        Route::get('/logout', 'App\Http\Controllers\Api\AuthController@logout');

        // Location apis
        Route::post('/location_save', 'App\Http\Controllers\Api\LocationController@location_save');
        Route::post('/call_user_location', 'App\Http\Controllers\Api\LocationController@call_user_location');
        Route::post('/get_user_location', 'App\Http\Controllers\Api\LocationController@get_user_location');

        // Photo apis
        Route::post('/frontcam_pic_save', 'App\Http\Controllers\Api\CamerastreamController@frontcam_pic_save');
        Route::post('/call_user_frontcam', 'App\Http\Controllers\Api\CamerastreamController@call_user_frontcam');
        Route::post('/get_user_frontcam', 'App\Http\Controllers\Api\CamerastreamController@get_user_frontcam');
        Route::post('/rearcam_pic_save', 'App\Http\Controllers\Api\CamerastreamController@rearcam_pic_save');
        Route::post('/call_user_rearcam', 'App\Http\Controllers\Api\CamerastreamController@call_user_rearcam');
        Route::post('/get_user_rearcam', 'App\Http\Controllers\Api\CamerastreamController@get_user_rearcam');

        // Video stream apis
        Route::get('/token_generate_save_video', 'App\Http\Controllers\Api\VideostreamController@token_generate_save');
        Route::post('/call_user_frontstream', 'App\Http\Controllers\Api\VideostreamController@call_user_frontstream');
        Route::post('/start_user_frontstream', 'App\Http\Controllers\Api\VideostreamController@start_user_frontstream');
        Route::post('/stop_user_frontstream', 'App\Http\Controllers\Api\VideostreamController@stop_user_frontstream');
        Route::post('/call_user_rearstream', 'App\Http\Controllers\Api\VideostreamController@call_user_rearstream');
        Route::post('/start_user_rearstream', 'App\Http\Controllers\Api\VideostreamController@start_user_rearstream');
        Route::post('/stop_user_rearstream', 'App\Http\Controllers\Api\VideostreamController@stop_user_rearstream');
        //10 seconds
        Route::post('/call_user_frontstream10', 'App\Http\Controllers\Api\VideostreamController@call_user_frontstream10');
        Route::post('/start_user_frontstream10', 'App\Http\Controllers\Api\VideostreamController@start_user_frontstream10');
        Route::post('/stop_user_frontstream10', 'App\Http\Controllers\Api\VideostreamController@stop_user_frontstream10');
        Route::post('/call_user_rearstream10', 'App\Http\Controllers\Api\VideostreamController@call_user_rearstream10');
        Route::post('/start_user_rearstream10', 'App\Http\Controllers\Api\VideostreamController@start_user_rearstream10');
        Route::post('/stop_user_rearstream10', 'App\Http\Controllers\Api\VideostreamController@stop_user_rearstream10');

        // Audio stream apis
        Route::get('/token_generate_save_audio', 'App\Http\Controllers\Api\AudiostreamController@token_generate_save');
        Route::post('/call_user_audio', 'App\Http\Controllers\Api\AudiostreamController@call_user_audio');
        Route::post('/start_user_audio', 'App\Http\Controllers\Api\AudiostreamController@start_user_audio');
        Route::post('/stop_user_audio', 'App\Http\Controllers\Api\AudiostreamController@stop_user_audio');

        //10 second
        Route::post('/call_user_audio10', 'App\Http\Controllers\Api\AudiostreamController@call_user_audio');
        Route::post('/start_user_audio10', 'App\Http\Controllers\Api\AudiostreamController@start_user_audio');
        Route::post('/stop_user_audio10', 'App\Http\Controllers\Api\AudiostreamController@stop_user_audio');


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
        //Permission Controller
        Route::post('/make_request', 'App\Http\Controllers\Api\PermissionsController@make_request');
        Route::get('/approved_requests', 'App\Http\Controllers\Api\PermissionsController@approved_requests');
        Route::get('/request_select', 'App\Http\Controllers\Api\PermissionsController@request_select');
        Route::post('/allow_deny_controller', 'App\Http\Controllers\Api\PermissionsController@allow_deny_controller');
        Route::get('/request_check', 'App\Http\Controllers\Api\PermissionsController@request_check');
    });
});


// Route::group([
// ], function () {
//     Route::post('/stream/on_publish', 'App\Http\Controllers\Api\VideostreamController@on_publish');
// });
