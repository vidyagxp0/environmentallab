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
        Schema::create('effectiveness_check_audit_trail', function (Blueprint $table) {
            $table->id();
            $table->text('effectiveness_id')->nullable();
            $table->text('activity_type')->nullable();
            $table->longText('previous')->nullable();
            $table->longText('current')->nullable();
            $table->longText('comment')->nullable();
            $table->text('action')->nullable();
            $table->text('stage')->nullable();
            $table->text('user_id')->nullable();
            $table->text('user_name')->nullable();
            $table->text('origin_state')->nullable();
            $table->text('user_role')->nullable();
            $table->text('change_to')->nullable();
            $table->text('change_from')->nullable();
            $table->text('step')->nullable();
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
        Schema::dropIfExists('effectiveness_check_audit_trail');
    }
};
