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
        Schema::create('ledger_reports', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_id');
            $table->string('ledger');
            $table->decimal('amount', 15, 2);
            $table->tinyInteger('pay_deduct');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ledger_reports');
    }
};
