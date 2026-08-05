<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('student_profiles')->onDelete('cascade');
            $table->foreignId('supervisor_id')->constrained('users')->onDelete('cascade');
            $table->decimal('technical_score', 3, 1)->default(0);
            $table->decimal('soft_skills_score', 3, 1)->default(0);
            $table->decimal('attitude_score', 3, 1)->default(0);
            $table->text('comments')->nullable();
            $table->timestamp('evaluated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_evaluations');
    }
};
