<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'company',
        'location',
        'bio',
        'avatar_path',
        'avatar_base64',
        'notifications_enabled',
        'ch_task_reminders',
        'ch_notes_updates',
        'ch_product_news',
        'dnd_enabled',
        'dnd_start_hour',
        'dnd_end_hour',
        // new auth/approval fields
        'role',          // 'user'|'admin'|'superadmin'
        'approved_at',   // nullable datetime
        'approved_by',   // nullable user id
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'notifications_enabled' => 'boolean',
        'ch_task_reminders'     => 'boolean',
        'ch_notes_updates'      => 'boolean',
        'ch_product_news'       => 'boolean',
        'dnd_enabled'           => 'boolean',
        'approved_at'           => 'datetime',
    ];

    // Auto-hash when assigning password via fillable/setter
    public function setPasswordAttribute($value)
    {
        if (!empty($value) && !str_starts_with((string)$value, '$2y$')) {
            $this->attributes['password'] = Hash::make($value);
        } else {
            $this->attributes['password'] = $value;
        }
    }

    // Scope for pending users
    public function scopePending($q)
    {
        return $q->whereNull('approved_at');
    }

    // Relations you already had
    public function folders()
    {
        return $this->hasMany(Folder::class);
    }
    public function notes()
    {
        return $this->hasMany(Note::class);
    }
    public function tags()
    {
        return $this->hasMany(Tag::class);
    }
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
