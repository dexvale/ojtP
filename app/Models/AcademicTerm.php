<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['academic_year', 'semester', 'is_active'])]
class AcademicTerm extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the currently active Academic Term.
     */
    public static function current(): ?self
    {
        return static::where('is_active', true)->first();
    }

    /**
     * Get full display title, e.g. "A.Y. 2026-2027 (1st Semester)".
     */
    public function getFullTitleAttribute(): string
    {
        return "A.Y. {$this->academic_year} ({$this->semester})";
    }

    public function studentProfiles()
    {
        return $this->hasMany(StudentProfile::class, 'academic_term_id');
    }
}
