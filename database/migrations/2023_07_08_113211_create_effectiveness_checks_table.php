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
        Schema::create('effectiveness_checks', function (Blueprint $table) {
            $table->id();
            $table->string('is_parent')->default('yes');
            $table->string('parent_record')->nullable();
            $table->integer('initiator_id')->nullable();
            $table->string('division_code')->nullable();
            $table->integer('parent_id')->nullable();
            $table->string('parent_type')->nullable();
            $table->string('division_id')->nullable();
            $table->string('intiation_date')->nullable();
            $table->string('due_date')->nullable();
            $table->string('record')->nullable();
            $table->string('originator')->nullable();
            $table->string('short_description')->nullable();
            $table->string('assign_to')->nullable();
            $table->string('Q_A')->nullable();
            $table->string('Effectiveness_check_Plan')->nullable();
            $table->string('Effectiveness_Summary')->nullable();
            $table->string('Effectiveness_Results')->nullable();
            $table->string('Effectiveness_check_Attachment')->nullable();
            $table->string('Addendum_Comments')->nullable();
            $table->string('Addendum_Attachment')->nullable();
            $table->string('Comments')->nullable();
            $table->string('Attachment')->nullable();
            $table->string('status')->default('Opened');
            $table->string('stage')->default(1);

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
        Schema::dropIfExists('effectiveness_checks');
    }
};
