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
        Schema::create('meeting_summary', function (Blueprint $table) {
            $table->id();
            $table->timestamps(); $table->string('risk_opportunities')->nullable();
            $table->string('external_supplier_performance')->nullable();
            $table->string('customer_satisfaction_level')->nullable();
            $table->string('budget_estimates')->nullable();
            $table->string('completion_of_previous_tasks')->nullable();
            $table->string('production')->nullable();
            $table->string('additional_suport_required')->nullable();           
            $table->string('file_attchment_if_any')->nullable();   
            
 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meeting_summary');
    }
};
