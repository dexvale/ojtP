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
        Schema::table('student_profiles', function (Blueprint $table) {
            $table->foreignId('academic_term_id')->nullable()->after('user_id')->constrained('academic_terms')->nullOnDelete();
            $table->enum('ojt_status', ['Active', 'Completed', 'Archived'])->default('Active')->after('placement_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_profiles', function (Blueprint $table) {
            $table->dropForeign(['academic_term_id']);
            $table->dropColumn(['academic_term_id', 'ojt_status']);
        });
    }
};
