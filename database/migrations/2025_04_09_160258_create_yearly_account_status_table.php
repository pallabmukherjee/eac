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
        Schema::create('yearly_account_status', function (Blueprint $table) {
            $table->id();
            $table->year('year')->nullable();
            $table->timestamps();
        });

        $currentYear = now()->year;
        DB::table('yearly_account_status')->insert([
            'year' => $currentYear,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yearly_account_status');
    }
};
