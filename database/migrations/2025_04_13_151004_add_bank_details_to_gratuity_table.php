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
        Schema::table('gratuity', function (Blueprint $table) {
            $table->string('bank_ac_no')->nullable()->after('ropa_year');
            $table->string('ifsc')->nullable()->after('bank_ac_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gratuity', function (Blueprint $table) {
            $table->dropColumn(['bank_ac_no', 'ifsc']);
        });
    }
};
