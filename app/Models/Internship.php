<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['student_id', 'advisor_id', 'department', 'required_hours', 'status'])]
class Internship extends Model
{
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function advisor()
    {
        return $this->belongsTo(User::class, 'advisor_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function shiftLogs()
    {
        return $this->hasMany(ShiftLog::class);
    }
}
