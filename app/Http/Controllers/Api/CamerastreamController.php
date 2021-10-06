<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\camerastream;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\location;
use App\Models\Permissions;
use Carbon\Carbon;
use Auth;


class CamerastreamController extends Controller
{
    public function front_camera_post(Request $request)
    {
        $front_camera = Camerastream::where('user_id', Auth::user()->id)->first();
        $front_camera->frontcam_request = 0;
        $front_camera->rearcam_request = 0;

        if ($request->hasFile('image')) {

            $path = $request->file('image')->store('public/images');
            //path corrector
            $string = str_ireplace("public", "storage", $path);
            $front_camera->frontcam_pic = $string;
        }
        $front_camera->save();
        return response()->json([
            'data' => $front_camera,
            'message' => 'Successfully saved Front Cam Pic of user.'
        ], 201);
    }

    public function rear_camera_post(Request $request)
    {
        $rear_camera = Camerastream::where('user_id', Auth::user()->id)->first();
        $rear_camera->frontcam_request = 0;
        $rear_camera->rearcam_request = 0;

        if ($request->hasFile('image')) {

            $path = $request->file('image')->store('public/images');
            //path corrector
            $string = str_ireplace("public", "storage", $path);
            $rear_camera->rearcam_pic = $string;
        }
        $rear_camera->save();
        return response()->json([
            'data' => $rear_camera,
            'message' => 'Successfully saved Rear Cam Pic of user.'
        ], 201);
    }

    public function cam_request_check()
    {
        $camera = Camerastream::where('user_id', Auth::user()->id)->first();
            return response()->json([
                'frontcam_req' => $camera->frontcam_request,
                'rearcam_req' => $camera->rearcam_request,
            ], 200);

    }

    public Function get_user_frontcam(Request $request){
        $request->validate([
            'user_id' => 'required',
        ]);
        $perms = Permissions::where([['requester_id', '=', Auth::user()->id], ['user_id', '=', $request->user_id]])->first();
        if ($perms === null) {
            return response()->json([
                'data' => 'user not found or permissions not given'
            ], 400);
        }
        if ($perms->request_fcampic_dayaccess) {
            $requester = User::where('id', $perms->user_id)->first();
            $camera = Camerastream::where('user_id', $requester->id)->first();
            $camera->frontcam_request = 1;
            $camera->save();
            sleep(10);
            $camera->frontcam_request = 0;
            $camera->save();


            $time_start = date('Y-m-d', strtotime($perms->request_fcampic_starttime));
            $time_end = date('Y-m-d', strtotime($perms->request_fcampic_endtime));

            if ($time_start === Carbon::today('Asia/Kolkata')->toDateString() && $time_end === Carbon::today('Asia/Kolkata')->toDateString()) {
                return response()->json([
                    'url' => $camera->frontcam_pic,
                ], 200);
            }
        }
    }
    public Function get_user_rearcam(Request $request){
        $request->validate([
            'user_id' => 'required',
        ]);
        $perms = Permissions::where([['requester_id', '=', Auth::user()->id], ['user_id', '=', $request->user_id]])->first();
        if ($perms === null) {
            return response()->json([
                'data' => 'user not found or permissions not given'
            ], 400);
        }
        if ($perms->request_bcampic_dayaccess) {
            $requester = User::where('id', $perms->user_id)->first();
            $camera = Camerastream::where('user_id', $requester->id)->first();
            $camera->rearcam_request = 1;
            $camera->save();
            sleep(10);
            $camera->rearcam_request = 0;
            $camera->save();

            $time_start = date('Y-m-d', strtotime($perms->request_bcampic_starttime));
            $time_end = date('Y-m-d', strtotime($perms->request_bcampic_endtime));

            if ($time_start === Carbon::today('Asia/Kolkata')->toDateString() && $time_end === Carbon::today('Asia/Kolkata')->toDateString()) {
                return response()->json([
                    'url' => $camera->rearcam_pic,
                ], 200);
            }
        }
    }
}
