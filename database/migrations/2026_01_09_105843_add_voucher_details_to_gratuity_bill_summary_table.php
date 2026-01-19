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
            if (!Schema::hasColumn('gratuity_bill_summary', 'voucher_no')) {
                $table->string('voucher_no')->nullable();
            }
            if (!Schema::hasColumn('gratuity_bill_summary', 'voucher_date')) {
                $table->date('voucher_date')->nullable();
            }
            if (!Schema::hasColumn('gratuity_bill_summary', 'id_no')) {
                $table->string('id_no')->nullable();
            }
            if (!Schema::hasColumn('gratuity_bill_summary', 'reference_no')) {
                $table->string('reference_no')->nullable();
            }
            if (!Schema::hasColumn('gratuity_bill_summary', 'reference_date')) {
                $table->date('reference_date')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gratuity_bill_summary', function (Blueprint $table) {
            if (Schema::hasColumn('gratuity_bill_summary', 'voucher_no')) {
                $table->dropColumn('voucher_no');
            }
            if (Schema::hasColumn('gratuity_bill_summary', 'voucher_date')) {
                $table->dropColumn('voucher_date');
            }
            if (Schema::hasColumn('gratuity_bill_summary', 'id_no')) {
                $table->dropColumn('id_no');
            }
            if (Schema::hasColumn('gratuity_bill_summary', 'reference_no')) {
                $table->dropColumn('reference_no');
            }
            if (Schema::hasColumn('gratuity_bill_summary', 'reference_date')) {
                $table->dropColumn('reference_date');
            }
        });
    }
};
