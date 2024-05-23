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
        Schema::table('auditees', function (Blueprint $table) {
            // $table->string('external_auditor_name')->nullable();
            // $table->string('area_of_auditing')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('auditees', function (Blueprint $table) {
            Schema::whenTableHasColumn('auditees', 'external_auditor_name', function () {
                // Schema::dropColumns('auditees', 'external_auditor_name');
            });
            Schema::whenTableHasColumn('auditees', 'area_of_auditing', function () {
                // Schema::dropColumns('auditees', 'area_of_auditing');
            });
        });
    }
};
