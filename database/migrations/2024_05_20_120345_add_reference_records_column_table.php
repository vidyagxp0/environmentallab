<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('capas', function (Blueprint $table) {
                        $table->text('rca_related_record')->nullable();

        });
        Schema::table('internal_audits', function (Blueprint $table) {
                        $table->text('rca_refrence_record')->nullable();
                        $table->text('ai_refrence_record')->nullable();
                        $table->text('cc_refrence_record')->nullable();
                        $table->text('capa_refrence_record')->nullable();

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
            Schema::dropIfExists('deleted_at');
            Schema::dropIfExists('rca_refrence_record');
                        Schema::dropIfExists('ai_refrence_record');
                        Schema::dropIfExists('cc_refrence_record');
                        Schema::dropIfExists('capa_refrence_record');
        });
        Schema::table('capas', function (Blueprint $table) {
            Schema::dropIfExists('rca_related_record');
        });
    }
};
