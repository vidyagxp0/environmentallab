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
        Schema::create('document_contents', function (Blueprint $table) {
            $table->id();
            $table->string('purpose')->nullable();
            $table->string('scope')->nullable();
            $table->string('responsibility')->nullable();
            $table->string('abbreviation')->nullable();
            $table->string('defination')->nullable();
            $table->string('procedure')->nullable();
            $table->string('reporting')->nullable();
            $table->string('reference_text')->nullable();
            $table->string('references')->nullable();
            $table->string('ann')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('annexure_number')->nullable();
            $table->string('annexure_data')->nullable();
            // $table->integer('reporting')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('document_content');
    }
};
