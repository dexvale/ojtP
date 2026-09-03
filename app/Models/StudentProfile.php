<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'user_id', 'academic_term_id', 'student_id_number', 'first_name', 'middle_name', 'last_name',
    'course', 'date_of_birth', 'blood_type', 'profile_photo_path', 'required_hours', 'supervisor_id', 'company_id', 'department',
    'placement_status', 'ojt_status', 'placement_remarks', 'acceptance_letter_path',
    'pending_company_name', 'pending_supervisor_name', 'pending_supervisor_email', 'pending_supervisor_contact',
    'contact_address', 'contact_number', 'father_name', 'mother_name',
    'emergency_contact_person', 'emergency_contact_number', 'internship_start'
])]
class StudentProfile extends Model
{
    public function academicTerm()
    {
        return $this->belongsTo(AcademicTerm::class, 'academic_term_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function assignedSupervisor()
    {
        return User::where('company_id', $this->company_id)->whereHas('roles', function($q) {
            $q->where('name', 'Advisor');
        });
    }

    public function ojtLogs()
    {
        return $this->hasMany(OjtLog::class, 'user_id', 'user_id');
    }

    public function academicCourse()
    {
        return $this->belongsTo(Course::class, 'course', 'course_name');
    }

    public function evaluations()
    {
        return $this->hasMany(StudentEvaluation::class, 'student_id');
    }

    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->profile_photo_path)) {
            return asset('storage/' . $this->profile_photo_path);
        }

        $name = urlencode(trim($this->first_name . ' ' . $this->last_name) ?: 'Student');
        return "https://ui-avatars.com/api/?name={$name}&background=3a0ca3&color=fff&bold=true";
    }
}
