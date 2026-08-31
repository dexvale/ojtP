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
        Schema::table('companies', function (Blueprint $table) {
            $table->string('status')->default('approved')->after('allocation_slots');
            $table->foreignId('created_by_student_id')->nullable()->after('status')->constrained('users')->nullOnDelete();
        });

        Schema::table('student_profiles', function (Blueprint $table) {
            $table->string('department')->nullable()->after('company_id');
            $table->string('placement_status')->default('Unassigned')->after('department'); // Unassigned, Pending, Approved, Rejected
            $table->text('placement_remarks')->nullable()->after('placement_status');
            $table->string('acceptance_letter_path')->nullable()->after('placement_remarks');
            $table->string('pending_company_name')->nullable()->after('acceptance_letter_path');
            $table->string('pending_supervisor_name')->nullable()->after('pending_company_name');
            $table->string('pending_supervisor_email')->nullable()->after('pending_supervisor_name');
            $table->string('pending_supervisor_contact')->nullable()->after('pending_supervisor_email');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('department')->nullable()->after('company_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('department');
        });

        Schema::table('student_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'department',
                'placement_status',
                'placement_remarks',
                'acceptance_letter_path',
                'pending_company_name',
                'pending_supervisor_name',
                'pending_supervisor_email',
                'pending_supervisor_contact',
            ]);
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->dropForeign(['created_by_student_id']);
            $table->dropColumn(['status', 'created_by_student_id']);
        });
    }
};
