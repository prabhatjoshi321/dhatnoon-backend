<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('lat');
            $table->string('long');
            $table->dateTime('start_time')->default('2021-09-03 17:44:24.718398');
            $table->dateTime('end_time')->default('2021-09-03 17:44:24.718398');
            $table->boolean('day_access')->default(0);
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
        Schema::dropIfExists('locations');
    }
}
