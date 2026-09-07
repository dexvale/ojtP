<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentEvaluation extends Model
{
    protected $fillable = [
        'student_id',
        'supervisor_id',
        'criteria_scores',
        'job_performance_score',
        'workmanship_score',
        'work_habits_score',
        'attendance_score',
        'final_rating',
        'transmuted_grade',
        'technical_score',
        'soft_skills_score',
        'attitude_score',
        'comments',
        'rated_by_name',
        'rated_by_designation',
        'approved_by_id',
        'approved_by_name',
        'approved_by_designation',
        'evaluated_at',
        'approved_at',
    ];

    protected $casts = [
        'criteria_scores'        => 'array',
        'job_performance_score'  => 'float',
        'workmanship_score'      => 'float',
        'work_habits_score'      => 'float',
        'attendance_score'       => 'float',
        'final_rating'           => 'float',
        'transmuted_grade'       => 'float',
        'technical_score'        => 'float',
        'soft_skills_score'      => 'float',
        'attitude_score'         => 'float',
        'evaluated_at'           => 'datetime',
        'approved_at'            => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(StudentProfile::class, 'student_id');
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_id');
    }

    /**
     * Official 12 Performance Appraisal Rubric items (BISU Balilihan Campus).
     */
    public static function rubricCriteria(): array
    {
        return [
            'technical_knowledge' => [
                'category'    => 'job_performance',
                'title'       => 'Technical Knowledge',
                'description' => 'Technical Knowledge of current job',
            ],
            'quality_of_work' => [
                'category'    => 'workmanship',
                'title'       => 'Quality of Work',
                'description' => 'Ability for individual productivity with swiftness in performance of task',
            ],
            'initiative_dependability' => [
                'category'    => 'workmanship',
                'title'       => 'Initiative and Dependability',
                'description' => 'Understand and follow instruction with less supervision. Able to perform and complete assigned work given. Show initiative and interest to other task.',
            ],
            'cooperation' => [
                'category'    => 'work_habits',
                'title'       => 'Cooperation',
                'description' => 'Ability to work in harmony with others.',
            ],
            'personality' => [
                'category'    => 'work_habits',
                'title'       => 'Personality',
                'description' => 'Able to adjust with varied and diverse personality. Ability to be tactful at most times, observe and extend courtesies to all',
            ],
            'time_management' => [
                'category'    => 'job_performance',
                'title'       => 'Time management ability',
                'description' => 'Able to perform and complete assigned work in given time and Working well under pressure',
            ],
            'attitude_maturity' => [
                'category'    => 'work_habits',
                'title'       => 'Attitude/Mental Maturity',
                'description' => 'Able to absorb comments and suggestions positively',
            ],
            'problem_solving' => [
                'category'    => 'job_performance',
                'title'       => 'Problem-solving and analytical skills',
                'description' => 'Ability to find solutions to problems encountered.',
            ],
            'safety_consciousness' => [
                'category'    => 'workmanship',
                'title'       => 'Safety Consciousness',
                'description' => 'Awareness of Safety practice',
            ],
            'communication_skills' => [
                'category'    => 'work_habits',
                'title'       => 'Good communication skills',
                'description' => 'The ability to convey information to another effectively and efficiently',
            ],
            'positive_attitude' => [
                'category'    => 'work_habits',
                'title'       => 'Positive Attitude/Mental',
                'description' => 'Ability to accept and learn from criticism and can easily overcome failures and discouragements.',
            ],
            'self_confidence' => [
                'category'    => 'work_habits',
                'title'       => 'Self Confidence',
                'description' => 'Ability to have a positive yet realistic views of themselves and their situations',
            ],
        ];
    }

    /**
     * Compute weighted category scores and final transmuted grade according to BISU Grading System:
     * - Job Performance: 50%
     * - Workmanship: 20%
     * - Work Habits & Attitudes: 20%
     * - Attendance: 10%
     */
    public static function calculateBisuRatings(array $criteriaScores, float $attendanceRating = 5.0): array
    {
        // 1. Job Performance (50% max): Technical Knowledge, Time Management, Problem Solving
        $jpItems = [
            floatval($criteriaScores['technical_knowledge'] ?? 5),
            floatval($criteriaScores['time_management'] ?? 5),
            floatval($criteriaScores['problem_solving'] ?? 5),
        ];
        $jpAvg = array_sum($jpItems) / count($jpItems);
        $jobPerformanceScore = round(($jpAvg / 5) * 50, 2);

        // 2. Workmanship (20% max): Quality of Work, Initiative and Dependability, Safety Consciousness
        $wmItems = [
            floatval($criteriaScores['quality_of_work'] ?? 5),
            floatval($criteriaScores['initiative_dependability'] ?? 5),
            floatval($criteriaScores['safety_consciousness'] ?? 5),
        ];
        $wmAvg = array_sum($wmItems) / count($wmItems);
        $workmanshipScore = round(($wmAvg / 5) * 20, 2);

        // 3. Work Habits & Attitudes (20% max): Cooperation, Personality, Attitude, Communication, Positive Attitude, Self Confidence
        $whItems = [
            floatval($criteriaScores['cooperation'] ?? 5),
            floatval($criteriaScores['personality'] ?? 5),
            floatval($criteriaScores['attitude_maturity'] ?? 5),
            floatval($criteriaScores['communication_skills'] ?? 5),
            floatval($criteriaScores['positive_attitude'] ?? 5),
            floatval($criteriaScores['self_confidence'] ?? 5),
        ];
        $whAvg = array_sum($whItems) / count($whItems);
        $workHabitsScore = round(($whAvg / 5) * 20, 2);

        // 4. Attendance (10% max)
        $attClamped = max(1, min(5, $attendanceRating));
        $attendanceScore = round(($attClamped / 5) * 10, 2);

        // Final Rating (0 - 100%)
        $finalRating = round($jobPerformanceScore + $workmanshipScore + $workHabitsScore + $attendanceScore, 2);
        $finalRating = min(100.0, max(0.0, $finalRating));

        // Transmuted Grade
        $transmutedGrade = self::transmuteBisuGrade($finalRating);

        return [
            'job_performance_score' => $jobPerformanceScore,
            'workmanship_score'     => $workmanshipScore,
            'work_habits_score'     => $workHabitsScore,
            'attendance_score'      => $attendanceScore,
            'final_rating'          => $finalRating,
            'transmuted_grade'      => $transmutedGrade,
            // Keep legacy 5-point averages synchronized
            'technical_score'       => round($jpAvg, 1),
            'soft_skills_score'     => round(($wmAvg + $whAvg) / 2, 1),
            'attitude_score'        => round($whAvg, 1),
        ];
    }

    /**
     * Transmute percentage rating to official BISU academic grade scale:
     * 95 - up  = 1.0
     * 94 - 90  = 1.1 - 1.5
     * 89 - 85  = 1.6 - 2.0
     * 84 - 80  = 2.1 - 2.5
     * 79 - 75  = 2.6 - 3.0
     * 74 - below = Failure (5.0)
     */
    public static function transmuteBisuGrade(float $rating): float
    {
        if ($rating >= 95.0) {
            return 1.0;
        }

        if ($rating >= 90.0) {
            // 94 -> 1.1, 93 -> 1.2, 92 -> 1.3, 91 -> 1.4, 90 -> 1.5
            $grade = 1.5 - (($rating - 90.0) / 4.0) * 0.4;
            return round(max(1.1, min(1.5, $grade)), 1);
        }

        if ($rating >= 85.0) {
            // 89 -> 1.6, 88 -> 1.7, 87 -> 1.8, 86 -> 1.9, 85 -> 2.0
            $grade = 2.0 - (($rating - 85.0) / 4.0) * 0.4;
            return round(max(1.6, min(2.0, $grade)), 1);
        }

        if ($rating >= 80.0) {
            // 84 -> 2.1, 83 -> 2.2, 82 -> 2.3, 81 -> 2.4, 80 -> 2.5
            $grade = 2.5 - (($rating - 80.0) / 4.0) * 0.4;
            return round(max(2.1, min(2.5, $grade)), 1);
        }

        if ($rating >= 75.0) {
            // 79 -> 2.6, 78 -> 2.7, 77 -> 2.8, 76 -> 2.9, 75 -> 3.0
            $grade = 3.0 - (($rating - 75.0) / 4.0) * 0.4;
            return round(max(2.6, min(3.0, $grade)), 1);
        }

        // 74 and below = Failure
        return 5.0;
    }

    /**
     * Descriptive label for the BISU grade.
     */
    public function getGradeDescriptionAttribute(): string
    {
        $grade = $this->transmuted_grade;
        if ($grade === null) {
            return 'Pending';
        }

        if ($grade <= 1.0) {
            return 'Excellent';
        }
        if ($grade <= 1.5) {
            return 'Very Good';
        }
        if ($grade <= 2.0) {
            return 'Good';
        }
        if ($grade <= 2.5) {
            return 'Fair';
        }
        if ($grade <= 3.0) {
            return 'Passed';
        }

        return 'Failure';
    }

    /**
     * Average score out of 5.0.
     */
    public function getAverageScoreAttribute(): float
    {
        if ($this->final_rating > 0) {
            return round(($this->final_rating / 100) * 5, 1);
        }

        return round(($this->technical_score + $this->soft_skills_score + $this->attitude_score) / 3, 1);
    }

    /**
     * Overall percentage rating.
     */
    public function getOverallPercentAttribute(): int
    {
        if ($this->final_rating > 0) {
            return (int) round($this->final_rating);
        }

        return (int) round(($this->average_score / 5) * 100);
    }
}
