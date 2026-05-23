<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['internship_id', 'date', 'morning_in', 'morning_out', 'afternoon_in', 'afternoon_out', 'activity_summary', 'photo_path', 'is_overtime', 'total_hours', 'status'])]
class ShiftLog extends Model
{
    protected function casts(): array
    {
        return [
            'date' => 'date',
            'is_overtime' => 'boolean',
            'total_hours' => 'float',
        ];
    }

    public function internship()
    {
        return $this->belongsTo(Internship::class);
    }
}
