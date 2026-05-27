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
        // Columns ot_clock_in, ot_clock_out, ot_duration, and has_overtime 
        // already exist in the database.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No action required.
    }
};
