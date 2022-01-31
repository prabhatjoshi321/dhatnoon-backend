<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Permissions;
use App\Models\notifications;
use Illuminate\Http\Request;
use Auth;
use App\Models\location;
use App\Models\User;
use Carbon\Carbon;
use PhpParser\Node\Expr\Cast\String_;

class PermissionsController extends Controller
{
    public function request_check()
    {
        $stream_param = Permissions::where('user_id', Auth::user()->id)->first();
        $data = Permissions::where([['user_id', '=', Auth::user()->id], ['new_flag', '=', 1]])->first();

        $fcam_stream = null;
        $bcam_stream = null;
        $fcam10_vid = null;
        $bcam10_vid = null;
        $aud_stream = null;
        $aud_10sec = null;

        if ($stream_param != null) {
            $fcam_stream = $stream_param->request_fcamstream_flag;
            $bcam_stream = $stream_param->request_bcamstream_flag;
            $fcam10_vid = $stream_param->request_fcam10secvid_flag;
            $bcam10_vid = $stream_param->request_bcam10secvid_flag;
            $aud_stream = $stream_param->request_audstream_flag;
            $aud_10sec = $stream_param->request_aud10secrec_flag;
        }
        if ($data == null) {
            return response()->json([
                'message' => 'no requests',
                'feature_id' => 10,
                //stream start stop flags
                'fcam_stream' => $fcam_stream,
                'bcam_stream' => $bcam_stream,
                'fcam10_vid' => $fcam10_vid,
                'bcam10_vid' => $bcam10_vid,
                'aud_stream' => $aud_stream,
                'aud_10sec' => $aud_10sec,
            ], 200);
        }

        $requester = User::where('id', $data->requester_id)->first();
        $data->new_flag = 0;
        $data->save();

        if ($data->request_geoloc_flag) {
            $notif = new notifications([
                'user_id' => Auth::user()->id,
                'message' => $requester->name . ' is viewing your location.',
            ]);
            $notif->save();
            return response()->json([
                'requester' => $requester->name,
                'message' => ' is viewing your location.',
                'feature_id' => 1,
            ], 200);
        }
        if ($data->request_fcampic_flag) {
            $notif = new notifications([
                'user_id' => Auth::user()->id,
                'message' => $requester->name . ' is viewing your front camera pic.',
            ]);
            $notif->save();
            return response()->json([
                'requester' => $requester->name,
                'message' =>  ' is viewing your front camera pic.',
                'feature_id' => 2,
            ], 200);
        }
        if ($data->request_bcampic_flag) {
            $notif = new notifications([
                'user_id' => Auth::user()->id,
                'message' => $requester->name . ' is viewing your rear camera pic.',
            ]);
            $notif->save();
            return response()->json([
                'requester' => $requester->name,
                'message' =>  ' is viewing your rear camera pic.',
                'feature_id' => 3,
            ], 200);
        }
        if ($data->request_fcamstream_flag) {
            $notif = new notifications([
                'user_id' => Auth::user()->id,
                'message' => $requester->name . ' is streaming your front camera.',
            ]);
            $notif->save();
            return response()->json([
                'requester' => $requester->name,
                'message' => ' is streaming your front camera.',
                'feature_id' => 4,
            ], 200);
        }
        if ($data->request_bcamstream_flag) {
            $notif = new notifications([
                'user_id' => Auth::user()->id,
                'message' => $requester->name . ' is streaming your rear camera.',
            ]);
            $notif->save();
            return response()->json([
                'requester' => $requester->name,
                'message' => ' is streaming your rear camera.',
                'feature_id' => 5,
            ], 200);
        }
        if ($data->request_fcam10secvid_flag) {
            $notif = new notifications([
                'user_id' => Auth::user()->id,
                'message' => $requester->name . ' is viewing 10 second video fron your front camera.',
            ]);
            $notif->save();
            return response()->json([
                'requester' => $requester->name,
                'message' =>  ' is viewing 10 second video fron your front camera.',
                'feature_id' => 6,
            ], 200);
        }
        if ($data->request_bcam10secvid_flag) {
            $notif = new notifications([
                'user_id' => Auth::user()->id,
                'message' => $requester->name . ' is viewing 10 second video fron your rear camera.',
            ]);
            $notif->save();
            return response()->json([
                'requester' => $requester->name,
                'message' =>  ' is viewing 10 second video fron your rear camera.',
                'feature_id' => 7,
            ], 200);
        }
        if ($data->request_audstream_flag) {
            $notif = new notifications([
                'user_id' => Auth::user()->id,
                'message' => $requester->name . ' is listening to your mic.',
            ]);
            $notif->save();
            return response()->json([
                'requester' => $requester->name,
                'message' =>  ' is listening to your mic.',
                'feature_id' => 8,
            ], 200);
        }
        if ($data->request_aud10secrec_flag) {
            $notif = new notifications([
                'user_id' => Auth::user()->id,
                'message' => $requester->name . ' is listening to 10 second audio fron your mic.',
            ]);
            $notif->save();
            return response()->json([
                'requester' => $requester->name,
                'message' =>  ' is listening to 10 second audio fron your mic.',
                'feature_id' => 9,
            ], 200);
        }
    }


    // make request controller

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

        //time seq check
        if (Carbon::parse($start_time)->greaterThan(Carbon::parse($end_time))) {
            return response()->json([
                'message' => 'Start time cannot be greater than End time.'
            ], 201);
        }

        $requester_id = Auth::user()->id;
        $requested_user_id = $user->id;

        $requester = User::where('id', $requester_id)->first();

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
                $notif = new notifications([
                    'user_id' => $requested_user_id,
                    'message' => $requester->name . ' has requested your location.',
                ]);
                $notif->save();
                $permission = new Permissions([
                    'user_id' => $requested_user_id,
                    'requester_id' => $requester_id,
                    'request_geoloc_starttime' => $start_time,
                    'request_geoloc_endtime' => $end_time,
                    'request_geoloc_updated_at' => Carbon::now("Asia/Kolkata"),
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
                $notif = new notifications([
                    'user_id' => $requested_user_id,
                    'message' => $requester->name . ' has requested your front camera pic.',
                ]);
                $notif->save();
                $permission = new Permissions([
                    'user_id' => $requested_user_id,
                    'requester_id' => $requester_id,
                    'request_fcampic_starttime' => $start_time,
                    'request_fcampic_endtime' => $end_time,
                    'request_fcampic_updated_at' => Carbon::now("Asia/Kolkata"),
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
                $notif = new notifications([
                    'user_id' => $requested_user_id,
                    'message' => $requester->name . ' has requested your rear camera pic.',
                ]);
                $notif->save();
                $permission = new Permissions([
                    'user_id' => $requested_user_id,
                    'requester_id' => $requester_id,
                    'request_bcampic_starttime' => $start_time,
                    'request_bcampic_endtime' => $end_time,
                    'request_bcampic_updated_at' => Carbon::now("Asia/Kolkata"),
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
                $notif = new notifications([
                    'user_id' => $requested_user_id,
                    'message' => $requester->name . ' has requested your front camera stream.',
                ]);
                $notif->save();
                $permission = new Permissions([
                    'user_id' => $requested_user_id,
                    'requester_id' => $requester_id,
                    'request_fcamstream_starttime' => $start_time,
                    'request_fcamstream_endtime' => $end_time,
                    'request_fcamstream_updated_at' => Carbon::now("Asia/Kolkata"),
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
                $notif = new notifications([
                    'user_id' => $requested_user_id,
                    'message' => $requester->name . ' has requested your rear camera stream.',
                ]);
                $notif->save();
                $permission = new Permissions([
                    'user_id' => $requested_user_id,
                    'requester_id' => $requester_id,
                    'request_bcamstream_starttime' => $start_time,
                    'request_bcamstream_endtime' => $end_time,
                    'request_bcamstream_updated_at' => Carbon::now("Asia/Kolkata"),
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
                $notif = new notifications([
                    'user_id' => $requested_user_id,
                    'message' => $requester->name . ' has requested 10 second video fron your front camera.',
                ]);
                $notif->save();
                $permission = new Permissions([
                    'user_id' => $requested_user_id,
                    'requester_id' => $requester_id,
                    'request_fcam10secvid_starttime' => $start_time,
                    'request_fcam10secvid_endtime' => $end_time,
                    'request_fcam10secvid_updated_at' => Carbon::now("Asia/Kolkata"),
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
                $notif = new notifications([
                    'user_id' => $requested_user_id,
                    'message' => $requester->name . ' has requested 10 second video fron your rear camera.',
                ]);
                $notif->save();
                $permission = new Permissions([
                    'user_id' => $requested_user_id,
                    'requester_id' => $requester_id,
                    'request_bcam10secvid_starttime' => $start_time,
                    'request_bcam10secvid_endtime' => $end_time,
                    'request_bcam10secvid_updated_at' => Carbon::now("Asia/Kolkata"),
                    'request_bcam10secvid_dayaccess' => 0,
                    'new_flag' => 1,
                ]);
                $permission->save();
                return response()->json([
                    'data' => $permission,
                    'message' => 'Back camera 10 Second video request sent.'
                ], 201);
            }

            //Audio Live Streaming.
            if ($request->selected_option === 'Audio Live Streaming.') {
                $notif = new notifications([
                    'user_id' => $requested_user_id,
                    'message' => $requester->name . ' has requested to listen to your mic.',
                ]);
                $notif->save();
                $permission = new Permissions([
                    'user_id' => $requested_user_id,
                    'requester_id' => $requester_id,
                    'request_audstream_starttime' => $start_time,
                    'request_audstream_endtime' => $end_time,
                    'request_audstream_updated_at' => Carbon::now("Asia/Kolkata"),
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
                $notif = new notifications([
                    'user_id' => $requested_user_id,
                    'message' => $requester->name . ' has requested for a 10 second audio recording fron your mic.',
                ]);
                $notif->save();
                $permission = new Permissions([
                    'user_id' => $requested_user_id,
                    'requester_id' => $requester_id,
                    'request_aud10secrec_starttime' => $start_time,
                    'request_aud10secrec_endtime' => $end_time,
                    'request_aud10secrec_updated_at' => Carbon::now("Asia/Kolkata"),
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
                $notif = new notifications([
                    'user_id' => $requested_user_id,
                    'message' => $requester->name . ' has requested your location.',
                ]);
                $notif->save();
                $exist->request_geoloc_starttime = $start_time;
                $exist->request_geoloc_endtime = $end_time;
                $exist->request_geoloc_updated_at = Carbon::now("Asia/Kolkata");
                $exist->request_geoloc_dayaccess = 0;
                $exist->new_flag = 1;
                $exist->save();
                return response()->json([
                    'message' => 'Geolocation request sent.'
                ], 201);
            }

            //Front Camera Pic
            if ($request->selected_option === 'Front Camera Pic.') {
                $notif = new notifications([
                    'user_id' => $requested_user_id,
                    'message' => $requester->name . ' has requested your front camera pic.',
                ]);
                $notif->save();
                $exist->request_fcampic_starttime = $start_time;
                $exist->request_fcampic_endtime = $end_time;
                $exist->request_fcampic_updated_at = Carbon::now("Asia/Kolkata");
                $exist->request_fcampic_dayaccess = 0;
                $exist->new_flag = 1;
                $exist->save();
                return response()->json([
                    'message' => 'Front camera pic request sent.'
                ], 201);
            }

            //Back Camera Pic
            if ($request->selected_option === 'Back Camera Pic.') {
                $notif = new notifications([
                    'user_id' => $requested_user_id,
                    'message' => $requester->name . ' has requested your rear camera pic.',
                ]);
                $notif->save();
                $exist->request_bcampic_starttime = $start_time;
                $exist->request_bcampic_endtime = $end_time;
                $exist->request_bcampic_updated_at = Carbon::now("Asia/Kolkata");
                $exist->request_bcampic_dayaccess = 0;
                $exist->new_flag = 1;
                $exist->save();
                return response()->json([
                    'message' => 'Back camera pic request sent.'
                ], 201);
            }

            //Front camera streaming
            if ($request->selected_option === 'Front Camera Streaming.') {
                $notif = new notifications([
                    'user_id' => $requested_user_id,
                    'message' => $requester->name . ' has requested your front camera stream.',
                ]);
                $notif->save();
                $exist->request_fcamstream_starttime = $start_time;
                $exist->request_fcamstream_endtime = $end_time;
                $exist->request_fcamstream_updated_at = Carbon::now("Asia/Kolkata");
                $exist->request_fcamstream_dayaccess = 0;
                $exist->new_flag = 1;
                $exist->save();
                return response()->json([
                    'message' => 'Front camera streaming request sent.'
                ], 201);
            }

            //Back camera streaming
            if ($request->selected_option === 'Back Camera Streaming.') {
                $notif = new notifications([
                    'user_id' => $requested_user_id,
                    'message' => $requester->name . ' has requested your rear camera stream.',
                ]);
                $notif->save();
                $exist->request_bcamstream_starttime = $start_time;
                $exist->request_bcamstream_endtime = $end_time;
                $exist->request_bcamstream_updated_at = Carbon::now("Asia/Kolkata");
                $exist->request_bcamstream_dayaccess = 0;
                $exist->new_flag = 1;
                $exist->save();
                return response()->json([
                    'message' => 'Back camera streaming request sent.'
                ], 201);
            }

            //Front Camera 10 Second Video.
            if ($request->selected_option === 'Front Camera 10 Second Video.') {
                $notif = new notifications([
                    'user_id' => $requested_user_id,
                    'message' => $requester->name . ' has requested 10 second video fron your front camera.',
                ]);
                $notif->save();
                $exist->request_fcam10secvid_starttime = $start_time;
                $exist->request_fcam10secvid_endtime = $end_time;
                $exist->request_fcam10secvid_updated_at = Carbon::now("Asia/Kolkata");
                $exist->request_fcam10secvid_dayaccess = 0;
                $exist->new_flag = 1;
                $exist->save();
                return response()->json([
                    'message' => 'front camera 10 second video request sent.'
                ], 201);
            }

            //Back camera 10 Second Video.
            if ($request->selected_option === 'Back camera 10 Second Video.') {
                $notif = new notifications([
                    'user_id' => $requested_user_id,
                    'message' => $requester->name . ' has requested 10 second video fron your rear camera.',
                ]);
                $notif->save();
                $exist->request_bcam10secvid_starttime = $start_time;
                $exist->request_bcam10secvid_endtime = $end_time;
                $exist->request_bcam10secvid_updated_at = Carbon::now("Asia/Kolkata");
                $exist->request_bcam10secvid_dayaccess = 0;
                $exist->new_flag = 1;
                $exist->save();
                return response()->json([
                    'message' => 'Back camera 10 Second Video request sent.'
                ], 201);
            }

            //Audio Live Streaming.
            if ($request->selected_option === 'Audio Live Streaming.') {
                $notif = new notifications([
                    'user_id' => $requested_user_id,
                    'message' => $requester->name . ' has requested to listen to your mic.',
                ]);
                $notif->save();
                $exist->request_audstream_starttime = $start_time;
                $exist->request_audstream_endtime = $end_time;
                $exist->request_audstream_updated_at = Carbon::now("Asia/Kolkata");
                $exist->request_audstream_dayaccess = 0;
                $exist->new_flag = 1;
                $exist->save();
                return response()->json([
                    'message' => 'Audio Live Streaming request sent.'
                ], 201);
            }

            //10 Second Audio Recording.
            if ($request->selected_option === '10 Second Audio Recording.') {
                $notif = new notifications([
                    'user_id' => $requested_user_id,
                    'message' => $requester->name . ' has requested for a 10 second audio recording fron your mic.',
                ]);
                $notif->save();
                $exist->request_aud10secrec_starttime = $start_time;
                $exist->request_aud10secrec_endtime = $end_time;
                $exist->request_aud10secrec_updated_at = Carbon::now("Asia/Kolkata");
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

            $user = User::where('id', $item->user_id)->first();
            if ($item->request_geoloc_dayaccess) {
                if (Carbon::now('Asia/Kolkata')->greaterThan($item->request_geoloc_starttime) && Carbon::now('Asia/Kolkata')->lessThan($item->request_geoloc_endtime)) {
                    $data_arranged[] = [
                        'user_id' => $user->id,
                        'feature' => 'Live Geo Location',
                        'feature_id' => 1,
                        'user_name' => $user->name,
                        'user_phno' => $user->phone_number,
                        'start_time' => $item->request_geoloc_starttime,
                        'end_time' => $item->request_geoloc_endtime,
                        'updated_at' => $item->request_geoloc_updated_at,
                    ];
                }
            }
            if ($item->request_fcampic_dayaccess) {
                if (Carbon::now('Asia/Kolkata')->greaterThan($item->request_fcampic_starttime) && Carbon::now('Asia/Kolkata')->lessThan($item->request_fcampic_endtime)) {
                    $data_arranged[] = [
                        'user_id' => $user->id,
                        'feature' => 'Front Camera Pic',
                        'feature_id' => 2,
                        'user_name' => $user->name,
                        'user_phno' => $user->phone_number,
                        'start_time' => $item->request_fcampic_starttime,
                        'end_time' => $item->request_fcampic_endtime,
                        'updated_at' => $item->request_fcampic_updated_at,
                    ];
                }
            }
            if ($item->request_bcampic_dayaccess) {
                if (Carbon::now('Asia/Kolkata')->greaterThan($item->request_bcampic_starttime) && Carbon::now('Asia/Kolkata')->lessThan($item->request_bcampic_endtime)) {
                    $data_arranged[] = [
                        'user_id' => $user->id,
                        'feature' => 'Back Camera Pic',
                        'feature_id' => 3,
                        'user_name' => $user->name,
                        'user_phno' => $user->phone_number,
                        'start_time' => $item->request_bcampic_starttime,
                        'end_time' => $item->request_bcampic_endtime,
                        'updated_at' => $item->request_bcampic_updated_at,
                    ];
                }
            }
            if ($item->request_fcamstream_dayaccess) {
                if (Carbon::now('Asia/Kolkata')->greaterThan($item->request_fcamstream_starttime) && Carbon::now('Asia/Kolkata')->lessThan($item->request_fcamstream_endtime)) {
                    $data_arranged[] = [
                        'user_id' => $user->id,
                        'feature' => 'Front Camera Streaming',
                        'feature_id' => 4,
                        'user_name' => $user->name,
                        'user_phno' => $user->phone_number,
                        'start_time' => $item->request_fcamstream_starttime,
                        'end_time' => $item->request_fcamstream_endtime,
                        'updated_at' => $item->request_fcamstream_updated_at,
                    ];
                }
            }
            if ($item->request_bcamstream_dayaccess) {
                if (Carbon::now('Asia/Kolkata')->greaterThan($item->request_bcamstream_starttime) && Carbon::now('Asia/Kolkata')->lessThan($item->request_bcamstream_endtime)) {
                    $data_arranged[] = [
                        'user_id' => $user->id,
                        'feature' => 'Back Camera Streaming',
                        'feature_id' => 5,
                        'user_name' => $user->name,
                        'user_phno' => $user->phone_number,
                        'start_time' => $item->request_bcamstream_starttime,
                        'end_time' => $item->request_bcamstream_endtime,
                        'updated_at' => $item->request_bcamstream_updated_at,
                    ];
                }
            }
            if ($item->request_fcam10secvid_dayaccess) {
                if (Carbon::now('Asia/Kolkata')->greaterThan($item->request_fcam10secvid_starttime) && Carbon::now('Asia/Kolkata')->lessThan($item->request_fcam10secvid_endtime)) {
                    $data_arranged[] = [
                        'user_id' => $user->id,
                        'feature' => 'Front Camera 10 Second Video',
                        'feature_id' => 6,
                        'user_name' => $user->name,
                        'user_phno' => $user->phone_number,
                        'start_time' => $item->request_fcam10secvid_starttime,
                        'end_time' => $item->request_fcam10secvid_endtime,
                        'updated_at' => $item->request_fcam10secvid_updated_at,
                    ];
                }
            }
            if ($item->request_bcam10secvid_dayaccess) {
                if (Carbon::now('Asia/Kolkata')->greaterThan($item->request_bcam10secvid_starttime) && Carbon::now('Asia/Kolkata')->lessThan($item->request_bcam10secvid_endtime)) {
                    $data_arranged[] = [
                        'user_id' => $user->id,
                        'feature' => 'Back camera 10 Second Video',
                        'feature_id' => 7,
                        'user_name' => $user->name,
                        'user_phno' => $user->phone_number,
                        'start_time' => $item->request_bcam10secvid_starttime,
                        'end_time' => $item->request_bcam10secvid_endtime,
                        'updated_at' => $item->request_bcam10secvid_updated_at,
                    ];
                }
            }
            if ($item->request_audstream_dayaccess) {
                if (Carbon::now('Asia/Kolkata')->greaterThan($item->request_audstream_starttime) && Carbon::now('Asia/Kolkata')->lessThan($item->request_audstream_endtime)) {
                    $data_arranged[] = [
                        'user_id' => $user->id,
                        'feature' => 'Audio Live Streaming',
                        'feature_id' => 8,
                        'user_name' => $user->name,
                        'user_phno' => $user->phone_number,
                        'start_time' => $item->request_audstream_starttime,
                        'end_time' => $item->request_audstream_endtime,
                        'updated_at' => $item->request_audstream_updated_at,
                    ];
                }
            }
            if ($item->request_aud10secrec_dayaccess) {
                if (Carbon::now('Asia/Kolkata')->greaterThan($item->request_aud10secrec_starttime) && Carbon::now('Asia/Kolkata')->lessThan($item->request_aud10secrec_endtime)) {
                    $data_arranged[] = [
                        'user_id' => $user->id,
                        'feature' => '10 Second Audio Recording',
                        'feature_id' => 9,
                        'user_name' => $user->name,
                        'user_phno' => $user->phone_number,
                        'start_time' => $item->request_aud10secrec_starttime,
                        'end_time' => $item->request_aud10secrec_endtime,
                        'updated_at' => $item->request_aud10secrec_updated_at,
                    ];
                }
            }
        }

        $ord = array();
        foreach ($data_arranged as $key => $value) {
            $ord[] = strtotime($value['updated_at']);
        }
        array_multisort($ord, SORT_DESC, $data_arranged);


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
            if (Carbon::now('Asia/Kolkata')->greaterThan($item->request_geoloc_starttime) && Carbon::now('Asia/Kolkata')->lessThan($item->request_geoloc_endtime)) {
                $data_arranged[] = [
                    'permisssion_id' =>  $item->id,
                    'feature' => 'Live Geo Location',
                    'feature_id' => 1,
                    'requester_id' => $requester->id,
                    'requester_name' => $requester->name,
                    'requester_phno' => $requester->phone_number,
                    'start_time' => $item->request_geoloc_starttime,
                    'end_time' => $item->request_geoloc_endtime,
                    'updated_at' => $item->request_geoloc_updated_at,
                    'day_access' => $item->request_geoloc_dayaccess,
                ];
            }
            if (Carbon::now('Asia/Kolkata')->greaterThan($item->request_fcampic_starttime) && Carbon::now('Asia/Kolkata')->lessThan($item->request_fcampic_endtime)) {
                $data_arranged[] = [
                    'permission_id' => $item->id,
                    'feature' => 'Front Camera Pic',
                    'feature_id' => 2,
                    'requester_id' => $requester->id,
                    'requester_name' => $requester->name,
                    'requester_phno' => $requester->phone_number,
                    'start_time' => $item->request_fcampic_starttime,
                    'end_time' => $item->request_fcampic_endtime,
                    'updated_at' => $item->request_fcampic_updated_at,
                    'day_access' => $item->request_fcampic_dayaccess,
                ];
            }
            if (Carbon::now('Asia/Kolkata')->greaterThan($item->request_bcampic_starttime) && Carbon::now('Asia/Kolkata')->lessThan($item->request_bcampic_endtime)) {
                $data_arranged[] = [
                    'permission_id' => $item->id,
                    'feature' => 'Back Camera Pic',
                    'feature_id' => 3,
                    'requester_id' => $requester->id,
                    'requester_name' => $requester->name,
                    'requester_phno' => $requester->phone_number,
                    'start_time' => $item->request_bcampic_starttime,
                    'end_time' => $item->request_bcampic_endtime,
                    'updated_at' => $item->request_bcampic_updated_at,
                    'day_access' => $item->request_bcampic_dayaccess,
                ];
            }
            if (Carbon::now('Asia/Kolkata')->greaterThan($item->request_fcamstream_starttime) && Carbon::now('Asia/Kolkata')->lessThan($item->request_fcamstream_endtime)) {
                $data_arranged[] = [
                    'permission_id' => $item->id,
                    'feature' => 'Front Camera Streaming',
                    'feature_id' => 4,
                    'requester_id' => $requester->id,
                    'requester_name' => $requester->name,
                    'requester_phno' => $requester->phone_number,
                    'start_time' => $item->request_fcamstream_starttime,
                    'end_time' => $item->request_fcamstream_endtime,
                    'updated_at' => $item->request_fcamstream_updated_at,
                    'day_access' => $item->request_fcamstream_dayaccess,
                ];
            }
            if (Carbon::now('Asia/Kolkata')->greaterThan($item->request_bcamstream_starttime) && Carbon::now('Asia/Kolkata')->lessThan($item->request_bcamstream_endtime)) {
                $data_arranged[] = [
                    'permission_id' => $item->id,
                    'feature' => 'Back Camera Streaming',
                    'feature_id' => 5,
                    'requester_id' => $requester->id,
                    'requester_name' => $requester->name,
                    'requester_phno' => $requester->phone_number,
                    'start_time' => $item->request_bcamstream_starttime,
                    'end_time' => $item->request_bcamstream_endtime,
                    'updated_at' => $item->request_bcamstream_updated_at,
                    'day_access' => $item->request_bcamstream_dayaccess,
                ];
            }
            if (Carbon::now('Asia/Kolkata')->greaterThan($item->request_fcam10secvid_starttime) && Carbon::now('Asia/Kolkata')->lessThan($item->request_fcam10secvid_endtime)) {
                $data_arranged[] = [
                    'permission_id' => $item->id,
                    'feature' => 'Front Camera 10 Second Video',
                    'feature_id' => 6,
                    'requester_id' => $requester->id,
                    'requester_name' => $requester->name,
                    'requester_phno' => $requester->phone_number,
                    'start_time' => $item->request_fcam10secvid_starttime,
                    'end_time' => $item->request_fcam10secvid_endtime,
                    'updated_at' => $item->request_fcam10secvid_updated_at,
                    'day_access' => $item->request_fcam10secvid_dayaccess,
                ];
            }
            if (Carbon::now('Asia/Kolkata')->greaterThan($item->request_bcam10secvid_starttime) && Carbon::now('Asia/Kolkata')->lessThan($item->request_bcam10secvid_endtime)) {
                $data_arranged[] = [
                    'permission_id' => $item->id,
                    'feature' => 'Back camera 10 Second Video',
                    'feature_id' => 7,
                    'requester_id' => $requester->id,
                    'requester_name' => $requester->name,
                    'requester_phno' => $requester->phone_number,
                    'start_time' => $item->request_bcam10secvid_starttime,
                    'end_time' => $item->request_bcam10secvid_endtime,
                    'updated_at' => $item->request_bcam10secvid_updated_at,
                    'day_access' => $item->request_bcam10secvid_dayaccess,
                ];
            }
            if (Carbon::now('Asia/Kolkata')->greaterThan($item->request_audstream_starttime) && Carbon::now('Asia/Kolkata')->lessThan($item->request_audstream_endtime)) {
                $data_arranged[] = [
                    'permission_id' => $item->id,
                    'feature' => 'Audio Live Streaming',
                    'feature_id' => 8,
                    'requester_id' => $requester->id,
                    'requester_name' => $requester->name,
                    'requester_phno' => $requester->phone_number,
                    'start_time' => $item->request_audstream_starttime,
                    'end_time' => $item->request_audstream_endtime,
                    'updated_at' => $item->request_audstream_updated_at,
                    'day_access' => $item->request_audstream_dayaccess,
                ];
            }
            if (Carbon::now('Asia/Kolkata')->greaterThan($item->request_aud10secrec_starttime) && Carbon::now('Asia/Kolkata')->lessThan($item->request_aud10secrec_endtime)) {
                $data_arranged[] = [
                    'permission_id' => $item->id,
                    'feature' => '10 Second Audio Recording',
                    'feature_id' => 9,
                    'requester_id' => $requester->id,
                    'requester_name' => $requester->name,
                    'requester_phno' => $requester->phone_number,
                    'start_time' => $item->request_aud10secrec_starttime,
                    'end_time' => $item->request_aud10secrec_endtime,
                    'updated_at' => $item->request_aud10secrec_updated_at,
                    'day_access' => $item->request_aud10secrec_dayaccess,
                ];
            }
        }

        $ord = array();
        foreach ($data_arranged as $key => $value) {
            $ord[] = strtotime($value['updated_at']);
        }
        array_multisort($ord, SORT_DESC, $data_arranged);

        return response()->json([
            'data' => $data_arranged
        ], 201);
    }



    public function allow_deny_controller(Request $request)
    {
        $request->validate([
            'option' => 'required|numeric',
            'feature_id' => 'required|numeric',
            'requester_id' => 'required|numeric'
        ]);
        $perms = Permissions::where([['requester_id', '=', $request->requester_id], ['user_id', '=', Auth::user()->id]])->first();
        $user = User::where('id', Auth::user()->id)->first();

        if ($request->feature_id === '1') {
            $time_start = date('Y-m-d', strtotime($perms->request_geoloc_starttime));
            $time_end = date('Y-m-d', strtotime($perms->request_geoloc_endtime));
            if ($time_start === Carbon::today('Asia/Kolkata')->toDateString() && $time_end === Carbon::today('Asia/Kolkata')->toDateString()) {
                if ($request->option === '1') {
                    $notif = new notifications([
                        'user_id' => $request->requester_id,
                        'message' => $user->name . ' has allowed your request for location.',
                    ]);
                    $notif->save();
                    $perms->request_geoloc_dayaccess = 1;
                    $perms->save();
                    return response()->json([
                        'message' => 'Geolocation request allowed.'
                    ], 200);
                } else if ($request->option === '0') {
                    $notif = new notifications([
                        'user_id' => $request->requester_id,
                        'message' => $user->name . ' has denied your request for location.',
                    ]);
                    $notif->save();
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
                    $notif = new notifications([
                        'user_id' => $request->requester_id,
                        'message' => $user->name . ' has allowed your request for Front camera pic.',
                    ]);
                    $notif->save();
                    $perms->request_fcampic_dayaccess = 1;
                    $perms->save();
                    return response()->json([
                        'message' => 'Front camera pic request allowed.'
                    ], 200);
                } else if ($request->option === '0') {
                    $notif = new notifications([
                        'user_id' => $request->requester_id,
                        'message' => $user->name . ' has denied your request for Front camera pic.',
                    ]);
                    $notif->save();
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
                    $notif = new notifications([
                        'user_id' => $request->requester_id,
                        'message' => $user->name . ' has allowed your request for Back camera pic.',
                    ]);
                    $notif->save();
                    $perms->request_bcampic_dayaccess = 1;
                    $perms->save();
                    return response()->json([
                        'message' => 'Back camera pic request allowed.'
                    ], 200);
                } else if ($request->option === '0') {
                    $notif = new notifications([
                        'user_id' => $request->requester_id,
                        'message' => $user->name . ' has denied your request for Back camera pic.',
                    ]);
                    $notif->save();
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
                    $notif = new notifications([
                        'user_id' => $request->requester_id,
                        'message' => $user->name . ' has allowed your request for Front camera streaming.',
                    ]);
                    $notif->save();
                    $perms->request_fcamstream_dayaccess = 1;
                    $perms->save();
                    return response()->json([
                        'message' => 'Front camera streaming request allowed.'
                    ], 200);
                } else if ($request->option === '0') {
                    $notif = new notifications([
                        'user_id' => $request->requester_id,
                        'message' => $user->name . ' has denied your request for Front camera streaming.',
                    ]);
                    $notif->save();
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
                    $notif = new notifications([
                        'user_id' => $request->requester_id,
                        'message' => $user->name . ' has allowed your request for Back camera streaming.',
                    ]);
                    $notif->save();
                    $perms->request_bcamstream_dayaccess = 1;
                    $perms->save();
                    return response()->json([
                        'message' => 'Back camera streaming request allowed.'
                    ], 200);
                } else if ($request->option === '0') {
                    $notif = new notifications([
                        'user_id' => $request->requester_id,
                        'message' => $user->name . ' has denied your request for Back camera streaming.',
                    ]);
                    $notif->save();
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
                    $notif = new notifications([
                        'user_id' => $request->requester_id,
                        'message' => $user->name . ' has allowed your request for 10 second video from front camera.',
                    ]);
                    $notif->save();
                    $perms->request_fcam10secvid_dayaccess = 1;
                    $perms->save();
                    return response()->json([
                        'message' => 'front camera 10 second video request allowed.'
                    ], 200);
                } else if ($request->option === '0') {
                    $notif = new notifications([
                        'user_id' => $request->requester_id,
                        'message' => $user->name . ' has denied your request for 10 second video from front camera.',
                    ]);
                    $notif->save();
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
                    $notif = new notifications([
                        'user_id' => $request->requester_id,
                        'message' => $user->name . ' has allowed your request for 10 second video from rear camera.',
                    ]);
                    $notif->save();
                    $perms->request_bcam10secvid_dayaccess = 1;
                    $perms->save();
                    return response()->json([
                        'message' => 'Back camera 10 Second Video request allowed.'
                    ], 200);
                } else if ($request->option === '0') {
                    $notif = new notifications([
                        'user_id' => $request->requester_id,
                        'message' => $user->name . ' has denied your request for 10 second video from rear camera.',
                    ]);
                    $notif->save();
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
                    $notif = new notifications([
                        'user_id' => $request->requester_id,
                        'message' => $user->name . ' has allowed your request for Audio Live Streaming.',
                    ]);
                    $notif->save();
                    $perms->request_audstream_dayaccess = 1;
                    $perms->save();
                    return response()->json([
                        'message' => 'Audio Live Streaming request allowed.'
                    ], 200);
                } else if ($request->option === '0') {
                    $notif = new notifications([
                        'user_id' => $request->requester_id,
                        'message' => $user->name . ' has denied your request for Audio Live Streaming.',
                    ]);
                    $notif->save();
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
                    $notif = new notifications([
                        'user_id' => $request->requester_id,
                        'message' => $user->name . ' has allowed your request for 10 Second Audio Recording.',
                    ]);
                    $notif->save();
                    $perms->request_aud10secrec_dayaccess = 1;
                    $perms->save();
                    return response()->json([
                        'message' => '10 Second Audio Recording request allowed.'
                    ], 200);
                } else if ($request->option === '0') {
                    $notif = new notifications([
                        'user_id' => $request->requester_id,
                        'message' => $user->name . ' has denied your request for 10 Second Audio Recording.',
                    ]);
                    $notif->save();
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
