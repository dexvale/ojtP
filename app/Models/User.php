<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['email', 'password', 'role', 'company_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    public function company()
    {
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

    public function getNameAttribute()
    {
        if ($this->role === 'Student' && $this->studentProfile) {
            return trim($this->studentProfile->first_name . ' ' . ($this->studentProfile->middle_name ? $this->studentProfile->middle_name . ' ' : '') . $this->studentProfile->last_name);
        }
        return $this->email;
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
}
