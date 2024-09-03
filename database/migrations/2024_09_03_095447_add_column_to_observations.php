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
        Schema::table('observations', function (Blueprint $table) {
            $table->text('report_issued_by')->nullable();
            $table->text('report_issued_on')->nullable();
            $table->text('qa_approvel_without_capa_by')->nullable();
            $table->text('qa_approvel_without_capa_on')->nullable();
            $table->text('reject_capa_plan_by')->nullable();
            $table->text('reject_capa_plan_on')->nullable();
            $table->text('all_capa_closed_by')->nullable();
            $table->text('all_capa_closed_on')->nullable();
            $table->text('final_approvel_by')->nullable();
            $table->text('final_approvel_on')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('observations', function (Blueprint $table) {
            //
        });
    }
};
