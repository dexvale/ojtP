<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['internship_id', 'type', 'file_path', 'status', 'uploaded_at'])]
class Document extends Model
{
    protected function casts(): array
    {
        return [
            'uploaded_at' => 'datetime',
        ];
    }

    public function internship()
    {
        return $this->belongsTo(Internship::class);
    }
}
