<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIOUSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ious', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('requestor_id')->nullable();
            $table->foreign('requestor_id')->references('id')->on('users');

            $table->unsignedBigInteger('debtor_id')->nullable();
            $table->foreign('debtor_id')->references('id')->on('users');

            $table->float('amount')->default(0);
            $table->longText('description')->nullable();
            $table->dateTime('due_by')->nullable();
            $table->boolean('paid')->default(false);

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
        Schema::dropIfExists('i_o_u_s');
    }
}
