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
        Schema::create('rv_cheque_list', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('voucher_id'); // Voucher ID
            $table->string('bill_no'); // Bill number
            $table->string('depositor_name'); // Depositor name
            $table->string('bank_name'); // Bank name
            $table->string('cheque_no'); // Cheque number
            $table->decimal('amount', 15, 2); // Amount with 2 decimal points and a max of 15 digits
            $table->string('cheque_submit_bank')->nullable(); // Optional field for cheque submit bank
            $table->tinyInteger('status')->default(1); // Status field with a default value of 1
            $table->timestamps(); // Created at & Updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rv_cheque_list');
    }
};
