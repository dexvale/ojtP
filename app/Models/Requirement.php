<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Requirement extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'template_path'];

    public function submissions()
    {
        return $this->hasMany(RequirementSubmission::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_requirement');
    }
}
