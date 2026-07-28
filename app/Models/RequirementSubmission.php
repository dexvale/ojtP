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
}
