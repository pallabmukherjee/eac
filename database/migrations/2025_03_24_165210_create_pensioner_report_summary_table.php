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
        Schema::create('pensioner_report_summary', function (Blueprint $table) {
            $table->id();
            $table->string('report_id');
            $table->string('pensioner_id');
            $table->decimal('gross', 10, 2);
            $table->decimal('arrear', 10, 2)->nullable();
            $table->decimal('overdrawn', 10, 2)->nullable();
            $table->decimal('net_pension', 10, 2);
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pensioner_report_summary');
    }
};
