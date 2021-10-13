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

class AudiostreamController extends Controller
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
        $mic = Audiostream::where('user_id', Auth::user()->id)->first();
        $mic_notifier = $mic;
        $mic->request_audiostream_notifier = 0;
        $mic->save();
        return response()->json([
            'request_audiostream_notifier' => $mic_notifier->request_audiostream_notifier,
            'request_audiostream' => $mic_notifier->request_audiostream,
            'agora_channel_name' => $mic_notifier->agora_channel_name,
            'agora_token' => $mic_notifier->agora_token,
            'agora_rtm_token' => $mic_notifier->agora_rtm_token,
            'message' => 'Successfully got stream credentials for camera stream.'
        ], 201);
    }




    public function get_user_audiostream_start(Request $request)
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
            $mic = Audiostream::where('user_id', $requester->id)->first();
            $time_start = date('Y-m-d', strtotime($perms->request_audstream_starttime));
            $time_end = date('Y-m-d', strtotime($perms->request_audstream_endtime));
            if ($time_start === Carbon::today('Asia/Kolkata')->toDateString() && $time_end === Carbon::today('Asia/Kolkata')->toDateString()) {
                $tokens = generateToken();
                $mic->request_audiostream = 1;
                $mic->request_audiostream_notifier = 1;
                $mic->agora_channel_name = $tokens->channel_name;
                $mic->agora_token = $tokens->token;
                $mic->agora_rtm_token = $tokens->rtm_token;
                $mic->save();
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

    public function get_user_audiostream_stop(Request $request)
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
            $mic = Audiostream::where('user_id', $requester->id)->first();
            $time_start = date('Y-m-d', strtotime($perms->request_audstream_starttime));
            $time_end = date('Y-m-d', strtotime($perms->request_audstream_endtime));
            if ($time_start === Carbon::today('Asia/Kolkata')->toDateString() && $time_end === Carbon::today('Asia/Kolkata')->toDateString()) {
                $mic->request_audiostream = 0;
                $mic->save();
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
    //
    //
    //
    //
    //Agora functions
    public function generateToken()
    {
        try {
            $channelName = (string) Uuid::generate(4);
            $token = $this->agoraService->getRtcToken($channelName);
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
            return $data;
        } catch (Exception $e) {
            return response()->json([
                'message' => 'err',
                'error' => $e->getMessage()
            ], 400);
        }
    }
    //Agora functions end
    //
    //
    //
    //
    public function audio10secstream(Request $request){
        $audio = audiostream::where('user_id', Auth::user()->id)->first();
        $audio->request_audiostream10sec_notifier = 0;

        if ($request->hasFile('audio')) {

            $path = $request->file('audio')->store('public/audio');
            //path corrector
            $string = str_ireplace("public", "storage", $path);
            $audio->audiostream_url = $string;
        }
        $audio->save();
        return response()->json([
            'data' => $audio,
            'message' => 'Successfully saved Front Cam Pic of user.'
        ], 201);
    }

    public function audio10secstream_request_check()
    {
        $camera = audiostream::where('user_id', Auth::user()->id)->first();
            return response()->json([
                'aud_req' => $camera->request_audiostream10sec_notifier,
            ], 200);

    }

    public Function get_10secaudio(Request $request){
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
            $mic = audiostream::where('user_id', $requester->id)->first();
            $mic->request_audiostream10sec_notifier = 1;
            $mic->save();
            sleep(10);
            $mic->request_audiostream10sec_notifier = 0;
            $mic->save();


            $time_start = date('Y-m-d', strtotime($perms->request_aud10secrec_starttime));
            $time_end = date('Y-m-d', strtotime($perms->request_aud10secrec_endtime));

            if ($time_start === Carbon::today('Asia/Kolkata')->toDateString() && $time_end === Carbon::today('Asia/Kolkata')->toDateString()) {
                return response()->json([
                    'url' => $mic->audiostream_url,
                ], 200);
            }
        }
    }

}
