<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'student_id_number', 'first_name', 'middle_name', 'last_name', 'course', 'required_hours'])]
class StudentProfile extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
