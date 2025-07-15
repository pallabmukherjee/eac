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
        Schema::table('gratuity_requests', function (Blueprint $table) {
            $table->string('prayer_no')->nullable()->after('amount');
            $table->date('prayer_date')->nullable()->after('prayer_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gratuity_requests', function (Blueprint $table) {
            $table->dropColumn('prayer_no');
            $table->dropColumn('prayer_date');
        });
    }
};
