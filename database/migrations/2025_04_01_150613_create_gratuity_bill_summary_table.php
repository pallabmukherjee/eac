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
        Schema::create('gratuity_bill_summary', function (Blueprint $table) {
            $table->id();
            $table->string('bill_id');
            $table->string('bill_no');
            $table->string('emp_id');
            $table->string('voucher_number')->nullable();
            $table->date('voucher_date')->nullable();
            $table->string('id_number')->nullable();
            $table->string('reference')->nullable();
            $table->decimal('gratuity_amount', 10, 2)->nullable();
            $table->decimal('loan_amount', 10, 2)->nullable();
            $table->timestamps();  // Created at and updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gratuity_bill_summary');
    }
};
