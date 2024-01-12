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
            $table->integer('record')->nullable();
            $table->string('form_type')->nullable();
            $table->string('division_id')->nullable();
            $table->integer('initiator_id')->nullable();
            $table->integer('parent_id')->nullable();
            $table->string('parent_type')->nullable();
            $table->string('division_code')->nullable();
            $table->string('intiation_date')->nullable();
            $table->string('initiator_Group')->nullable();
            $table->text('short_description')->nullable();
            $table->string('due_date')->nullable();
            $table->integer('assign_id')->nullable();
            $table->string('Sample_Types')->nullable();
            $table->string('test_lab')->nullable();

            $table->string('ten_trend')->nullable();
            $table->string('investigators')->nullable();
            $table->text('attachments')->nullable();
            $table->string('comments')->nullable();
            $table->text('lab_inv_concl')->nullable();
            $table->text('lab_inv_attach')->nullable();
            $table->text('qc_head_comments')->nullable();
            $table->string('inv_attach')->nullable();
            $table->string('plan_proposed_on')->nullable();
            $table->string('Plan_approved_on')->nullable();
            $table->string('qa_more_info_required_on')->nullable();
            $table->string('cancelled_on')->nullable();
            $table->string('completed_on')->nullable();
            $table->string('approved_on')->nullable();
            $table->string('rejected_on')->nullable();
            $table->string('status')->nullable();
            $table->integer('stage')->nullable();

            $table->string('submitted_by')->nullable();
            $table->string('report_result_by')->nullable();
            $table->string('evaluation_complete_by')->nullable();

            $table->string('submitted_on')->nullable();
            $table->string('report_result_on')->nullable();
            $table->string('evaluation_complete_on')->nullable();
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
