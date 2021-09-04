<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\location;
use Illuminate\Http\Request;
use Auth;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function location_save(Request $request)
    {
        $request->validate([
            'lat' => 'required',
            'long' => 'required',
            'start_time' => '',
            'end_time' => '',
        ]);

        // $data = Location::find(Auth::user()->id);
        $data = Location::where('user_id', Auth::user()->id)->first();

        $data->lat = $request->lat;
        $data->long = $request->long;

        if( $request->start_time != null){
            $data->start_time = $request->start_time;
            $data->end_time = $request->end_time;
        }else{
            $data->start_time = $data->start_time;
            $data->end_time = $data->end_time;
        }

        $data->save();

        return response()->json([
            'data' => $data,
            'status' => $data->day_access,
            'start_time' => $data->start_time,
            'end_time' => $data->end_time,
            'message' => 'Successfully saved Location!'
        ], 201);
    }

    public function location_choice(Request $request)
    {
        $request->validate([
            'lat' => 'required',
            'long' => 'required',
            'day_access' => '',
        ]);

        // $data = Location::find(Auth::user()->id);
        $data = Location::where('user_id', Auth::user()->id)->first();

        $data->lat = $request->lat;
        $data->long = $request->long;

        if( $request->day_access != null){
            $data->day_access = $request->day_access;
        }

        $data->save();

        return response()->json([
            'data' => $data,
            'status' => $data->day_access,
            'start_time' => $data->start_time,
            'end_time' => $data->end_time,
            'message' => 'Successfully saved Location!'
        ], 201);
    }

    public function user_check(Request $request)
    {
        $request -> validate([
            'user_id' => 'required'
        ]);

        $data = Location::where('user_id', $request->user_id)->first();
        return response()->json([
            'data' => $data,
            'lat' => $data->lat,
            'long' => $data->long,
            'start_time' => $data->start_time,
            'end_time' => $data->end_time,
            'day_access' => $data->day_access,
        ]);

    }

    public function Location_request(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        // $data = Location::find(Auth::user()->id);
        $data = Location::where('user_id', $request->user_id)->first();

        $data->start_time = $request->start_time;
        $data->end_time = $request->end_time;
        $data->day_access = 0;

        $data->save();

        return response()->json([
            'data' => $data,
            'status' => $data->day_access,
            'start_time' => $data->start_time,
            'end_time' => $data->end_time,
            'message' => 'Successfully saved Location!'
        ], 201);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\location  $location
     * @return \Illuminate\Http\Response
     */
    public function show(location $location)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\location  $location
     * @return \Illuminate\Http\Response
     */
    public function edit(location $location)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\location  $location
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, location $location)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\location  $location
     * @return \Illuminate\Http\Response
     */
    public function destroy(location $location)
    {
        //
    }
}
