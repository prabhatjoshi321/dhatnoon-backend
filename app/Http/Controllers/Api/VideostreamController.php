<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\videostream;
use Illuminate\Http\Request;

class VideostreamController extends Controller
{
    public function on_publish(Request $request)
    {
        if ($request->name == "mystream") {
            return response('Good', 200)->header('Content-Type', 'text/plain');
        } else {
            return response('No', 400)->header('Content-Type', 'text/plain');
        }
    }


}
