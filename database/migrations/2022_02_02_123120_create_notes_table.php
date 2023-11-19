<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('pinboard_id')->nullable();
            $table->foreign('pinboard_id')->references('id')->on('pinboards');

            $table->unsignedBigInteger('category_id')->default(0);
            $table->foreign('category_id')->references('id')->on('categories');

            $table->longText('content');
            $table->timestamp('datetime')->useCurrent();
            $table->boolean('anon')->default(false);

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
        Schema::dropIfExists('notes');
    }
}
