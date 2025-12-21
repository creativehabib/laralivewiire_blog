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
        if (! setting('comment_show_avatars', true)) {
            return '';
        }

        $userAvatar = $this->user?->avatar;

        if ($userAvatar) {
            return Storage::url($userAvatar);
        }

        $hash = md5(strtolower(trim($this->email)));

        $default = match (setting('comment_avatar_default', 'mystery')) {
            'blank'      => 'blank',
            'gravatar'   => 'https://www.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536',
            'identicon'  => 'identicon',
            'wavatar'    => 'wavatar',
            'monsterid'  => 'monsterid',
            'retro'      => 'retro',
            default      => 'mp',
        };

        $rating = setting('comment_avatar_rating', 'g');

        $shouldForceDefault = in_array($default, ['blank', 'identicon', 'wavatar', 'monsterid', 'retro'], true);

        $queryParams = [
            's' => 80,
            'd' => $default,
            'r' => $rating,
        ];

        if ($shouldForceDefault) {
            $queryParams['f'] = 'y';
        }

        $query = http_build_query($queryParams, '', '&', PHP_QUERY_RFC3986);

        return "https://www.gravatar.com/avatar/{$hash}?{$query}";
    }
}
