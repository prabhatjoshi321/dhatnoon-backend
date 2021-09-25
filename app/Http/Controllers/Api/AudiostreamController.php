<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api;

use App\Models\audiostream;
use Illuminate\Http\Request;

class AudiostreamController extends Controller
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
    public function create()
    {
        //
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
     * @param  \App\Models\audiostream  $audiostream
     * @return \Illuminate\Http\Response
     */
    public function show(audiostream $audiostream)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\audiostream  $audiostream
     * @return \Illuminate\Http\Response
     */
    public function edit(audiostream $audiostream)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\audiostream  $audiostream
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, audiostream $audiostream)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\audiostream  $audiostream
     * @return \Illuminate\Http\Response
     */
    public function destroy(audiostream $audiostream)
    {
        //
    }
}
