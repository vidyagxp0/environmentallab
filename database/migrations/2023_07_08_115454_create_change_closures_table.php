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
        Schema::create('change_closures', function (Blueprint $table) {
            $table->id();
            $table->integer('cc_id');
            $table->string('sno')->nullable();
            $table->string('affected_document')->nullable();
            $table->string('doc_name')->nullable();
            $table->string('doc_no')->nullable();
            $table->string('version_no')->nullable();
            $table->string('implementation_date')->nullable();
            $table->string('new_doc_no')->nullable();
            $table->string('new_version_no')->nullable();
            $table->text('qa_closure_comments')->nullable();
            $table->text('attach_list')->nullable();
            $table->text('effective_check')->nullable();
            $table->date('effective_check_date')->nullable();
            $table->text('Effectiveness_checker')->nullable();
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
        Schema::dropIfExists('change_closures');
    }
};
