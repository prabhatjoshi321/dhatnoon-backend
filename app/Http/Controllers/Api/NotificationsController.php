<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\notifications;
use Illuminate\Http\Request;
use Auth;
use App\Models\location;
use App\Models\User;
use Carbon\Carbon;
use PhpParser\Node\Expr\Cast\String_;

class NotificationsController extends Controller
{
    public function notif_check()
    {
        $notif = notifications::where([['user_id', '=', Auth::user()->id], ['new', '=', 1]])->first();

        if ($notif == null) {
            return response()->json([
                'message' => 'No Notifications',
            ], 200);
        }
        $notif->new = 0;
        $notif->save();
        return response()->json([
            'message' => $notif->message,
        ], 200);
    }
}
