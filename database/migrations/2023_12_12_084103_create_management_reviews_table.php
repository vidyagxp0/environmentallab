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
        Schema::create('management_reviews', function (Blueprint $table) {
            $table->id();
            $table->integer('initiator_id')->nullable();
            $table->string('division_id')->nullable();
            $table->string('form_type')->nullable();
            $table->integer('record')->nullable();
            $table->integer('parent_id')->nullable();
            $table->string('parent_type')->nullable();
            $table->string('division_code')->nullable();
            $table->text('short_description')->nullable();
            $table->string('assigned_to')->nullable();
            $table->string('due_date')->nullable();
            $table->string('intiation_date')->nullable();
            $table->string('type')->nullable();
            $table->string('priority_level')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('attendees')->nullable();
            $table->text('agenda')->nullable();
            $table->text('description')->nullable();
            $table->text('attachment')->nullable();
            $table->text('inv_attachment')->nullable();
            $table->date('actual_start_date')->nullable();
            $table->date('actual_end_date')->nullable();
            $table->string('meeting_minute')->nullable();
            $table->string('decision')->nullable();
            $table->string('zone')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('site_name')->nullable();
            $table->string('building')->nullable();
            $table->string('floor')->nullable();
            $table->string('room')->nullable();
            $table->string('status')->nullable();
            $table->integer('stage')->nullable();

            $table->string('completed_by')->nullable();

            $table->string('completed_on')->nullable();
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('management_reviews');
    }
};
