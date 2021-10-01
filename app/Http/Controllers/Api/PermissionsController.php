<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Permissions;
use Illuminate\Http\Request;
use Auth;
use App\Models\location;
use App\Models\User;
use Carbon\Carbon;
use PhpParser\Node\Expr\Cast\String_;

class PermissionsController extends Controller
{
    public function make_request(Request $request)
    {
        $request->validate([
            'phone_number' => 'required',
            'selected_option' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        //Time Converter Function
        function convert(String $time)
        {
            $date = Carbon::today('Asia/Kolkata')->toDateString();
            $dt_combined = $date . ' ' . $time;
            $formatted = date('Y-m-d H:i:s', strtotime($dt_combined));
            return $formatted;
        }
        $start_time = convert($request->start_time);
        $end_time = convert($request->end_time);

        //Find If user Exists in database
        $user = User::where('phone_number', $request->phone_number)->first();
        if ($user === null) {
            return response()->json([
                'message' => 'Given phone number doesnot belong to any user.'
            ], 400);
        }

        $requester_id = Auth::user()->id;
        $requested_user_id = $user->id;

        // User Ids
        // if ($requester_id === $requested_user_id) {
        //     return response()->json([
        //         'message' => 'You cannot post request to yourself'
        //     ], 200);
        // }

        //Exist check
        $exist = Permissions::where([['requester_id', '=', $requester_id], ['user_id', '=', $requested_user_id]])->first();
        if ($exist === null) {
            // Permission control Logics
            //Geolocation
            if ($request->selected_option === 'Live Geo Location.') {
                $permission = new Permissions([
                    'user_id' => $requested_user_id,
                    'requester_id' => $requester_id,
                    'request_geoloc_starttime' => $start_time,
                    'request_geoloc_endtime' => $end_time,
                    'request_geoloc_dayaccess' => 0,
                    'new_flag' => 1,
                ]);
                $permission->save();
                return response()->json([
                    'data' => $permission,
                    'message' => 'Geolocation request sent.'
                ], 201);
            }

            //Front Camera Pic
            if ($request->selected_option === 'Front Camera Pic.') {
                $permission = new Permissions([
                    'user_id' => $requested_user_id,
                    'requester_id' => $requester_id,
                    'request_fcampic_starttime' => $start_time,
                    'request_fcampic_endtime' => $end_time,
                    'request_fcampic_dayaccess' => 0,
                    'new_flag' => 1,
                ]);
                $permission->save();
                return response()->json([
                    'data' => $permission,
                    'message' => 'Front camera pic request sent.'
                ], 201);
            }

            //Back Camera Pic
            if ($request->selected_option === 'Back Camera Pic.') {
                $permission = new Permissions([
                    'user_id' => $requested_user_id,
                    'requester_id' => $requester_id,
                    'request_bcampic_starttime' => $start_time,
                    'request_bcampic_endtime' => $end_time,
                    'request_bcampic_dayaccess' => 0,
                    'new_flag' => 1,
                ]);
                $permission->save();
                return response()->json([
                    'data' => $permission,
                    'message' => 'Back camera pic request sent.'
                ], 201);
            }

            //Front camera streaming
            if ($request->selected_option === 'Front Camera Streaming.') {
                $permission = new Permissions([
                    'user_id' => $requested_user_id,
                    'requester_id' => $requester_id,
                    'request_fcamstream_starttime' => $start_time,
                    'request_fcamstream_endtime' => $end_time,
                    'request_fcamstream_dayaccess' => 0,
                    'new_flag' => 1,
                ]);
                $permission->save();
                return response()->json([
                    'data' => $permission,
                    'message' => 'Front camera streaming request sent.'
                ], 201);
            }

            //Back camera streaming
            if ($request->selected_option === 'Back Camera Streaming.') {
                $permission = new Permissions([
                    'user_id' => $requested_user_id,
                    'requester_id' => $requester_id,
                    'request_bcamstream_starttime' => $start_time,
                    'request_bcamstream_endtime' => $end_time,
                    'request_bcamstream_dayaccess' => 0,
                    'new_flag' => 1,
                ]);
                $permission->save();
                return response()->json([
                    'data' => $permission,
                    'message' => 'Back camera streaming request sent.'
                ], 201);
            }

            //Front Camera 10 Second Video.
            if ($request->selected_option === 'Front Camera 10 Second Video.') {
                $permission = new Permissions([
                    'user_id' => $requested_user_id,
                    'requester_id' => $requester_id,
                    'request_fcam10secvid_starttime' => $start_time,
                    'request_fcam10secvid_endtime' => $end_time,
                    'request_geoloc_dayaccess' => 0,
                    'new_flag' => 1,
                ]);
                $permission->save();
                return response()->json([
                    'data' => $permission,
                    'message' => 'front camera 10 second video request sent.'
                ], 201);
            }

            //Back camera 10 Second Video.
            if ($request->selected_option === 'Back camera 10 Second Video.') {
                $permission = new Permissions([
                    'user_id' => $requested_user_id,
                    'requester_id' => $requester_id,
                    'request_bcam10secvid_starttime' => $start_time,
                    'request_bcam10secvid_endtime' => $end_time,
                    'request_bcam10secvid_dayaccess' => 0,
                    'new_flag' => 1,
                ]);
                $permission->save();
                return response()->json([
                    'data' => $permission,
                    'message' => 'Back camera 10 Second Video request sent.'
                ], 201);
            }

            //Audio Live Streaming.
            if ($request->selected_option === 'Audio Live Streaming.') {
                $permission = new Permissions([
                    'user_id' => $requested_user_id,
                    'requester_id' => $requester_id,
                    'request_audstream_starttime' => $start_time,
                    'request_audstream_endtime' => $end_time,
                    'request_audstream_dayaccess' => 0,
                    'new_flag' => 1,
                ]);
                $permission->save();
                return response()->json([
                    'data' => $permission,
                    'message' => 'Audio Live Streaming request sent.'
                ], 201);
            }

            //10 Second Audio Recording.
            if ($request->selected_option === '10 Second Audio Recording.') {
                $permission = new Permissions([
                    'user_id' => $requested_user_id,
                    'requester_id' => $requester_id,
                    'request_aud10secrec_starttime' => $start_time,
                    'request_aud10secrec_endtime' => $end_time,
                    'request_aud10secrec_dayaccess' => 0,
                    'new_flag' => 1,
                ]);
                $permission->save();
                return response()->json([
                    'data' => $permission,
                    'message' => '10 Second Audio Recording request sent.'
                ], 201);
            }
        } else {
            //Permission Logic for existing requests


            //Geolocation
            if ($request->selected_option === 'Live Geo Location.') {
                $exist->request_geoloc_starttime = $start_time;
                $exist->request_geoloc_endtime = $end_time;
                $exist->request_geoloc_dayaccess = 0;
                $exist->new_flag = 1;
                $exist->save();
                return response()->json([
                    'message' => 'Geolocation request sent.'
                ], 201);
            }

            //Front Camera Pic
            if ($request->selected_option === 'Front Camera Pic.') {
                $exist->request_fcampic_starttime = $start_time;
                $exist->request_fcampic_endtime = $end_time;
                $exist->request_fcampic_dayaccess = 0;
                $exist->new_flag = 1;
                $exist->save();
                return response()->json([
                    'message' => 'Front camera pic request sent.'
                ], 201);
            }

            //Back Camera Pic
            if ($request->selected_option === 'Back Camera Pic.') {
                $exist->request_bcampic_starttime = $start_time;
                $exist->request_bcampic_endtime = $end_time;
                $exist->request_bcampic_dayaccess = 0;
                $exist->new_flag = 1;
                $exist->save();
                return response()->json([
                    'message' => 'Back camera pic request sent.'
                ], 201);
            }

            //Front camera streaming
            if ($request->selected_option === 'Front Camera Streaming.') {
                $exist->request_fcamstream_starttime = $start_time;
                $exist->request_fcamstream_endtime = $end_time;
                $exist->request_fcamstream_dayaccess = 0;
                $exist->new_flag = 1;
                $exist->save();
                return response()->json([
                    'message' => 'Front camera streaming request sent.'
                ], 201);
            }

            //Back camera streaming
            if ($request->selected_option === 'Back Camera Streaming.') {
                $exist->request_bcamstream_starttime = $start_time;
                $exist->request_bcamstream_endtime = $end_time;
                $exist->request_bcamstream_dayaccess = 0;
                $exist->new_flag = 1;
                $exist->save();
                return response()->json([
                    'message' => 'Back camera streaming request sent.'
                ], 201);
            }

            //Front Camera 10 Second Video.
            if ($request->selected_option === 'Front Camera 10 Second Video.') {
                $exist->request_fcam10secvid_starttime = $start_time;
                $exist->request_fcam10secvid_endtime = $end_time;
                $exist->request_fcam10secvid_dayaccess = 0;
                $exist->new_flag = 1;
                $exist->save();
                return response()->json([
                    'message' => 'front camera 10 second video request sent.'
                ], 201);
            }

            //Back camera 10 Second Video.
            if ($request->selected_option === 'Back camera 10 Second Video.') {
                $exist->request_bcam10secvid_starttime = $start_time;
                $exist->request_bcam10secvid_endtime = $end_time;
                $exist->request_bcam10secvid_dayaccess = 0;
                $exist->new_flag = 1;
                $exist->save();
                return response()->json([
                    'message' => 'Back camera 10 Second Video request sent.'
                ], 201);
            }

            //Audio Live Streaming.
            if ($request->selected_option === 'Audio Live Streaming.') {
                $exist->request_audstream_starttime = $start_time;
                $exist->request_audstream_endtime = $end_time;
                $exist->request_audstream_dayaccess = 0;
                $exist->new_flag = 1;
                $exist->save();
                return response()->json([
                    'message' => 'Audio Live Streaming request sent.'
                ], 201);
            }

            //10 Second Audio Recording.
            if ($request->selected_option === '10 Second Audio Recording.') {
                $exist->request_aud10secrec_starttime = $start_time;
                $exist->request_aud10secrec_endtime = $end_time;
                $exist->request_aud10secrec_dayaccess = 0;
                $exist->new_flag = 1;
                $exist->save();
                return response()->json([
                    'message' => '10 Second Audio Recording request sent.'
                ], 201);
            }
        }

        return response()->json([
            'message' => 'Something Went Wrong.'
        ], 301);
    }

    public function approved_requests()
    {
        $requester_id = Auth::user()->id;
        $data = Permissions::where([['requester_id', '=', $requester_id]])->get();
        $json_decoded = json_decode($data);

        $data_arranged = [];

        foreach ($json_decoded as $item) {

            if ($item->request_geoloc_dayaccess) {
                $user = User::where('id', $item->user_id)->first();
                $time_start = date('Y-m-d', strtotime($item->request_geoloc_starttime));
                $time_end = date('Y-m-d', strtotime($item->request_geoloc_endtime));
                if ($time_start === Carbon::today('Asia/Kolkata')->toDateString() && $time_end === Carbon::today('Asia/Kolkata')->toDateString()) {
                    $data_arranged[] = [
                        'user_id' => $user->id,
                        'feature' => 'Live Geo Location',
                        'feature_id' => 1,
                        'user_name' => $user->name,
                        'user_phno' => $user->phone_number,
                        'start_time' => $item->request_geoloc_starttime,
                        'end_time' => $item->request_geoloc_endtime,
                    ];
                }
            }
            if ($item->request_fcampic_dayaccess) {
                $user = User::where('id', $item->user_id)->first();
                $time_start = date('Y-m-d', strtotime($item->request_fcampic_starttime));
                $time_end = date('Y-m-d', strtotime($item->request_fcampic_endtime));
                if ($time_start === Carbon::today('Asia/Kolkata')->toDateString() && $time_end === Carbon::today('Asia/Kolkata')->toDateString()) {
                    $data_arranged[] = [
                        'user_id' => $user->id,
                        'feature' => 'Front Camera Pic',
                        'feature_id' => 2,
                        'user_name' => $user->name,
                        'user_phno' => $user->phone_number,
                        'start_time' => $item->request_fcampic_starttime,
                        'end_time' => $item->request_fcampic_endtime,
                    ];
                }
            }
            if ($item->request_bcampic_dayaccess) {
                $user = User::where('id', $item->user_id)->first();
                $time_start = date('Y-m-d', strtotime($item->request_bcampic_starttime));
                $time_end = date('Y-m-d', strtotime($item->request_bcampic_endtime));
                if ($time_start === Carbon::today('Asia/Kolkata')->toDateString() && $time_end === Carbon::today('Asia/Kolkata')->toDateString()) {
                    $data_arranged[] = [
                        'user_id' => $user->id,
                        'feature' => 'Back Camera Pic',
                        'feature_id' => 3,
                        'user_name' => $user->name,
                        'user_phno' => $user->phone_number,
                        'start_time' => $item->request_bcampic_starttime,
                        'end_time' => $item->request_bcampic_endtime,
                    ];
                }
            }
            if ($item->request_fcamstream_dayaccess) {
                $user = User::where('id', $item->user_id)->first();
                $time_start = date('Y-m-d', strtotime($item->request_fcamstream_starttime));
                $time_end = date('Y-m-d', strtotime($item->request_fcamstream_endtime));
                if ($time_start === Carbon::today('Asia/Kolkata')->toDateString() && $time_end === Carbon::today('Asia/Kolkata')->toDateString()) {
                    $data_arranged[] = [
                        'user_id' => $user->id,
                        'feature' => 'Front Camera Streaming',
                        'feature_id' => 4,
                        'user_name' => $user->name,
                        'user_phno' => $user->phone_number,
                        'start_time' => $item->request_fcamstream_starttime,
                        'end_time' => $item->request_fcamstream_endtime,
                    ];
                }
            }
            if ($item->request_bcamstream_dayaccess) {
                $user = User::where('id', $item->user_id)->first();
                $time_start = date('Y-m-d', strtotime($item->request_bcamstream_starttime));
                $time_end = date('Y-m-d', strtotime($item->request_bcamstream_endtime));
                if ($time_start === Carbon::today('Asia/Kolkata')->toDateString() && $time_end === Carbon::today('Asia/Kolkata')->toDateString()) {
                    $data_arranged[] = [
                        'user_id' => $user->id,
                        'feature' => 'Back Camera Streaming',
                        'feature_id' => 5,
                        'user_name' => $user->name,
                        'user_phno' => $user->phone_number,
                        'start_time' => $item->request_bcamstream_starttime,
                        'end_time' => $item->request_bcamstream_endtime,
                    ];
                }
            }
            if ($item->request_fcam10secvid_dayaccess) {
                $user = User::where('id', $item->user_id)->first();
                $time_start = date('Y-m-d', strtotime($item->request_fcam10secvid_starttime));
                $time_end = date('Y-m-d', strtotime($item->request_fcam10secvid_endtime));
                if ($time_start === Carbon::today('Asia/Kolkata')->toDateString() && $time_end === Carbon::today('Asia/Kolkata')->toDateString()) {
                    $data_arranged[] = [
                        'user_id' => $user->id,
                        'feature' => 'Front Camera 10 Second Video',
                        'feature_id' => 6,
                        'user_name' => $user->name,
                        'user_phno' => $user->phone_number,
                        'start_time' => $item->request_fcam10secvid_starttime,
                        'end_time' => $item->request_fcam10secvid_endtime,
                    ];
                }
            }
            if ($item->request_bcam10secvid_dayaccess) {
                $user = User::where('id', $item->user_id)->first();
                $time_start = date('Y-m-d', strtotime($item->request_bcam10secvid_starttime));
                $time_end = date('Y-m-d', strtotime($item->request_bcam10secvid_endtime));
                if ($time_start === Carbon::today('Asia/Kolkata')->toDateString() && $time_end === Carbon::today('Asia/Kolkata')->toDateString()) {
                    $data_arranged[] = [
                        'user_id' => $user->id,
                        'feature' => 'Back camera 10 Second Video',
                        'feature_id' => 7,
                        'user_name' => $user->name,
                        'user_phno' => $user->phone_number,
                        'start_time' => $item->request_bcam10secvid_starttime,
                        'end_time' => $item->request_bcam10secvid_endtime,
                    ];
                }
            }
            if ($item->request_audstream_dayaccess) {
                $user = User::where('id', $item->user_id)->first();
                $time_start = date('Y-m-d', strtotime($item->request_audstream_starttime));
                $time_end = date('Y-m-d', strtotime($item->request_audstream_endtime));
                if ($time_start === Carbon::today('Asia/Kolkata')->toDateString() && $time_end === Carbon::today('Asia/Kolkata')->toDateString()) {
                    $data_arranged[] = [
                        'user_id' => $user->id,
                        'feature' => 'Audio Live Streaming',
                        'feature_id' => 8,
                        'user_name' => $user->name,
                        'user_phno' => $user->phone_number,
                        'start_time' => $item->request_audstream_starttime,
                        'end_time' => $item->request_audstream_endtime,
                    ];
                }
            }
            if ($item->request_aud10secrec_dayaccess) {
                $user = User::where('id', $item->user_id)->first();
                $time_start = date('Y-m-d', strtotime($item->request_aud10secrec_starttime));
                $time_end = date('Y-m-d', strtotime($item->request_aud10secrec_endtime));
                if ($time_start === Carbon::today('Asia/Kolkata')->toDateString() && $time_end === Carbon::today('Asia/Kolkata')->toDateString()) {
                    $data_arranged[] = [
                        'user_id' => $user->id,
                        'feature' => '10 Second Audio Recording',
                        'feature_id' => 9,
                        'user_name' => $user->name,
                        'user_phno' => $user->phone_number,
                        'start_time' => $item->request_aud10secrec_starttime,
                        'end_time' => $item->request_aud10secrec_endtime,
                    ];
                }
            }
        }
        return response()->json([
            'data' => $data_arranged
        ], 201);
    }

    //Requests sent to you
    public function request_select()
    {
        $user_id = Auth::user()->id;
        $data = Permissions::where([['user_id', '=', $user_id]])->get();
        $json_decoded = json_decode($data);

        $data_arranged = [];

        foreach ($json_decoded as $item) {

                $requester = User::where('id', $item->requester_id)->first();
                $time_start = date('Y-m-d', strtotime($item->request_geoloc_starttime));
                $time_end = date('Y-m-d', strtotime($item->request_geoloc_endtime));
                if ($time_start === Carbon::today('Asia/Kolkata')->toDateString() && $time_end === Carbon::today('Asia/Kolkata')->toDateString()) {
                    $data_arranged[] = [
                        'permisssion_id' =>  $item->id,
                        'feature' => 'Live Geo Location',
                        'feature_id' => 1,
                        'requester_name' => $requester->name,
                        'requester_phno' => $requester->phone_number,
                        'start_time' => $item->request_geoloc_starttime,
                        'end_time' => $item->request_geoloc_endtime,
                        'end_time' => $item->request_geoloc_endtime,
                        'day_access' => $item->request_geoloc_dayaccess,
                    ];
                }
                $requester = User::where('id', $item->requester_id)->first();
                $time_start = date('Y-m-d', strtotime($item->request_fcampic_starttime));
                $time_end = date('Y-m-d', strtotime($item->request_fcampic_endtime));
                if ($time_start === Carbon::today('Asia/Kolkata')->toDateString() && $time_end === Carbon::today('Asia/Kolkata')->toDateString()) {
                    $data_arranged[] = [
                        'permission_id' => $item->id,
                        'feature' => 'Front Camera Pic',
                        'feature_id' => 2,
                        'requester_name' => $requester->name,
                        'requester_phno' => $requester->phone_number,
                        'start_time' => $item->request_fcampic_starttime,
                        'end_time' => $item->request_fcampic_endtime,
                        'day_access' => $item->request_fcampic_dayaccess,
                    ];
                }
                $requester = User::where('id', $item->requester_id)->first();
                $time_start = date('Y-m-d', strtotime($item->request_bcampic_starttime));
                $time_end = date('Y-m-d', strtotime($item->request_bcampic_endtime));
                if ($time_start === Carbon::today('Asia/Kolkata')->toDateString() && $time_end === Carbon::today('Asia/Kolkata')->toDateString()) {
                    $data_arranged[] = [
                        'permission_id' => $item->id,
                        'feature' => 'Back Camera Pic',
                        'feature_id' => 3,
                        'requester_name' => $requester->name,
                        'requester_phno' => $requester->phone_number,
                        'start_time' => $item->request_bcampic_starttime,
                        'end_time' => $item->request_bcampic_endtime,
                        'day_access' => $item->request_bcampic_dayaccess,
                    ];
                }
                $requester = User::where('id', $item->requester_id)->first();
                $time_start = date('Y-m-d', strtotime($item->request_fcamstream_starttime));
                $time_end = date('Y-m-d', strtotime($item->request_fcamstream_endtime));
                if ($time_start === Carbon::today('Asia/Kolkata')->toDateString() && $time_end === Carbon::today('Asia/Kolkata')->toDateString()) {
                    $data_arranged[] = [
                        'permission_id' => $item->id,
                        'feature' => 'Front Camera Streaming',
                        'feature_id' => 4,
                        'requester_name' => $requester->name,
                        'requester_phno' => $requester->phone_number,
                        'start_time' => $item->request_fcamstream_starttime,
                        'end_time' => $item->request_fcamstream_endtime,
                        'day_access' => $item->request_fcamstream_dayaccess,
                    ];
                }
                $requester = User::where('id', $item->requester_id)->first();
                $time_start = date('Y-m-d', strtotime($item->request_bcamstream_starttime));
                $time_end = date('Y-m-d', strtotime($item->request_bcamstream_endtime));
                if ($time_start === Carbon::today('Asia/Kolkata')->toDateString() && $time_end === Carbon::today('Asia/Kolkata')->toDateString()) {
                    $data_arranged[] = [
                        'permission_id' => $item->id,
                        'feature' => 'Back Camera Streaming',
                        'feature_id' => 5,
                        'requester_name' => $requester->name,
                        'requester_phno' => $requester->phone_number,
                        'start_time' => $item->request_bcamstream_starttime,
                        'end_time' => $item->request_bcamstream_endtime,
                        'day_access' => $item->request_bcamstream_dayaccess,
                    ];
                }
                $requester = User::where('id', $item->requester_id)->first();
                $time_start = date('Y-m-d', strtotime($item->request_fcam10secvid_starttime));
                $time_end = date('Y-m-d', strtotime($item->request_fcam10secvid_endtime));
                if ($time_start === Carbon::today('Asia/Kolkata')->toDateString() && $time_end === Carbon::today('Asia/Kolkata')->toDateString()) {
                    $data_arranged[] = [
                        'permission_id' => $item->id,
                        'feature' => 'Front Camera 10 Second Video',
                        'feature_id' => 6,
                        'requester_name' => $requester->name,
                        'requester_phno' => $requester->phone_number,
                        'start_time' => $item->request_fcam10secvid_starttime,
                        'end_time' => $item->request_fcam10secvid_endtime,
                        'day_access' => $item->request_fcam10secvid_dayaccess,
                    ];
                }
                $requester = User::where('id', $item->requester_id)->first();
                $time_start = date('Y-m-d', strtotime($item->request_bcam10secvid_starttime));
                $time_end = date('Y-m-d', strtotime($item->request_bcam10secvid_endtime));
                if ($time_start === Carbon::today('Asia/Kolkata')->toDateString() && $time_end === Carbon::today('Asia/Kolkata')->toDateString()) {
                    $data_arranged[] = [
                        'permission_id' => $item->id,
                        'feature' => 'Back camera 10 Second Video',
                        'feature_id' => 7,
                        'requester_name' => $requester->name,
                        'requester_phno' => $requester->phone_number,
                        'start_time' => $item->request_bcam10secvid_starttime,
                        'end_time' => $item->request_bcam10secvid_endtime,
                        'day_access' => $item->request_bcam10secvid_dayaccess,
                    ];
                }
                $requester = User::where('id', $item->requester_id)->first();
                $time_start = date('Y-m-d', strtotime($item->request_audstream_starttime));
                $time_end = date('Y-m-d', strtotime($item->request_audstream_endtime));
                if ($time_start === Carbon::today('Asia/Kolkata')->toDateString() && $time_end === Carbon::today('Asia/Kolkata')->toDateString()) {
                    $data_arranged[] = [
                        'permission_id' => $item->id,
                        'feature' => 'Audio Live Streaming',
                        'feature_id' => 8,
                        'requester_name' => $requester->name,
                        'requester_phno' => $requester->phone_number,
                        'start_time' => $item->request_audstream_starttime,
                        'end_time' => $item->request_audstream_endtime,
                        'day_access' => $item->request_audstream_dayaccess,
                    ];
                }
                $requester = User::where('id', $item->requester_id)->first();
                $time_start = date('Y-m-d', strtotime($item->request_aud10secrec_starttime));
                $time_end = date('Y-m-d', strtotime($item->request_aud10secrec_endtime));
                if ($time_start === Carbon::today('Asia/Kolkata')->toDateString() && $time_end === Carbon::today('Asia/Kolkata')->toDateString()) {
                    $data_arranged[] = [
                        'permission_id' => $item->id,
                        'feature' => '10 Second Audio Recording',
                        'feature_id' => 9,
                        'requester_name' => $requester->name,
                        'requester_phno' => $requester->phone_number,
                        'start_time' => $item->request_aud10secrec_starttime,
                        'end_time' => $item->request_aud10secrec_endtime,
                        'day_access' => $item->request_aud10secrec_dayaccess,
                    ];
                }
        }
        return response()->json([
            'data' => $data_arranged
        ], 201);
    }

    public function get_user_location(Request $request)
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
        if ($perms->request_geoloc_dayaccess) {
            $requester = User::where('id', $perms->user_id)->first();
            $location_params = location::where('user_id', $requester->id)->first();
            $time_start = date('Y-m-d', strtotime($perms->request_geoloc_starttime));
            $time_end = date('Y-m-d', strtotime($perms->request_geoloc_endtime));

            if ($time_start === Carbon::today('Asia/Kolkata')->toDateString() && $time_end === Carbon::today('Asia/Kolkata')->toDateString()) {
                return response()->json([
                    'lat' => $location_params->lat,
                    'long' => $location_params->long,
                ], 200);
            }
        }
    }

    public function allow_deny_controller(Request $request)
    {
        $request->validate([
            'option' => 'required|numeric',
            'feature_id' => 'required|numeric',
        ]);
        $perms = Permissions::where([['user_id', '=', Auth::user()->id]])->first();


        if ($request->feature_id === '1') {
            $time_start = date('Y-m-d', strtotime($perms->request_geoloc_starttime));
            $time_end = date('Y-m-d', strtotime($perms->request_geoloc_endtime));
            if ($time_start === Carbon::today('Asia/Kolkata')->toDateString() && $time_end === Carbon::today('Asia/Kolkata')->toDateString()) {
                if ($request->option === '1') {
                    $perms->request_geoloc_dayaccess = 1;
                    $perms->save();
                    return response()->json([
                        'message' => 'Geolocation request allowed.'
                    ], 200);
                } else if ($request->option === '0') {
                    $perms->request_geoloc_dayaccess = 0;
                    $perms->save();
                    return response()->json([
                        'message' => 'Geolocation request denied.'
                    ], 200);
                }
            }
        } else if ($request->feature_id === '2') {
            $time_start = date('Y-m-d', strtotime($perms->request_fcampic_starttime));
            $time_end = date('Y-m-d', strtotime($perms->request_fcampic_endtime));
            if ($time_start === Carbon::today('Asia/Kolkata')->toDateString() && $time_end === Carbon::today('Asia/Kolkata')->toDateString()) {
                if ($request->option === '1') {
                    $perms->request_fcampic_dayaccess = 1;
                    $perms->save();
                    return response()->json([
                        'message' => 'Front camera pic request allowed.'
                    ], 200);
                } else if ($request->option === '0') {
                    $perms->request_fcampic_dayaccess = 0;
                    $perms->save();
                    return response()->json([
                        'message' => 'Front camera pic request denied.'
                    ], 200);
                }
            }
        } else if ($request->feature_id === '3') {
            $time_start = date('Y-m-d', strtotime($perms->request_bcampic_starttime));
            $time_end = date('Y-m-d', strtotime($perms->request_bcampic_endtime));
            if ($time_start === Carbon::today('Asia/Kolkata')->toDateString() && $time_end === Carbon::today('Asia/Kolkata')->toDateString()) {
                if ($request->option === '1') {
                    $perms->request_bcampic_dayaccess = 1;
                    $perms->save();
                    return response()->json([
                        'message' => 'Back camera pic request allowed.'
                    ], 200);
                } else if ($request->option === '0') {
                    $perms->request_bcampic_dayaccess = 0;
                    $perms->save();
                    return response()->json([
                        'message' => 'Back camera pic request denied.'
                    ], 200);
                }
            }
        } else if ($request->feature_id === '4') {
            $time_start = date('Y-m-d', strtotime($perms->request_fcamstream_starttime));
            $time_end = date('Y-m-d', strtotime($perms->request_fcamstream_endtime));
            if ($time_start === Carbon::today('Asia/Kolkata')->toDateString() && $time_end === Carbon::today('Asia/Kolkata')->toDateString()) {
                if ($request->option === '1') {
                    $perms->request_fcamstream_dayaccess = 1;
                    $perms->save();
                    return response()->json([
                        'message' => 'Front camera streaming request allowed.'
                    ], 200);
                } else if ($request->option === '0') {
                    $perms->request_fcamstream_dayaccess = 0;
                    $perms->save();
                    return response()->json([
                        'message' => 'Front camera streaming request denied.'
                    ], 200);
                }
            }
        } else if ($request->feature_id === '5') {
            $time_start = date('Y-m-d', strtotime($perms->request_bcamstream_starttime));
            $time_end = date('Y-m-d', strtotime($perms->request_bcamstream_endtime));
            if ($time_start === Carbon::today('Asia/Kolkata')->toDateString() && $time_end === Carbon::today('Asia/Kolkata')->toDateString()) {
                if ($request->option === '1') {
                    $perms->request_bcamstream_dayaccess = 1;
                    $perms->save();
                    return response()->json([
                        'message' => 'Back camera streaming request allowed.'
                    ], 200);
                } else if ($request->option === '0') {
                    $perms->request_bcamstream_dayaccess = 0;
                    $perms->save();
                    return response()->json([
                        'message' => 'Back camera streaming request denied.'
                    ], 200);
                }
            }
        } else if ($request->feature_id === '6') {
            $time_start = date('Y-m-d', strtotime($perms->request_fcam10secvid_starttime));
            $time_end = date('Y-m-d', strtotime($perms->request_fcam10secvid_endtime));
            if ($time_start === Carbon::today('Asia/Kolkata')->toDateString() && $time_end === Carbon::today('Asia/Kolkata')->toDateString()) {
                if ($request->option === '1') {
                    $perms->request_fcam10secvid_dayaccess = 1;
                    $perms->save();
                    return response()->json([
                        'message' => 'front camera 10 second video request allowed.'
                    ], 200);
                } else if ($request->option === '0') {
                    $perms->request_fcam10secvid_dayaccess = 0;
                    $perms->save();
                    return response()->json([
                        'message' => 'front camera 10 second video request denied.'
                    ], 200);
                }
            }
        } else if ($request->feature_id === '7') {
            $time_start = date('Y-m-d', strtotime($perms->request_bcam10secvid_starttime));
            $time_end = date('Y-m-d', strtotime($perms->request_bcam10secvid_endtime));
            if ($time_start === Carbon::today('Asia/Kolkata')->toDateString() && $time_end === Carbon::today('Asia/Kolkata')->toDateString()) {
                if ($request->option === '1') {
                    $perms->request_bcam10secvid_dayaccess = 1;
                    $perms->save();
                    return response()->json([
                        'message' => 'Back camera 10 Second Video request allowed.'
                    ], 200);
                } else if ($request->option === '0') {
                    $perms->request_bcam10secvid_dayaccess = 0;
                    $perms->save();
                    return response()->json([
                        'message' => 'Back camera 10 Second Video request denied.'
                    ], 200);
                }
            }
        } else if ($request->feature_id === '8') {
            $time_start = date('Y-m-d', strtotime($perms->request_audstream_starttime));
            $time_end = date('Y-m-d', strtotime($perms->request_audstream_endtime));
            if ($time_start === Carbon::today('Asia/Kolkata')->toDateString() && $time_end === Carbon::today('Asia/Kolkata')->toDateString()) {
                if ($request->option === '1') {
                    $perms->request_audstream_dayaccess = 1;
                    $perms->save();
                    return response()->json([
                        'message' => 'Audio Live Streaming request allowed.'
                    ], 200);
                } else if ($request->option === '0') {
                    $perms->request_audstream_dayaccess = 0;
                    $perms->save();
                    return response()->json([
                        'message' => 'Audio Live Streaming request denied.'
                    ], 200);
                }
            }
        } else if ($request->feature_id === '9') {
            $time_start = date('Y-m-d', strtotime($perms->request_aud10secrec_starttime));
            $time_end = date('Y-m-d', strtotime($perms->request_aud10secrec_endtime));
            if ($time_start === Carbon::today('Asia/Kolkata')->toDateString() && $time_end === Carbon::today('Asia/Kolkata')->toDateString()) {
                if ($request->option === '1') {
                    $perms->request_aud10secrec_dayaccess = 1;
                    $perms->save();
                    return response()->json([
                        'message' => '10 Second Audio Recording request allowed.'
                    ], 200);
                } else if ($request->option === '0') {
                    $perms->request_aud10secrec_dayaccess = 0;
                    $perms->save();
                    return response()->json([
                        'message' => '10 Second Audio Recording request denied.'
                    ], 200);
                }
                }
        }
        return response()->json([
            'message' => 'Something went wrong.'
        ], 201);
    }
}
