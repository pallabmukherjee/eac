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
        Schema::create('pensioners', function (Blueprint $table) {
            $table->id();
            $table->string('pensioner_name');
            $table->enum('pension_type', [1, 2]); // Type of Pension (Self or Family member)
            $table->string('family_name');
            $table->date('dob');
            $table->date('retirement_date');
            $table->enum('alive_status', [1, 2]);
            $table->date('death_date')->nullable();
            $table->boolean('life_certificate')->default(false);
            $table->date('five_year_date'); // Date after 5 years
            $table->boolean('five_year_completed')->default(false); // 5-year completed status
            $table->string('employee_code');
            $table->string('ppo_number');
            $table->integer('ropa_year');
            $table->bigInteger('aadhar_number');
            $table->bigInteger('savings_account_number');
            $table->string('ifsc_code');
            $table->integer('dr_percentage');
            $table->decimal('basic_pension', 10, 2);
            $table->decimal('medical_allowance', 10, 2);
            $table->decimal('other_allowance', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pensioners');
    }
};
