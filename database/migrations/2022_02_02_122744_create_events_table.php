<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('calendar_id');
            $table->foreign('calendar_id')->references('id')->on('calendars');

            $table->string('name');
            $table->dateTime('datetime');

            $table->string('location_line_1')->nullable();
            $table->string('location_line_2')->nullable();
            $table->string('location_city')->nullable();
            $table->string('location_county')->nullable();
            $table->string('location_postcode')->nullable();

            $table->unsignedBigInteger('home_id')->nullable();
            $table->foreign('home_id')->references('id')->on('homes');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');

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
        Schema::dropIfExists('events');
    }
}
