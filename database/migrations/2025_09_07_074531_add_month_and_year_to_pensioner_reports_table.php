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
        Schema::table('pensioner_reports', function (Blueprint $table) {
            $table->integer('month')->after('id')->nullable();
            $table->integer('year')->after('month')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pensioner_reports', function (Blueprint $table) {
            $table->dropColumn(['month', 'year']);
        });
    }
};
