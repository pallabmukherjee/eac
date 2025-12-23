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
        Schema::table('gratuity_bill_summary', function (Blueprint $table) {
            if (!Schema::hasColumn('gratuity_bill_summary', 'id_no')) {
                $table->string('id_no')->nullable();
            }
            if (!Schema::hasColumn('gratuity_bill_summary', 'reference_no')) {
                $table->string('reference_no')->nullable();
            }
            if (!Schema::hasColumn('gratuity_bill_summary', 'reference_date')) {
                $table->date('reference_date')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gratuity_bill_summary', function (Blueprint $table) {
            $table->dropColumn(['id_no', 'reference_no', 'reference_date']);
        });
    }
};
