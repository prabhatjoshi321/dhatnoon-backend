<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AgoraService;
use Webpatser\Uuid\Uuid;
use Exception;

class AgoraController extends Controller
{
    /**
     * @var AgoraService
     */
    protected $agoraService;

    public function __construct()
    {
        $this->agoraService = app(AgoraService::class);
    }

    public function generateToken()
    {
        try {
            $channelName = (string) Uuid::generate(4);
            // Rtc token dùng để video call
            $token = $this->agoraService->getRtcToken($channelName);
            // Rtm token dùng để chat
            $rtmToken = $this->agoraService->getRtmToken($channelName);
            if (!$token || !$rtmToken) {

                return response()->json([
                    'message' => 'Generate token error',
                ], 400);
            }

            $data = [
                'channel_name' => $channelName,
                'token' => $token,
                'rtm_token' => $rtmToken,
            ];

            return response()->json([
                'message' => 'Success',
                'channel_name' => $channelName,
                'token' => $token,
                'rtm_token' => $rtmToken
            ], 200);
            // return $this->success($data);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'err',
                'error' => $e->getMessage()
            ], 400);
            // return $this->error($e->getMessage());
        }
    }
}
