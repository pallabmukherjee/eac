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
        Schema::create('jv_ledger_head', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_id');
            $table->string('ledger_head');
            $table->decimal('amount', 15, 2);
            $table->string('crdr');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jv_ledger_head');
    }
};
