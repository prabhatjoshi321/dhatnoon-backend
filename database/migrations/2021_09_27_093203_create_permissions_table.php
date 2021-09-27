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
            //start Times
            $table->datetime('request_geoloc_starttime')->default('2021-01-01 20:00:00.718398');
            $table->datetime('request_fcampic_starttime')->default('2021-01-01 20:00:00.718398');
            $table->datetime('request_bcampic_starttime')->default('2021-01-01 20:00:00.718398');
            $table->datetime('request_fcamstream_starttime')->default('2021-01-01 20:00:00.718398');
            $table->datetime('request_bcamstream_starttime')->default('2021-01-01 20:00:00.718398');
            $table->datetime('request_fcam10secvid_starttime')->default('2021-01-01 20:00:00.718398');
            $table->datetime('request_bcam10secvid_starttime')->default('2021-01-01 20:00:00.718398');
            $table->datetime('request_audstream_starttime')->default('2021-01-01 20:00:00.718398');
            $table->datetime('request_aud10secrec_starttime')->default('2021-01-01 20:00:00.718398');
            //end Times
            $table->datetime('request_geoloc_endtime')->default('2021-01-01 20:00:00.718398');
            $table->datetime('request_fcampic_endtime')->default('2021-01-01 20:00:00.718398');
            $table->datetime('request_bcampic_endtime')->default('2021-01-01 20:00:00.718398');
            $table->datetime('request_fcamstream_endtime')->default('2021-01-01 20:00:00.718398');
            $table->datetime('request_bcamstream_endtime')->default('2021-01-01 20:00:00.718398');
            $table->datetime('request_fcam10secvid_endtime')->default('2021-01-01 20:00:00.718398');
            $table->datetime('request_bcam10secvid_endtime')->default('2021-01-01 20:00:00.718398');
            $table->datetime('request_audstream_endtime')->default('2021-01-01 20:00:00.718398');
            $table->datetime('request_aud10secrec_endtime')->default('2021-01-01 20:00:00.718398');
            //request day access bits
            $table->boolean('request_geoloc_dayaccess')->default('0');
            $table->boolean('request_fcampic_dayaccess')->default('0');
            $table->boolean('request_bcampic_dayaccess')->default('0');
            $table->boolean('request_fcamstream_dayaccess')->default('0');
            $table->boolean('request_bcamstream_dayaccess')->default('0');
            $table->boolean('request_fcam10secvid_dayaccess')->default('0');
            $table->boolean('request_bcam10secvid_dayaccess')->default('0');
            $table->boolean('request_audstream_dayaccess')->default('0');
            $table->boolean('request_aud10secrec_dayaccess')->default('0');
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
