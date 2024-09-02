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
        Schema::table('effectiveness_checks', function (Blueprint $table) {
            $table->text('more_info_required_effective_by')->nullable();
            $table->text('more_info_required_effective_on')->nullable();
            $table->longText('more_info_required_effective_comment')->nullable();
            $table->text('more_info_required_not_effective_by')->nullable();
            $table->text('more_info_required_not_effective_on')->nullable();
            $table->longText('more_info_required_not_effective_comment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('effectiveness_checks', function (Blueprint $table) {
            //
        });
    }
};
