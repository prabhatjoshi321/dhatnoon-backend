<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Permissions extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
            'user_id',
            'requester_id',
            // geolocation
            'request_geoloc_starttime',
            'request_geoloc_endtime',
            'request_geoloc_updated_at',
            'request_geoloc_new_val',
            'request_geoloc_dayaccess',
            'request_geoloc_flag',
            //front camera pic
            'request_fcampic_starttime',
            'request_fcampic_endtime',
            'request_fcampic_updated_at',
            'request_fcampic_new_val',
            'request_fcampic_dayaccess',
            'request_fcampic_flag',
            //back campic
            'request_bcampic_starttime',
            'request_bcampic_endtime',
            'request_bcampic_updated_at',
            'request_bcampic_new_val',
            'request_bcampic_dayaccess',
            'request_bcampic_flag',
            //front camera stream
            'request_fcamstream_starttime',
            'request_fcamstream_endtime',
            'request_fcamstream_updated_at',
            'request_fcamstream_new_val',
            'request_fcamstream_dayaccess',
            'request_fcamstream_flag',
            //rear camera stream
            'request_bcamstream_starttime',
            'request_bcamstream_endtime',
            'request_bcamstream_updated_at',
            'request_bcamstream_new_val',
            'request_bcamstream_dayaccess',
            'request_bcamstream_flag',
            //fcam 10 second video
            'request_fcam10secvid_starttime',
            'request_fcam10secvid_endtime',
            'request_fcam10secvid_updated_at',
            'request_fcam10secvid_new_val',
            'request_fcam10secvid_dayaccess',
            'request_fcam10secvid_flag',
            //bcam 10 second video
            'request_bcam10secvid_starttime',
            'request_bcam10secvid_endtime',
            'request_bcam10secvid_updated_at',
            'request_bcam10secvid_new_val',
            'request_bcam10secvid_dayaccess',
            'request_bcam10secvid_flag',
            //audio stream
            'request_audstream_starttime',
            'request_audstream_endtime',
            'request_audstream_updated_at',
            'request_audstream_new_val',
            'request_audstream_dayaccess',
            'request_audstream_flag',
            //audio 10 second recording
            'request_aud10secrec_starttime',
            'request_aud10secrec_endtime',
            'request_aud10secrec_updated_at',
            'request_aud10secrec_new_val',
            'request_aud10secrec_dayaccess',
            'request_aud10secrec_flag',


            // new flag
            'new_flag',
        ];
}
