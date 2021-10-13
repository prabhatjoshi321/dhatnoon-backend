<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\videostream;
// use App\Models\camerastream;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\location;
use App\Models\Permissions;
use Carbon\Carbon;
use Auth;

//Agora Services
use App\Services\AgoraService;
use Webpatser\Uuid\Uuid;
use Exception;
//End Agora services

class VideostreamController extends Controller
{

    //Agora initialization components
    protected $agoraService;

    public function __construct()
    {
        $this->agoraService = app(AgoraService::class);
    }
    //Agora initialization components end

    // public function on_publish(Request $request)
    // {
    //     if ($request->name == "mystream") {
    //         return response('Good', 200)->header('Content-Type', 'text/plain');
    //     } else {
    //         return response('No', 400)->header('Content-Type', 'text/plain');
    //     }
    // }

    public function stream_check()
    {
        $camera = Videostream::where('user_id', Auth::user()->id)->first();
        $camera_notifier = $camera;
        $camera->frontcam_request_stream_notifier = 0;
        $camera->rearcam_request_stream_notifier = 0;
        $camera->save();
        return response()->json([
            'frontcam_request_stream_notifier' => $camera_notifier->frontcam_request_stream_notifier,
            'rearcam_request_stream_notifier' => $camera_notifier->frontcam_request_stream,
            'frontcam_request_stream' => $camera_notifier->frontcam_request_stream,
            'rearcam_request_stream' => $camera_notifier->frontcam_request_stream,
            'agora_channel_name' => $camera_notifier->agora_channel_name,
            'agora_token' => $camera_notifier->agora_token,
            'agora_rtm_token' => $camera_notifier->agora_rtm_token,
            'message' => 'Successfully got stream credentials for camera stream.'
        ], 201);
    }



    // public function stream_request_check()
    // {
    //     $camera = Videostream::where('user_id', Auth::user()->id)->first();
    //     return response()->json([
    //         'frontcam_req' => $camera->frontcam_request_stream,
    //         'rearcam_req' => $camera->rearcam_request_stream,
    //     ], 200);
    // }

    public function get_user_frontstream_start(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
        ]);
        $perms = Permissions::where([['requester_id', '=', Auth::user()->id], ['user_id', '=', $request->user_id]])->first();
        if ($perms === null) {
            return response()->json([
                'data' => 'user not found or permissions not given'
            ], 400);
        }
        if ($perms->request_fcamstream_dayaccess) {
            $requester = User::where('id', $perms->user_id)->first();
            $camera = Videostream::where('user_id', $requester->id)->first();
            $time_start = date('Y-m-d', strtotime($perms->request_fcamstream_starttime));
            $time_end = date('Y-m-d', strtotime($perms->request_fcamstream_endtime));
            if ($time_start === Carbon::today('Asia/Kolkata')->toDateString() && $time_end === Carbon::today('Asia/Kolkata')->toDateString()) {
                $tokens = generateToken();
                $camera->frontcam_request_stream = 1;
                $camera->frontcam_request_stream_notifier = 1;
                $camera->agora_channel_name = $tokens->channel_name;
                $camera->agora_token = $tokens->token;
                $camera->agora_rtm_token = $tokens->rtm_token;
                $camera->save();
                return response()->json([
                    'channel_name' => $tokens->channel_name,
                    'token' => $tokens->token,
                    'rtm_token' =>  $tokens->rtm_token,
                    'message' => 'Frontcam Stream Started',
                ], 200);
            }
        } else {
            return response()->json([
                'message' => 'No Access given for today',
            ], 200);
        }
    }

    public function get_user_frontstream_stop(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
        ]);
        $perms = Permissions::where([['requester_id', '=', Auth::user()->id], ['user_id', '=', $request->user_id]])->first();
        if ($perms === null) {
            return response()->json([
                'data' => 'user not found or permissions not given'
            ], 400);
        }
        if ($perms->request_bcamstream_dayaccess) {
            $requester = User::where('id', $perms->user_id)->first();
            $camera = Videostream::where('user_id', $requester->id)->first();
            $time_start = date('Y-m-d', strtotime($perms->request_fcamstream_starttime));
            $time_end = date('Y-m-d', strtotime($perms->request_fcamstream_endtime));
            if ($time_start === Carbon::today('Asia/Kolkata')->toDateString() && $time_end === Carbon::today('Asia/Kolkata')->toDateString()) {
                $camera->frontcam_request_stream = 0;
                $camera->save();
                return response()->json([
                    'message' => 'Frontcam Stream Stopped',
                ], 200);
            }
        } else {
            return response()->json([
                'message' => 'No Access given for today',
            ], 200);
        }
    }

    public function get_user_rearstream_start(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
        ]);
        $perms = Permissions::where([['requester_id', '=', Auth::user()->id], ['user_id', '=', $request->user_id]])->first();
        if ($perms === null) {
            return response()->json([
                'data' => 'user not found or permissions not given'
            ], 400);
        }
        if ($perms->request_bcamstream_dayaccess) {
            $requester = User::where('id', $perms->user_id)->first();
            $camera = Videostream::where('user_id', $requester->id)->first();
            $time_start = date('Y-m-d', strtotime($perms->request_bcamstream_starttime));
            $time_end = date('Y-m-d', strtotime($perms->request_bcamstream_endtime));
            if ($time_start === Carbon::today('Asia/Kolkata')->toDateString() && $time_end === Carbon::today('Asia/Kolkata')->toDateString()) {
                $tokens = generateToken();
                $camera->rearcam_request_stream = 1;
                $camera->rearcam_request_stream_notifier = 1;
                $camera->agora_channel_name = $tokens->channel_name;
                $camera->agora_token = $tokens->token;
                $camera->agora_rtm_token = $tokens->rtm_token;
                $camera->agora_token = 1;
                $camera->agora_rtm_token = 1;
                $camera->save();
                return response()->json([
                    'channel_name' => $tokens->channel_name,
                    'token' => $tokens->token,
                    'rtm_token' =>  $tokens->rtm_token,
                    'message' => 'Rearcam Stream Started',
                ], 200);
            }
        } else {
            return response()->json([
                'message' => 'No Access given for today',
            ], 200);
        }
    }

    public function get_user_rearstream_stop(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
        ]);
        $perms = Permissions::where([['requester_id', '=', Auth::user()->id], ['user_id', '=', $request->user_id]])->first();
        if ($perms === null) {
            return response()->json([
                'data' => 'user not found or permissions not given'
            ], 400);
        }
        if ($perms->request_bcamstream_dayaccess) {
            $requester = User::where('id', $perms->user_id)->first();
            $camera = Videostream::where('user_id', $requester->id)->first();
            $time_start = date('Y-m-d', strtotime($perms->request_bcamstream_starttime));
            $time_end = date('Y-m-d', strtotime($perms->request_bcamstream_endtime));
            if ($time_start === Carbon::today('Asia/Kolkata')->toDateString() && $time_end === Carbon::today('Asia/Kolkata')->toDateString()) {
                $camera->rearcam_request_stream = 0;
                $camera->save();
                return response()->json([
                    'message' => 'Rearcam Stream Stopped',
                ], 200);
            }
        } else {
            return response()->json([
                'message' => 'No Access given for today',
            ], 200);
        }
    }



//
//
//
//
//Agora functions
public function generateToken()
    {
        try {
            $channelName = (string) Uuid::generate(4);
            // Rtc token dùng để video call
            $token = $this->agoraService->getRtcToken($channelName);
            // Rtm token dùng để chat
            $rtmToken = $this->agoraService->getRtmToken($channelName);
            if (!$token || !$rtmToken) {

                return response()->json([
                    'message' => 'Generate token error',
                ], 400);
            }

            $data = [
                'channel_name' => $channelName,
                'token' => $token,
                'rtm_token' => $rtmToken,
            ];

            // return response()->json([
            //     'message' => 'Success',
            //     'channel_name' => $channelName,
            //     'token' => $token,
            //     'rtm_token' => $rtmToken
            // ], 200);
            return $data;
            // return $this->success($data);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'err',
                'error' => $e->getMessage()
            ], 400);
            // return $this->error($e->getMessage());
        }
    }
//Agora functions end


}
