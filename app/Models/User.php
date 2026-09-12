<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'company_id', 'department', 'contact_number', 'profile_photo_path'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    public function company()
    {
        if ($this->role === 'Advisor') {
            return $this->belongsTo(Company::class, 'company_id');
        }

        return $this->hasOneThrough(
            Company::class,
            StudentProfile::class,
            'user_id',    // Foreign key on student_profiles table...
            'id',         // Foreign key on companies table...
            'id',         // Local key on users table...
            'company_id'  // Local key on student_profiles table...
        );
    }
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function studentProfile()
    {
        return $this->hasOne(StudentProfile::class);
    }

    public function getDisplayNameAttribute()
    {
        if (!empty($this->attributes['name'])) {
            return $this->attributes['name'];
        }

        if ($this->role === 'Student' && $this->studentProfile) {
            return trim($this->studentProfile->first_name . ' ' . ($this->studentProfile->middle_name ? $this->studentProfile->middle_name . ' ' : '') . $this->studentProfile->last_name);
        }

        $emailPrefix = explode('@', $this->email)[0];
        $formatted = ucwords(str_replace(['.', '_', '-'], ' ', $emailPrefix));

        return $formatted ?: $this->email;
    }

    public function getDisplayRoleAttribute()
    {
        if ($this->role === 'Coordinator') {
            return 'OJT Coordinator';
        }
        if ($this->role === 'Advisor') {
            return 'Company Supervisor';
        }
        if ($this->role === 'Student') {
            return 'OJT Intern';
        }
        return $this->role ?? 'User';
    }

    public function getAvatarUrlAttribute()
    {
        if ($this->profile_photo_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->profile_photo_path)) {
            return asset('storage/' . $this->profile_photo_path);
        }

        if ($this->role === 'Student' && $this->studentProfile?->profile_photo_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->studentProfile->profile_photo_path)) {
            return asset('storage/' . $this->studentProfile->profile_photo_path);
        }

        $name = urlencode($this->display_name ?: 'User');
        return "https://ui-avatars.com/api/?name={$name}&background=3a0ca3&color=fff&bold=true";
    }

    public function internships()
    {
        return $this->hasMany(Internship::class, 'student_id');
    }

    public function advisedInternships()
    {
        return $this->hasMany(Internship::class, 'advisor_id');
    }

    public function ojtLogs()
    {
        return $this->hasMany(OjtLog::class);
    }

    public function managedCourses()
    {
        return $this->belongsToMany(Course::class, 'coordinator_course');
    }

    public function requirementSubmissions()
    {
        return $this->hasMany(RequirementSubmission::class);
    }

    public function hasRole(string|array $roles): bool
    {
        if (is_array($roles)) {
            return in_array($this->role, $roles);
        }
        return strcasecmp($this->role ?? '', $roles) === 0;
    }
}
