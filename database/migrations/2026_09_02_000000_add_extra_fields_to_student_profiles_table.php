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
            $table->date('date_of_birth')->nullable()->after('course');
            $table->string('blood_type', 10)->nullable()->after('date_of_birth');
            $table->string('profile_photo_path')->nullable()->after('blood_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_profiles', function (Blueprint $table) {
            $table->dropColumn(['date_of_birth', 'blood_type', 'profile_photo_path']);
        });
    }
};
