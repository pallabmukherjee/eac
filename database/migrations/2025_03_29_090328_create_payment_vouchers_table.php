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
        Schema::create('payment_vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('p_voucher_id');
            $table->string('bill_no');
            $table->date('date');
            $table->string('payee');
            $table->string('scheme_fund');
            $table->string('payment_mode');
            $table->string('bank')->nullable();
            $table->string('reference_number')->nullable();
            $table->date('reference_date')->nullable();
            $table->text('narration')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_vouchers');
    }
};
