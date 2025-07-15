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
        Schema::table('p_vouchar_deduct', function (Blueprint $table) {
            $table->string('bill_no')->nullable()->after('voucher_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('p_vouchar_deduct', function (Blueprint $table) {
            $table->dropColumn('bill_no');
        });
    }
};
