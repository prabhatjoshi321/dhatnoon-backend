<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
class Videostream extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'user_id',
        'frontcam_request_stream_notifier',
        'rearcam_request_stream_notifier',
        'frontcam_request_stream',
        'rearcam_request_stream',
        'agora_channel_name',
        'agora_token',
        'agora_rtm_token',
    ];
}
