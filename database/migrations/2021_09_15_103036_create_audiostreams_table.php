<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAudiostreamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audiostreams', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->boolean('request_audiostream_notifier');
            $table->boolean('request_audiostream');
            $table->string('agora_channel_name');
            $table->string('agora_token');
            $table->string('agora_rtm_token');
            $table->boolean('request_audiostream10sec_notifier')->default('0');
            $table->string('audiostream_url')->default('');
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
        Schema::dropIfExists('audiostreams');
    }
}
