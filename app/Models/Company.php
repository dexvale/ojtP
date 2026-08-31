<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'industry', 'location', 'contact_person', 'contact_number', 'allocation_slots', 'status', 'created_by_student_id'])]
class Company extends Model
{
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function studentProfiles()
    {
        return $this->hasMany(StudentProfile::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'company_course');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_student_id');
    }
}
