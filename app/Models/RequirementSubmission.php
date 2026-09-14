<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RequirementSubmission extends Model
{
    use HasFactory;

    protected $fillable = ['requirement_id', 'user_id', 'file_path', 'status', 'remarks'];

    public function requirement()
    {
        return $this->belongsTo(Requirement::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDisplayFileNameAttribute()
    {
        if (!$this->file_path) {
            return 'document';
        }

        $extension = strtolower(pathinfo($this->file_path, PATHINFO_EXTENSION));
        $student = $this->user?->studentProfile;
        $studentName = $student ? ($student->last_name . '_' . $student->first_name) : 'Student';
        $reqTitle = $this->requirement?->title ?: 'Requirement';

        return \Illuminate\Support\Str::slug($studentName . '_' . $reqTitle) . '.' . $extension;
    }

    public function getFileExtensionAttribute()
    {
        return strtolower(pathinfo($this->file_path, PATHINFO_EXTENSION));
    }
}
