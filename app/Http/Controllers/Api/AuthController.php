<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Location;
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
            'email' => 'required|string|unique:users',
            'usertype' => 'required|integer',
            'password' => 'required|string|confirmed'
        ]);

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'usertype' => $request->usertype,
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
        $token->expires_at = Carbon::now()->addWeeks(20);
        $token->save();

        $user_avail = Location::where('user_id', $user->id)->first();

        if($user_avail == null){
            $location = new Location([
                'user_id' => $user->id,
                'lat' => 0,
                'long' => 0,
                'start_time' => '2021-09-03 17:44:24.718398',
                'end_time' => '2021-09-03 17:44:24.718398',
            ]);
            $location->save();
        }

        return response()->json([
            'username' => $user->name,
            'id' => $user->id,
            'usertype' => $user->usertype,
            'access_token' => $tokenResult->accessToken,
            'access_t' => $user_avail,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
            'misc' => $user
        ]);
    }


    public function user_get()
    {
        $usertype = Auth::user()->usertype;

        if($usertype === 2){
            return response()->json([
                'unauthorised',
            ], 401);
        }


        return response()->json([
            'data' => User::where('usertype', 2)->get()
        ]);
    }

}

