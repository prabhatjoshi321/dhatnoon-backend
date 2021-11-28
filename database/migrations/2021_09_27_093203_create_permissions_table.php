<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Ramsey\Uuid\Type\Integer;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('requester_id');

            //Geolocation
            $table->datetime('request_geoloc_starttime')->default('2021-01-01 20:00:00.718398');
            $table->datetime('request_geoloc_endtime')->default('2021-01-01 20:00:00.718398');
            $table->datetime('request_geoloc_updated_at')->default('2021-01-01 20:00:00.718398');
            $table->boolean('request_geoloc_new_val')->default('0');
            $table->boolean('request_geoloc_dayaccess')->default('0');
            $table->boolean('request_geoloc_flag')->default('0');

            //Frontcam post
            $table->datetime('request_fcampic_starttime')->default('2021-01-01 20:00:00.718398');
            $table->datetime('request_fcampic_endtime')->default('2021-01-01 20:00:00.718398');
            $table->datetime('request_fcampic_updated_at')->default('2021-01-01 20:00:00.718398');
            $table->boolean('request_fcampic_new_val')->default('0');
            $table->boolean('request_fcampic_dayaccess')->default('0');
            $table->boolean('request_fcampic_flag')->default('0');

            //rearcam post
            $table->datetime('request_bcampic_starttime')->default('2021-01-01 20:00:00.718398');
            $table->datetime('request_bcampic_endtime')->default('2021-01-01 20:00:00.718398');
            $table->datetime('request_bcampic_updated_at')->default('2021-01-01 20:00:00.718398');
            $table->boolean('request_bcampic_new_val')->default('0');
            $table->boolean('request_bcampic_dayaccess')->default('0');
            $table->boolean('request_bcampic_flag')->default('0');

            //frontcam stream
            $table->datetime('request_fcamstream_starttime')->default('2021-01-01 20:00:00.718398');
            $table->datetime('request_fcamstream_endtime')->default('2021-01-01 20:00:00.718398');
            $table->datetime('request_fcamstream_updated_at')->default('2021-01-01 20:00:00.718398');
            $table->boolean('request_fcamstream_new_val')->default('0');
            $table->boolean('request_fcamstream_dayaccess')->default('0');
            $table->boolean('request_fcamstream_flag')->default('0');

            // rearcam stream
            $table->datetime('request_bcamstream_endtime')->default('2021-01-01 20:00:00.718398');
            $table->datetime('request_bcamstream_starttime')->default('2021-01-01 20:00:00.718398');
            $table->datetime('request_bcamstream_updated_at')->default('2021-01-01 20:00:00.718398');
            $table->boolean('request_bcamstream_new_val')->default('0');
            $table->boolean('request_bcamstream_dayaccess')->default('0');
            $table->boolean('request_bcamstream_flag')->default('0');

            // front cam 10 sec video
            $table->datetime('request_fcam10secvid_starttime')->default('2021-01-01 20:00:00.718398');
            $table->datetime('request_fcam10secvid_endtime')->default('2021-01-01 20:00:00.718398');
            $table->datetime('request_fcam10secvid_updated_at')->default('2021-01-01 20:00:00.718398');
            $table->boolean('request_fcam10secvid_new_val')->default('0');
            $table->boolean('request_fcam10secvid_dayaccess')->default('0');
            $table->boolean('request_fcam10secvid_flag')->default('0');

            // rear cam 10 sec video
            $table->datetime('request_bcam10secvid_starttime')->default('2021-01-01 20:00:00.718398');
            $table->datetime('request_bcam10secvid_endtime')->default('2021-01-01 20:00:00.718398');
            $table->datetime('request_bcam10secvid_updated_at')->default('2021-01-01 20:00:00.718398');
            $table->boolean('request_bcam10secvid_new_val')->default('0');
            $table->boolean('request_bcam10secvid_dayaccess')->default('0');
            $table->boolean('request_bcam10secvid_flag')->default('0');

            // audio stream
            $table->datetime('request_audstream_starttime')->default('2021-01-01 20:00:00.718398');
            $table->datetime('request_audstream_endtime')->default('2021-01-01 20:00:00.718398');
            $table->datetime('request_audstream_updated_at')->default('2021-01-01 20:00:00.718398');
            $table->boolean('request_audstream_new_val')->default('0');
            $table->boolean('request_audstream_dayaccess')->default('0');
            $table->boolean('request_audstream_flag')->default('0');

            // audio 10 second recording
            $table->datetime('request_aud10secrec_starttime')->default('2021-01-01 20:00:00.718398');
            $table->datetime('request_aud10secrec_endtime')->default('2021-01-01 20:00:00.718398');
            $table->datetime('request_aud10secrec_updated_at')->default('2021-01-01 20:00:00.718398');
            $table->boolean('request_aud10secrec_new_val')->default('0');
            $table->boolean('request_aud10secrec_dayaccess')->default('0');
            $table->boolean('request_aud10secrec_flag')->default('0');

            //New Flag
            $table->boolean('new_flag')->default('0');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permissions');
    }
}
