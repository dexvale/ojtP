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
}
