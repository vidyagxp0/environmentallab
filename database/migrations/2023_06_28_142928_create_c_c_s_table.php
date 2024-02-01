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
        Schema::create('c_c_s', function (Blueprint $table) {
            $table->id();
            $table->integer('initiator_id')->nullable();
            $table->string('division_id')->nullable();

            $table->string('form_type')->nullable();
            $table->integer('record')->nullable();
            $table->integer('parent_id')->nullable();
            $table->string('parent_type')->nullable();
            $table->longText('short_description')->nullable();
            $table->longText('severity_level1')->nullable();    
            //$table->longText('Initiator_Group')->nullable();
            $table->integer('assign_to')->nullable();
            $table->string('due_date')->nullable();
            // $table->string('training_required')->default('No');

            $table->string('doc_change')->nullable();
            $table->string('If_Others')->nullable();
            $table->string('Division_Code')->nullable();
            $table->string('in_attachment')->nullable();
            $table->string('current_practice')->nullable();
            $table->string('proposed_change')->nullable();
            $table->string('reason_change')->nullable();
            $table->string('other_comment')->nullable();
            $table->string('supervisor_comment')->nullable();
            $table->string('type_chnage')->nullable();
            $table->string('qa_comments')->nullable();
            $table->string('related_records')->nullable();
            $table->string('qa_head')->nullable();

            $table->string('qa_eval_comments')->nullable();
            $table->string('qa_eval_attach')->nullable();
            $table->string('training_required')->nullable();
            $table->string('train_comments')->nullable();

            $table->string('Microbiology')->nullable(); 
            $table->text('Microbiology_Person')->nullable();
            $table->string('goup_review')->nullable();
            $table->string('Production')->nullable();
            $table->string('Production_Person')->nullable();
            $table->string('Quality_Approver')->nullable();
            $table->string('Quality_Approver_Person')->nullable();
            $table->string('bd_domestic')->nullable();
            $table->string('Bd_Person')->nullable();
            $table->string('additional_attachments')->nullable();

            $table->string('cft_comments')->nullable();
            $table->string('cft_attchament')->nullable();
            $table->string('qa_commentss')->nullable();
            $table->string('designee_comments')->nullable();
            $table->string('Warehouse_comments')->nullable();
            $table->string('Engineering_comments')->nullable();
            $table->string('Instrumentation_comments')->nullable();
            $table->string('Validation_comments')->nullable();
            $table->string('Others_comments')->nullable();
            $table->string('Group_comments')->nullable();
            $table->string('group_attachments')->nullable();
            
            $table->string('risk_identification')->nullable();
            $table->string('severity')->nullable();
            $table->string('Occurance')->nullable();
            $table->string('Detection')->nullable();
            $table->string('RPN')->nullable();
            $table->string('risk_evaluation')->nullable();
            $table->string('migration_action')->nullable();

            $table->string('qa_appro_comments')->nullable();
            $table->string('feedback')->nullable();
            $table->string('tran_attach')->nullable();

            $table->string('qa_closure_comments')->nullable();
            $table->string('attach_list')->nullable();
            $table->string('effective_check')->nullable();
            $table->string('effective_check_date')->nullable();
            $table->string('Effectiveness_checker')->nullable();
            $table->string('effective_check_plan')->nullable();
            $table->string('due_date_extension')->nullable();

            $table->string('status')->nullable();
            $table->integer('stage')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('c_c_s');
    }
};
