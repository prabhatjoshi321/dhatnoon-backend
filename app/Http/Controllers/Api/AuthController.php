<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\camerastream;
use App\Models\videostream;
use App\Models\audiostream;
use App\Models\User;
use App\Models\Location;
use App\Models\Permissions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function signup(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone_number' => 'required|numeric|between:1000000000,9999999999|unique:users',
            'password' => 'required|string|confirmed|min:6'
        ]);

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => bcrypt($request->password)
        ]);

        $user->save();
        return response()->json([
            'data' => $user,
            'message' => 'Successfully created user!'
        ], 201);
    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Invalid Credentials'
            ], 401);
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->addDays(1);
        $token->save();

        //Location table entry creation
        $user_avail_loc = Location::where('user_id', $user->id)->first();

        if($user_avail_loc == null){
            $location = new Location([
                'user_id' => $user->id,
                'lat' => 0,
                'long' => 0,
            ]);
            $location->save();
        }

        //Camera table entry creation
        $user_avail_cam = camerastream::where('user_id', $user->id)->first();

        if($user_avail_cam == null){
            $camera = new camerastream([
                'user_id' => $user->id,
                'frontcam_pic' => '',
                'rearcam_pic' => '',
            ]);
            $camera->save();
        }
        //Streaming table entry creation
        $user_avail_stream = videostream::where('user_id', $user->id)->first();

        if($user_avail_stream == null){
            $stream = new videostream([
                'user_id' => $user->id,
                'agora_channel_name' => '',
                'agora_token' => '',
                'agora_rtm_token' => '',
            ]);
            $stream->save();
        }
        $user_avail_audio = Audiostream::where('user_id', $user->id)->first();

        if($user_avail_audio == null){
            $audio = new Audiostream([
                'user_id' => $user->id,
                'agora_channel_name' => '',
                'agora_token' => '',
                'agora_rtm_token' => '',
            ]);
            $audio->save();
        }

        return response()->json([
            'username' => $user->name,
            'id' => $user->id,
            'phone_number' => $user->phone_number,
            'email' => $user->email,
            'access_token' => $tokenResult->accessToken,
            'loc_check' => $user_avail_loc,
            'cam_check' => $user_avail_cam,
            'stream_check' => $user_avail_stream,
            'audio_check' => $user_avail_audio,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
            'misc' => $user
        ]);
    }

    public function user(Request $request){
        return response()->json($request->user());
    }

}

