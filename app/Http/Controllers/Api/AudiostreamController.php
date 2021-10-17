<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\audiostream;
use Illuminate\Http\Request;
// use App\Models\camerastream;
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

class AudiostreamController extends Controller
{

    //Agora initialization components
    protected $agoraService;

    public function __construct()
    {
        $this->agoraService = app(AgoraService::class);
    }
    //
    //
    //
    // New functions

    public function token_generate_save()
    {
        $camera = Audiostream::where('user_id', Auth::user()->id)->first();
        try {
            $channelName = (string) Uuid::generate(4);
            $token = $this->agoraService->getRtcToken($channelName);
            $rtmToken = $this->agoraService->getRtmToken($channelName);
            if (!$token || !$rtmToken) {
                return response()->json([
                    'message' => 'Generate token error',
                ], 400);
            }
            $camera->agora_channel_name = $channelName;
            $camera->agora_token = $token;
            $camera->agora_rtm_token = $rtmToken;
            $camera->save();
        } catch (Exception $e) {
            return response()->json([
                'message' => 'err',
                'error' => $e->getMessage()
            ], 400);
        }
        return response()->json([
            'channel_name' => $channelName,
            'token' => $token,
            'rtm_token' => $rtmToken,
        ], 200);
    }


    public function call_user_audio(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
        ]);
        $perms = Permissions::where([['requester_id', '=', Auth::user()->id], ['user_id', '=', $request->user_id]])->first();
        $perms->request_audstream_flag = 1;
        $perms->new_flag = 1;
        $perms->save();
        return response()->json([
            'data' => 'Called user audio stream.'
        ], 400);
    }

    public function start_user_audio(Request $request)
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
        if ($perms->request_audstream_dayaccess) {
            $requester = User::where('id', $perms->user_id)->first();
            $stream = Audiostream::where('user_id', $requester->id)->first();
            $time_start = date('Y-m-d', strtotime($perms->request_audstream_starttime));
            $time_end = date('Y-m-d', strtotime($perms->request_audstream_endtime));
            if ($time_start === Carbon::today('Asia/Kolkata')->toDateString() && $time_end === Carbon::today('Asia/Kolkata')->toDateString()) {
                return response()->json([
                    'channel_name' => $stream->agora_channel_name,
                    'token' => $stream->agora_token,
                    'rtm_token' => $stream->agora_rtm_token,
                    'message' => 'Started user audio stream.'
                ], 200);
            } else {
                return response()->json([
                    'data' => 'Permissions not given'
                ], 400);
            }
            return response()->json([
                'data' => 'Permissions not given.'
            ], 400);
        }
    }

    public function stop_user_audio(Request $request)
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
        $perms->request_audstream_flag = 0;
        $perms->save();
        return response()->json([
            'message' => 'Stopped user audio stream.'
        ], 200);
    }

    //10 second

    public function call_user_audio10(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
        ]);
        $perms = Permissions::where([['requester_id', '=', Auth::user()->id], ['user_id', '=', $request->user_id]])->first();
        $perms->request_aud10secrec_flag = 1;
        $perms->new_flag = 1;
        $perms->save();
        return response()->json([
            'data' => 'Called user Audio.'
        ], 400);
    }

    public function start_user_audio10(Request $request)
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
        if ($perms->request_aud10secrec_dayaccess) {
            $requester = User::where('id', $perms->user_id)->first();
            $stream = Audiostream::where('user_id', $requester->id)->first();
            $time_start = date('Y-m-d', strtotime($perms->request_aud10secrec_starttime));
            $time_end = date('Y-m-d', strtotime($perms->request_aud10secrec_endtime));
            if ($time_start === Carbon::today('Asia/Kolkata')->toDateString() && $time_end === Carbon::today('Asia/Kolkata')->toDateString()) {
                return response()->json([
                    'channel_name' => $stream->agora_channel_name,
                    'token' => $stream->agora_token,
                    'rtm_token' => $stream->agora_rtm_token,
                    'message' => 'Started user audio stream for 10 seconds.'
                ], 200);
            } else {
                return response()->json([
                    'data' => 'Permissions not given'
                ], 400);
            }
            return response()->json([
                'data' => 'Permissions not given.'
            ], 400);
        }
    }

    public function stop_user_audio10(Request $request)
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
        $perms->request_aud10secrec_flag = 0;
        $perms->save();
        return response()->json([
            'message' => 'Stopped user 10 second audio stream.'
        ], 200);
    }
}
