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
        Schema::create('docdetails', function (Blueprint $table) {
            $table->id();
            $table->string('cc_id')->nullable();
            $table->string('sno')->nullable();
            $table->string('current_doc_no')->nullable();
            $table->string('current_version_no')->nullable();
            $table->string('new_doc_no')->nullable();
            $table->string('new_version_no')->nullable();
            $table->longText('current_practice')->nullable();
            $table->text('proposed_change')->nullable();
            $table->text('reason_change')->nullable();
            $table->longText('other_comment')->nullable();
            $table->longText('supervisor_comment')->nullable();
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
        Schema::dropIfExists('docdetails');
    }
};