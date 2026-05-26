<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'student_id_number', 'first_name', 'middle_name', 'last_name', 'course', 'required_hours', 'supervisor_id', 'company_id', 'contact_address', 'contact_number', 'father_name', 'mother_name', 'emergency_contact_person', 'emergency_contact_number', 'internship_start'])]
class StudentProfile extends Model
{
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
}
