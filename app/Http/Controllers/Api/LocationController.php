<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Permissions;
use App\Models\location;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Auth;

class LocationController extends Controller
{


    public function location_save(Request $request)
    {
        $request->validate([
            'lat' => 'required',
            'long' => 'required',
        ]);

        $data = location::where('user_id', Auth::user()->id)->first();
        $data->lat = $request->lat;
        $data->long = $request->long;
        $data->save();

        //perms
        $perms = Permissions::where('user_id', Auth::user()->id)->first();
        $perms->request_geoloc_new_val = 1;
        $perms->request_geoloc_flag = 0;
        $perms->save();

        return response()->json([
            'data' => $data,
            'message' => 'Successfully saved Location of user.'
        ], 201);
    }

    public function call_user_location(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
        ]);
        $perms = Permissions::where([['requester_id', '=', Auth::user()->id], ['user_id', '=', $request->user_id]])->first();
        $perms->request_geoloc_flag = 1;
        $perms->new_flag = 1;
        $perms->save();
        return response()->json([
            'data' => 'Called user location'
        ], 200);
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

        if ($perms->request_geoloc_new_val !== 1) {
            return response()->json([
                'data' => 'Unable to fetch new location'
            ], 400);
        }
        $perms->request_geoloc_new_val = 0;
        $perms->request_geoloc_flag = 0;
        $perms->save();
        if ($perms->request_geoloc_dayaccess) {
            $requester = User::where('id', $perms->user_id)->first();
            $location_params = location::where('user_id', $requester->id)->first();

            if (Carbon::now('Asia/Kolkata')->greaterThan($perms->request_geoloc_starttime) && Carbon::now('Asia/Kolkata')->lessThan($perms->request_geoloc_endtime)) {
                return response()->json([
                    'lat' => $location_params->lat,
                    'long' => $location_params->long,
                ], 200);
            }
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
