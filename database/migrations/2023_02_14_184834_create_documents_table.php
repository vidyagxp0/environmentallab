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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->integer('originator_id')->nullable();
            $table->integer('division_id')->nullable();
            $table->integer('process_id')->nullable();
            $table->integer('record')->nullable();
            $table->string('revised')->default('No');
            $table->integer('revised_doc')->default('0');
            $table->longtext('document_name');
            $table->longtext('short_description')->nullable();
            // $table->longtext('assigned_to')->nullable();
            $table->string('due_date')->nullable();
            $table->longtext('description')->nullable();
            $table->longtext('notify_to')->nullable();
            $table->longtext('reference_record')->nullable();
            $table->longtext('department_id')->nullable();
            $table->longtext('document_type_id')->nullable();
            $table->longtext('document_subtype_id')->nullable();
            $table->longtext('document_language_id')->nullable();
            $table->longtext('keywords')->nullable();
            $table->string('effectve_date')->nullable();
            $table->string('next_review_date')->nullable();
            $table->string('review_period')->nullable();
            $table->longtext('attach_draft_doocument')->nullable();
            $table->longtext('attach_effective_docuement')->nullable();
            $table->longtext('reviewers')->nullable();
            $table->longtext('approvers')->nullable();
            $table->longtext('reviewers_group')->nullable();
            $table->longtext('approver_group')->nullable();
            // $table->longtext('distribution_list')->nullable();
            $table->longtext('revision_summary')->nullable();
            $table->integer('stage')->default(1);
            $table->string('status');
            //$table->string('training_required');
            $table->longtext('document')->nullable();
            $table->string('revision')->default("No");
            $table->string('revision_policy')->nullable();
           // $table->longtext('revision_status')->nullable();
            // $table->longtext('defination')->nullable();
            // $table->longtext('materials_and_equipments')->nullable();
            // $table->longtext('procedure')->nullable();
            // $table->longtext('reporting')->nullable();
            // $table->longtext('references')->nullable();
            // $table->string('trainer')->nullable();
            // $table->string('reporting')->nullable();
            // $table->string('references')->nullable();
            // $table->string('serial_number')->nullable();
            // $table->string('annexure_number')->nullable();
            // $table->string('annexure_data')->nullable();
            // $table->string('annexuredata')->nullable();
            // $table->string('reference_text')->nullable();
            // $table->string('references')->nullable();
        

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
        Schema::dropIfExists('documents');
    }
};
