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
        Schema::create('loans', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('emp_code'); // Employee code
            $table->string('bank_name'); // Bank name
            $table->decimal('loan_amount', 15, 2); // Loan amount with two decimal places
            $table->text('loan_details'); // Loan details
            $table->timestamps(); // Created at and updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
