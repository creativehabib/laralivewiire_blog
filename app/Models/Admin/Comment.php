<?php

namespace App\Models\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Comment extends Model
{
    protected $fillable = [
        'name',
        'email',
        'website',
        'content',
        'status',
        'user_id',
        'ip_address',
        'user_agent',
        'parent_id',
        'commentable_id',
        'commentable_type',
    ];

    public function commentable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function repliesRecursive()
    {
        return $this->replies()
            ->approved()
            ->oldest()
            ->with(['parent', 'repliesRecursive', 'user']);
    }

    public function getAvatarUrlAttribute(): string
    {
        $userAvatar = $this->user?->avatar;

        if ($userAvatar) {
            return Storage::url($userAvatar);
        }

        $hash = md5(strtolower(trim($this->email)));

        return "https://www.gravatar.com/avatar/{$hash}?s=80&d=identicon";
    }
}
