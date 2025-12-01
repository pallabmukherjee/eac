<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix invalid dates before modifying table
        DB::statement("UPDATE pensioners SET created_at = NULL WHERE CAST(created_at AS CHAR) = '0000-00-00 00:00:00'");
        DB::statement("UPDATE pensioners SET updated_at = NULL WHERE CAST(updated_at AS CHAR) = '0000-00-00 00:00:00'");

        Schema::table('pensioners', function (Blueprint $table) {
            $table->string('pension_type')->nullable()->change();
            $table->string('family_name')->nullable()->change();
            $table->string('alive_status')->nullable()->change();
            $table->string('employee_code')->nullable()->change();
            $table->string('ppo_number')->nullable()->change();
            $table->bigInteger('aadhar_number')->nullable()->change();
            $table->bigInteger('savings_account_number')->nullable()->change();
            $table->string('ifsc_code')->nullable()->change();
            $table->integer('dr_percentage')->nullable()->change();
            $table->decimal('basic_pension', 10, 2)->nullable()->change();
            $table->decimal('medical_allowance', 10, 2)->nullable()->change();
            $table->decimal('other_allowance', 10, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pensioners', function (Blueprint $table) {
            $table->string('pension_type')->nullable(false)->change();
            $table->string('family_name')->nullable(false)->change();
            $table->string('alive_status')->nullable(false)->change();
            $table->string('employee_code')->nullable(false)->change();
            $table->string('ppo_number')->nullable(false)->change();
            $table->bigInteger('aadhar_number')->nullable(false)->change();
            $table->bigInteger('savings_account_number')->nullable(false)->change();
            $table->string('ifsc_code')->nullable(false)->change();
            $table->integer('dr_percentage')->nullable(false)->change();
            $table->decimal('basic_pension', 10, 2)->nullable(false)->change();
            $table->decimal('medical_allowance', 10, 2)->nullable(false)->change();
            $table->decimal('other_allowance', 10, 2)->nullable(false)->change();
        });
    }
};
