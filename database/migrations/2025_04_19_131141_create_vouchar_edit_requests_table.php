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
        Schema::create('vouchar_edit_requests', function (Blueprint $table) {
            $table->id();
            $table->string('vouchar_id');
            $table->string('bill_no');
            $table->tinyInteger('user_id');
            $table->tinyInteger('edit_status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchar_edit_requests');
    }
};
