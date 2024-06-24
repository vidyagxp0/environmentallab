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
        Schema::create('training_activity_log', function (Blueprint $table) {
            $table->id();
            $table->string('training_id');
            $table->string('doc_id');
            $table->string('user_id');
            $table->string('status');
            $table->string('training_complition');
            $table->string('classroom_trainer');
            $table->string('training_cordinator');
            $table->string('due_date');
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
        Schema::dropIfExists('training_activity_log');
    }
};
