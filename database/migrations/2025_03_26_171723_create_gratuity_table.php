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
        Schema::create('gratuity', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('relation_name');
            $table->string('employee_code');
            $table->string('ppo_number');
            $table->date('ppo_receive_date');
            $table->enum('retire_dead', [1, 2]); // 1 = Retire, 2 = Dead
            $table->date('retirement_date');
            $table->string('financial_year');
            $table->enum('alive_status', [1, 2]); // 1 = Alive, 2 = Dead
            $table->enum('loan_status', [1, 2]); // 1 = Yes, 2 = No
            $table->enum('relation_died', [1, 2]); // 1 = Yes, 2 = No
            $table->string('warrant_name')->nullable();
            $table->string('warrant_adhar_no')->nullable();
            $table->decimal('ppo_amount', 10, 2);
            $table->decimal('sanctioned_amount', 10, 2);
            $table->string('ropa_year');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gratuity');
    }
};
