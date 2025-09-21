<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\UsesUuid;

class Note extends Model
{
    use UsesUuid;

    protected $fillable = [
        'id',
        'user_id',
        'title',
        'content',
        'folder_id',
        'accent_argb',
        'rich_json'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
