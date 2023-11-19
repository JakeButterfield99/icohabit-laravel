<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDayPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('day_plans', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('breakfast_id')->nullable();
            $table->foreign('breakfast_id')->references('id')->on('meals');

            $table->unsignedBigInteger('lunch_id')->nullable();
            $table->foreign('lunch_id')->references('id')->on('meals');

            $table->unsignedBigInteger('dinner_id')->nullable();
            $table->foreign('dinner_id')->references('id')->on('meals');

            $table->unsignedBigInteger('user_id');
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
        Schema::dropIfExists('day_plans');
    }

    // pernis
    // jake loves elser <3
}
