<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planners', function (Blueprint $table) {
            $table->id();

            $table->string('name');

            $table->unsignedBigInteger('mon_id')->nullable();
            $table->foreign('mon_id')->references('id')->on('day_plans');

            $table->unsignedBigInteger('tues_id')->nullable();
            $table->foreign('tues_id')->references('id')->on('day_plans');

            $table->unsignedBigInteger('wed_id')->nullable();
            $table->foreign('wed_id')->references('id')->on('day_plans');

            $table->unsignedBigInteger('thurs_id')->nullable();
            $table->foreign('thurs_id')->references('id')->on('day_plans');

            $table->unsignedBigInteger('fri_id')->nullable();
            $table->foreign('fri_id')->references('id')->on('day_plans');

            $table->unsignedBigInteger('sat_id')->nullable();
            $table->foreign('sat_id')->references('id')->on('day_plans');

            $table->unsignedBigInteger('sun_id')->nullable();
            $table->foreign('sun_id')->references('id')->on('day_plans');

            $table->string('visible')->default('private');

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
        Schema::dropIfExists('planners');
    }
}
