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
        Schema::table('contra_vouchers', function (Blueprint $table) {
            $table->tinyInteger('edit_status')->default(0)->after('online_remarks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contra_vouchers', function (Blueprint $table) {
            $table->dropColumn('edit_status');
        });
    }
};
