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
        Schema::table('detailed_heads', function (Blueprint $table) {
            $table->decimal('opening_debit', 15, 2)->default(00.00);  // Opening Debit
            $table->decimal('opening_credit', 15, 2)->default(00.00); // Opening Credit
            $table->decimal('debit_amount', 15, 2)->default(00.00);    // Debit Amount
            $table->decimal('credit_amount', 15, 2)->default(00.00);   // Credit Amount
            $table->decimal('closing_debit', 15, 2)->default(00.00);   // Closing Debit
            $table->decimal('closing_credit', 15, 2)->default(00.00);  // Closing Credit
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detailed_heads', function (Blueprint $table) {
            $table->dropColumn([
                'opening_debit',
                'opening_credit',
                'debit_amount',
                'credit_amount',
                'closing_debit',
                'closing_credit',
            ]);
        });
    }
};
