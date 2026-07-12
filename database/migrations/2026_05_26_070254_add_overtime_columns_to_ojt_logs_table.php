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
        Schema::table('ojt_logs', function (Blueprint $table) {
            $table->time('ot_clock_in')->nullable()->after('afternoon_out');
            $table->time('ot_clock_out')->nullable()->after('ot_clock_in');
            $table->decimal('ot_duration', 5, 2)->default(0.00)->after('ot_clock_out');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ojt_logs', function (Blueprint $table) {
            $table->dropColumn(['ot_clock_in', 'ot_clock_out', 'ot_duration']);
        });
    }
};
