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
        // Safely alter the column type to TEXT to support 65,000+ characters
        DB::statement('ALTER TABLE ojt_logs MODIFY remarks TEXT');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to VARCHAR(500) if rolled back
        DB::statement('ALTER TABLE ojt_logs MODIFY remarks VARCHAR(500)');
    }
};
