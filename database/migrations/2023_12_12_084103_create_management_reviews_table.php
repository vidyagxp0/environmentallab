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
            // $table->integer('record_number')->nullable();
            $table->string('Operations')->nullable();
            $table->string('requirement_products_services')->nullable();
            $table->string('design_development_product_services')->nullable();
            $table->string('control_externally_provide_services')->nullable();
            $table->string('production_service_provision')->nullable();
            $table->string('release_product_services')->nullable();
            $table->string('control_nonconforming_outputs')->nullable();
            $table->string('risk_opportunities')->nullable();
            $table->string('action-item-details')->nullable();
            $table->integer('serial_number')->nullable();
            $table->date('date')->nullable();
            $table->string('topic')->nullable();
            $table->string('responsible')->nullable();
            $table->string('start_time')->nullable();
            $table->string('comment')->nullable();
    

            $table->integer('assign_id')->nullable();
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
            $table->date('updated_at')->nullable();
            $table->date('created_at')->nullable();
            //$table->date('origin_state')->nullable();



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
