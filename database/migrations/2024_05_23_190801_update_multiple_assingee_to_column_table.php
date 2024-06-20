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
        Schema::table('internal_audits', function (Blueprint $table) {
            $table->string('multiple_assignee_to')->nullable();
        });
        Schema::table('auditees', function (Blueprint $table) {
            $table->string('multiple_assignee_to')->nullable();
            $table->string('external_auditor_name')->nullable();
            $table->string('area_of_auditing')->nullable();
        });
        Schema::table('audit_program_grids', function (Blueprint $table) {
            $table->string('auditee')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('internal_audits', function (Blueprint $table) {
            Schema::dropIfExists('multiple_assignee_to');
        });
        Schema::table('auditees', function (Blueprint $table) {
            Schema::dropIfExists('multiple_assignee_to');
        });
        Schema::table('audit_program_grids', function (Blueprint $table) {
            Schema::dropIfExists('auditee');
        });
    }
};
