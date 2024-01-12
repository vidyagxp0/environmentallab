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
        Schema::create('c_c_s', function (Blueprint $table) {
            $table->id();
            $table->integer('initiator_id')->nullable();
            $table->string('division_id')->nullable();

            $table->string('form_type')->nullable();
            $table->integer('record')->nullable();
            $table->integer('parent_id')->nullable();
            $table->string('parent_type')->nullable();
            $table->longText('short_description')->nullable();
            $table->longText('Inititator_Group')->nullable();
            $table->integer('assign_to')->nullable();
            $table->string('due_date')->nullable();
            // $table->string('training_required')->default('No');

            $table->string('doc_change')->nullable();
            $table->string('If_Others')->nullable();
            $table->string('Division_Code')->nullable();
            $table->string('in_attachment')->nullable();
            $table->string('status')->nullable();
            $table->integer('stage')->nullable();
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
        Schema::dropIfExists('c_c_s');
    }
};
