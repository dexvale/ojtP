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
            $table->index(['status', 'user_id'], 'ojt_logs_status_user_id_index');
            $table->index(['user_id', 'log_date'], 'ojt_logs_user_id_log_date_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ojt_logs', function (Blueprint $table) {
            $table->dropIndex('ojt_logs_status_user_id_index');
            $table->dropIndex('ojt_logs_user_id_log_date_index');
        });
    }
};
