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
        Schema::create('management_review_doc_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('review_id')->nullable();
            $table->string('type')->nullable();
            $table->text('date')->nullable();
            $table->text('topic')->nullable();
            $table->text('responsible')->nullable();
            $table->text('start_time')->nullable();
            $table->text('end_time')->nullable();
            $table->text('comment')->nullable();
            $table->text('invited_Person')->nullable();
            $table->text('designee')->nullable();
            $table->text('department')->nullable();
            $table->text('meeting_Attended')->nullable();
            $table->text('designee_Name')->nullable();
            $table->text('designee_Department')->nullable();
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('management_review_doc_details');
    }
};
