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
        Schema::create('yearly_account_balence', function (Blueprint $table) {
            $table->id();
            $table->year('year');
            $table->string('ledgers_head_code');
            $table->string('major_head_code');
            $table->string('minor_head_code');
            $table->decimal('opening_debit', 15, 2);
            $table->decimal('opening_credit', 15, 2);
            $table->decimal('closing_debit', 15, 2);
            $table->decimal('closing_credit', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yearly_account_balence');
    }
};
