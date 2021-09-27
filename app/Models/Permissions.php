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
            //start Times
            'request_geoloc_starttime',
            'request_fcampic_starttime',
            'request_bcampic_starttime',
            'request_fcamstream_starttime',
            'request_bcamstream_starttime',
            'request_fcam10secvid_starttime',
            'request_bcam10secvid_starttime',
            'request_audstream_starttime',
            'request_aud10secrec_starttime',
            //end Times
            'request_geoloc_endtime',
            'request_fcampic_endtime',
            'request_bcampic_endtime',
            'request_fcamstream_endtime',
            'request_bcamstream_endtime',
            'request_fcam10secvid_endtime',
            'request_bcam10secvid_endtime',
            'request_audstream_endtime',
            'request_aud10secrec_endtime',
            //request day access bits
            'request_geoloc_dayaccess',
            'request_fcampic_dayaccess',
            'request_bcampic_dayaccess',
            'request_fcamstream_dayaccess',
            'request_bcamstream_dayaccess',
            'request_fcam10secvid_dayaccess',
            'request_bcam10secvid_dayaccess',
            'request_audstream_dayaccess',
            'request_aud10secrec_dayaccess',
    ];
}
