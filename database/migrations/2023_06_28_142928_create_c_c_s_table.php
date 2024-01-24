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
            $table->longText('Inititator_Group')->nullable();
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
            //$table->string('Microbiology_Person')->nullable();
            $table->string('Production')->nullable();
            $table->string('Production_Person')->nullable();
            $table->string('Quality_Approver')->nullable();
            $table->string('Quality_Approver_Person')->nullable();
            $table->string('bd_domestic')->nullable();
            $table->string('Bd_Person')->nullable();
            //$table->string('additional_attachments')->nullable();


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
