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
        Schema::create('gratuity_loan_pay', function (Blueprint $table) {
            $table->id();
            $table->string('bill_id');
            $table->string('bill_no');
            $table->string('emp_id');
            $table->string('bank');
            $table->decimal('amount', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gratuity_loan_pay');
    }
};
