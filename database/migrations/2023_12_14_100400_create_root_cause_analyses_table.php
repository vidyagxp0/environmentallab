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
        Schema::create('root_cause_analyses', function (Blueprint $table) {
            $table->id();
            $table->string('originator')->nullable();
            $table->string('form_type')->nullable();
            $table->string('date_opened')->nullable();
            $table->string('short_description')->nullable();
            $table->string('assigned_to')->nullable();
            $table->string('due_date')->nullable();
            $table->string('Type')->nullable();
            $table->string('investigators')->nullable();
            $table->string('department')->nullable();
            $table->string('description')->nullable();
            $table->string('comments')->nullable();
            $table->string('root_cause_initial_attachment')->nullable();
            $table->string('related_url')->nullable();
            $table->string('root_cause_methodology')->nullable();
            $table->string('measurement')->nullable();
            $table->string('materials')->nullable();
            $table->string('methods')->nullable();
            $table->string('environment')->nullable();
            $table->string('manpower')->nullable();
            $table->string('machine')->nullable();
            $table->string('problem_statement')->nullable();
            $table->string('why_problem_statement')->nullable();
            $table->string('why_1')->nullable();
            $table->string('why_2')->nullable();
            $table->string('why_3')->nullable();
            $table->string('why_4')->nullable();
            $table->string('why_5')->nullable();
            $table->string('root-cause')->nullable();
            $table->string('what_will_be')->nullable();
            $table->string('what_will_not_be')->nullable();
            $table->string('what_rationable')->nullable();
            $table->string('where_will_be')->nullable();
            $table->string('where_will_not_be')->nullable();
            $table->string('where_rationable')->nullable();
            $table->string('when_will_be')->nullable();
            $table->string('when_will_not_be')->nullable();
            $table->string('when_rationable')->nullable();
            $table->string('coverage_will_be')->nullable();
            $table->string('coverage_will_not_be')->nullable();
            $table->string('coverage_rationable')->nullable();
            $table->string('who_will_be')->nullable();
            $table->string('who_will_not_be')->nullable();
            $table->string('who_rationable')->nullable();
            $table->string('investigation_summary')->nullable();
            $table->string('zone')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            
            $table->integer('record')->nullable();
            // $table->string('division_id')->nullable();
            $table->integer('initiator_id')->nullable();
            // $table->integer('parent_id')->nullable();
            $table->string('division_code')->nullable();
            $table->string('intiation_date')->nullable();
            $table->string('initiator_Group')->nullable();
            $table->integer('assign_id')->nullable();
            $table->string('Sample_Types')->nullable();
            $table->string('test_lab')->nullable();
            $table->string('ten_trend')->nullable();
            // $table->text('attachments')->nullable();
            $table->text('lab_inv_concl')->nullable();
            $table->text('lab_inv_attach')->nullable();
            $table->text('qc_head_comments')->nullable();
            $table->string('inv_attach')->nullable();
            // $table->string('plan_proposed_on')->nullable();
            // $table->string('Plan_approved_on')->nullable();
            // $table->string('qa_more_info_required_on')->nullable();
            // $table->string('cancelled_on')->nullable();
            // $table->string('completed_on')->nullable();
            // $table->string('approved_on')->nullable();
            // $table->string('rejected_on')->nullable();
            $table->string('status')->nullable();
            $table->integer('stage')->nullable();
            // $table->string('submitted_by')->nullable();
            // $table->string('report_result_by')->nullable();
            // $table->string('evaluation_complete_by')->nullable();
            // $table->string('submitted_on')->nullable();
            // $table->string('report_result_on')->nullable();
            // $table->string('evaluation_complete_on')->nullable();
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
        Schema::dropIfExists('root_cause_analyses');
    }
};
