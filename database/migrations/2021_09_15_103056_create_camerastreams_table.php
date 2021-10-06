<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCamerastreamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('camerastreams', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('frontcam_pic');
            $table->string('rearcam_pic');
            $table->boolean('frontcam_request');
            $table->boolean('rearcam_request');
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
        Schema::dropIfExists('camerastreams');
    }
}
