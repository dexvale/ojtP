<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentEvaluation extends Model
{
    protected $fillable = [
        'student_id',
        'supervisor_id',
        'technical_score',
        'soft_skills_score',
        'attitude_score',
        'comments',
        'evaluated_at',
    ];

    protected $casts = [
        'evaluated_at' => 'datetime',
        'technical_score' => 'float',
        'soft_skills_score' => 'float',
        'attitude_score' => 'float',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(StudentProfile::class, 'student_id');
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    /**
     * Average of all three scores (out of 5).
     */
    public function getAverageScoreAttribute(): float
    {
        return round(($this->technical_score + $this->soft_skills_score + $this->attitude_score) / 3, 1);
    }

    /**
     * Overall rating as a percentage (out of 100).
     */
    public function getOverallPercentAttribute(): int
    {
        return (int) round(($this->average_score / 5) * 100);
    }
}
