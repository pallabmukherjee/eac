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
        Schema::create('leave_increment', function (Blueprint $table) {
            $table->id();
            $table->string('month');
            $table->enum('type', ['CL', 'EL', 'ML']);
            $table->timestamps();
        });

        DB::table('leave_increment')->insert([
            ['month' => 'January-2025', 'type' => 'CL'],
            ['month' => 'January-2025', 'type' => 'EL'],
            ['month' => 'January-2025', 'type' => 'ML'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_increment');
    }
};
