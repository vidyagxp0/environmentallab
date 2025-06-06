<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('action_item_histories', function (Blueprint $table) {
            //$table->string('action_name')->nullable();
            $table->string('mailUserId')->nullable();
            $table->string('role_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('action_item_histories', function (Blueprint $table) {
            //
        });
    }
};
