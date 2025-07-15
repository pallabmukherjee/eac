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
        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('user_id');
            $table->boolean('payment')->default(false); // Payment role (true or false)
            $table->boolean('receipt')->default(false); // Receipt role (true or false)
            $table->boolean('contra')->default(false); // Contra role (true or false)
            $table->boolean('journal')->default(false); // Journal role (true or false)
            $table->boolean('leave')->default(false); // Leave role (true or false)
            $table->boolean('pension')->default(false); // Pension role (true or false)
            $table->boolean('gratuity')->default(false); // Gratuity role (true or false)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_roles');
    }
};
