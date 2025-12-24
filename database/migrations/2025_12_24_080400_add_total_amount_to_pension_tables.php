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
            $table->decimal('total_amount', 15, 2)->default(0.00)->after('year');
        });

        Schema::table('pensioner_other_bills', function (Blueprint $table) {
            $table->decimal('total_amount', 15, 2)->default(0.00)->after('details');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pensioner_reports', function (Blueprint $table) {
            $table->dropColumn('total_amount');
        });

        Schema::table('pensioner_other_bills', function (Blueprint $table) {
            $table->dropColumn('total_amount');
        });
    }
};