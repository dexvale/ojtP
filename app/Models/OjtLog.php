<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OjtLog extends Model
{
    protected $fillable = [
        'user_id',
        'log_date',
        'morning_in',
        'morning_out',
        'afternoon_in',
        'afternoon_out',
        'tasks_performed',
        'hours_rendered',
        'status',
        'has_overtime',
        'photo_path'
    ];

    protected function casts(): array
    {
        return [
            'log_date' => 'date',
            'hours_rendered' => 'float',
            'has_overtime' => 'boolean'
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
