<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Audiostream extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'user_id',
        'request_audiostream_notifier',
        'request_audiostream',
        'agora_channel_name',
        'agora_token',
        'agora_rtm_token',
        'audiostream_url',
    ];
}
