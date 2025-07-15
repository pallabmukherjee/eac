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
        Schema::create('leave_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('emp_id');
            $table->string('emp_name');
            $table->string('emp_designation');
            $table->string('month');
            $table->integer('cl')->default(0);
            $table->integer('cl_enjoyed')->default(0);
            $table->integer('cl_in_hand')->default(0);
            $table->string('cl_date')->nullable();
            $table->integer('el')->default(0);
            $table->integer('el_enjoyed')->default(0);
            $table->integer('el_in_hand')->default(0);
            $table->string('el_date')->nullable();
            $table->integer('ml')->default(0);
            $table->integer('ml_enjoyed')->default(0);
            $table->integer('ml_in_hand')->default(0);
            $table->string('ml_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_reports');
    }
};
