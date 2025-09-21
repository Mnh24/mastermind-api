<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\UsesUuid;

class Task extends Model
{
    use UsesUuid;

    protected $fillable = [
        'id',
        'user_id',
        'title',
        'description',
        'time',
        'end_time',
        'progress',
        'status',
        'gradient_start',
        'gradient_end',
        'due_date'
    ];

    protected $casts = [
        'progress' => 'float',
        'due_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
