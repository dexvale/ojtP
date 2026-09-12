<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['course_name', 'required_hours'])]
class Course extends Model
{
    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_course');
    }

    public function requirements()
    {
        return $this->belongsToMany(Requirement::class, 'course_requirement');
    }

    public function getNameAttribute(): string
    {
        return $this->course_name ?? '';
    }

    public function getCodeAttribute(): string
    {
        return preg_replace('/^(Bachelor of Science in|BS in)\s*/i', '', $this->course_name ?? '');
    }
}
