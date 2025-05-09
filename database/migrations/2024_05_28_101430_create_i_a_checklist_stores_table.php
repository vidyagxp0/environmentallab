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
        Schema::create('i_a_checklist_stores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ia_id')->nullable();

            for ($i = 1; $i <= 31; $i++) {
                $table->text("checklist_stores_response_$i")->nullable();
                 $table->text("checklist_stores_remark_$i")->nullable();
            }

            $table->string('checklist_stores_response_comment')->nullable();
            $table->longText('checklist_stores_response_attachment')->nullable();
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
        Schema::dropIfExists('i_a_checklist_stores');
    }
};
