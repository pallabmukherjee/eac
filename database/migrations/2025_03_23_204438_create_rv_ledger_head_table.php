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
        Schema::create('rv_ledger_head', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_id');
            $table->string('ledger_head');
            $table->string('cash_head')->nullable();
            $table->decimal('cash_amount', 15, 2)->default(0.00);
            $table->decimal('cheque_amount', 15, 2)->default(0.00);
            $table->string('online_head')->nullable();
            $table->decimal('online_amount', 15, 2)->default(0.00);
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rv_ledger_head');
    }
};
