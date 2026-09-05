<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_evaluations', function (Blueprint $table) {
            // Detailed 12 Performance Appraisal criteria & attendance rating (stored as JSON)
            $table->json('criteria_scores')->nullable()->after('supervisor_id');

            // Category Weighted Scores (Percentage based)
            $table->decimal('job_performance_score', 5, 2)->default(0)->after('criteria_scores'); // Max 50.00
            $table->decimal('workmanship_score', 5, 2)->default(0)->after('job_performance_score'); // Max 20.00
            $table->decimal('work_habits_score', 5, 2)->default(0)->after('workmanship_score');   // Max 20.00
            $table->decimal('attendance_score', 5, 2)->default(0)->after('work_habits_score');     // Max 10.00
            
            // Final Rating (0 - 100%) and Transmuted BISU Academic Grade (1.0 to 5.0)
            $table->decimal('final_rating', 5, 2)->default(0)->after('attendance_score');
            $table->decimal('transmuted_grade', 3, 2)->nullable()->after('final_rating');

            // Signatory: Rated By (Supervisor / Agency Representative)
            $table->string('rated_by_name')->nullable()->after('comments');
            $table->string('rated_by_designation')->nullable()->after('rated_by_name');

            // Signatory: Approved By (OJT Coordinator / Dean)
            $table->foreignId('approved_by_id')->nullable()->constrained('users')->nullOnDelete()->after('rated_by_designation');
            $table->string('approved_by_name')->nullable()->after('approved_by_id');
            $table->string('approved_by_designation')->nullable()->after('approved_by_name');
            $table->timestamp('approved_at')->nullable()->after('approved_by_designation');
        });
    }

    public function down(): void
    {
        Schema::table('student_evaluations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('approved_by_id');
            $table->dropColumn([
                'criteria_scores',
                'job_performance_score',
                'workmanship_score',
                'work_habits_score',
                'attendance_score',
                'final_rating',
                'transmuted_grade',
                'rated_by_name',
                'rated_by_designation',
                'approved_by_name',
                'approved_by_designation',
                'approved_at',
            ]);
        });
    }
};
