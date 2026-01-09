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
        Schema::table('gratuity_bill_summary', function (Blueprint $table) {
            $table->string('voucher_no')->nullable();
            $table->date('voucher_date')->nullable();
            $table->string('id_no')->nullable();
            $table->string('reference_no')->nullable();
            $table->date('reference_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gratuity_bill_summary', function (Blueprint $table) {
            $table->dropColumn('voucher_no');
            $table->dropColumn('voucher_date');
            $table->dropColumn('id_no');
            $table->dropColumn('reference_no');
            $table->dropColumn('reference_date');
        });
    }
};
