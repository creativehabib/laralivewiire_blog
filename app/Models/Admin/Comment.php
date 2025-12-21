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
        // ১. সেটিংস চেক: যদি অবতার দেখানো বন্ধ থাকে
        if (! setting('comment_show_avatars', true)) {
            return '';
        }

        // ২. লোকাল অবতার চেক: ইউজারের নিজস্ব ছবি আছে কিনা
        $userAvatar = $this->user?->avatar;
        if ($userAvatar && Storage::disk('public')->exists($userAvatar)) {
            return Storage::url($userAvatar);
        }

        // ৩. ইমেইল থেকে হ্যাশ জেনারেট
        $hash = md5(strtolower(trim($this->email)));

        // ৪. ডিফল্ট ইমেজ নির্ধারণ
        // এখানে লজিক হলো: যদি সেটিংস থেকে নির্দিষ্ট কোনো স্টাইল (যেমন monsterid) আসে তো ভালো,
        // আর যদি 'gravatar' বা অন্য কিছু আসে যা লোগো দেখায়, তবে আমরা জোর করে 'identicon' দিব।

        $settingDefault = setting('comment_avatar_default', 'identicon');

        $default = match ($settingDefault) {
            'wavatar'    => 'wavatar',   // কার্টুন মুখ
            'monsterid'  => 'monsterid', // মনস্টার
            'retro'      => 'retro',     // পিক্সেল আর্ট
            'robohash'   => 'robohash',  // রোবট
            'mp'         => 'mp',        // সাধারণ মানুষ
            'blank'      => 'blank',     // খালি
            default      => 'identicon', // **FIX:** অন্য সব ক্ষেত্রে (এমনকি Gravatar লোগো এড়াতে) Identicon দেখাবে
        };

        $rating = setting('comment_avatar_rating', 'g');

        // ৫. কুয়েরি প্যারামিটার
        $queryParams = [
            's' => 80,       // Size
            'd' => $default, // এখানে 'identicon' সেট হবে যদি ছবি না থাকে
            'r' => $rating,  // Rating
        ];

        // নোট: এখানে $queryParams['f'] = 'y' দেওয়া যাবে না।
        // দিলেই আসল ছবি থাকলেও দেখাবে না, শুধু ডাইনামিক দেখাবে।
        // এটা না দিলে: আসল ছবি থাকলে দেখাবে, না থাকলে 'd' এর ভ্যালু (identicon) দেখাবে।

        $query = http_build_query($queryParams);

        return "https://www.gravatar.com/avatar/{$hash}?{$query}";
    }

}
