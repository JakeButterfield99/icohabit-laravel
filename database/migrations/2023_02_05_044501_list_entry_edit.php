<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ListEntryEdit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('list_entries', function (Blueprint $table) {
            $table->integer('quantity')->default(1);
            $table->boolean('checked')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('list_entries', function (Blueprint $table) {
            $table->dropColumn('quantity');
            $table->dropColumn('checked');
        });
    }
}
