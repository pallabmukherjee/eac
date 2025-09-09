<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pensioner_other_bill_summaries', function (Blueprint $table) {
            $table->unsignedBigInteger('pensioner_id')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pensioner_other_bill_summaries', function (Blueprint $table) {
            $table->tinyInteger('pensioner_id')->change();
        });
    }
};