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
        Schema::table('risk_management', function (Blueprint $table) {
            $table->text('More_info1_by')->nullable();
            $table->text('More_info1_on')->nullable();
            $table->text('More_info2_by')->nullable();
            $table->text('More_info2_on')->nullable();
            $table->text('More_info3_by')->nullable();
            $table->text('More_info3_on')->nullable();
            $table->text('More_info4_by')->nullable();
            $table->text('More_info4_on')->nullable();
            $table->text('More_info5_by')->nullable();
            $table->text('More_info5_on')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('risk_management', function (Blueprint $table) {
            //
        });
    }
};
