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
        Schema::create('contra_vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_id');
            $table->string('bill_no');
            $table->date('date');
            $table->string('from_head');
            $table->string('from_bank');
            $table->string('to_head');
            $table->string('to_bank');
            $table->decimal('cash_amount', 15, 2)->nullable();
            $table->string('cheque_no')->nullable();
            $table->date('cheque_date')->nullable();
            $table->decimal('cheque_amount', 15, 2)->nullable();
            $table->decimal('online_amount', 15, 2)->nullable();
            $table->text('online_remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contra_vouchers');
    }
};
