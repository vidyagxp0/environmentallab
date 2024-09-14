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
        Schema::table('lab_incidents', function (Blueprint $table) {
            $table->string('request_more_info_by')->nullable();
            $table->string('request_more_info_on')->nullable();
            $table->string('request_more_information_by')->nullable();
            $table->string('request_more_information_on')->nullable();
            $table->string('further_investigation_required_by')->nullable();
            $table->string('further_investigation_required_on')->nullable();
            $table->string('return_to_pending_capa_by')->nullable();
            $table->string('return_to_pending_capa_on')->nullable();
            $table->string('return_to_qa_review_by')->nullable();
            $table->string('return_to_qa_review_on')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lab_incidents', function (Blueprint $table) {
            //
        });
    }
};