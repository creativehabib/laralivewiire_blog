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

        // ২. লোকাল অবতার চেক: ইউজারের নিজস্ব ছবি আছে কিনা এবং ফাইলটি স্টোরেজে আছে কিনা
        $userAvatar = $this->user?->avatar;

        if ($userAvatar && Storage::disk('public')->exists($userAvatar)) {
            return Storage::url($userAvatar);
        }

        // ৩. ইমেইল থেকে হ্যাশ জেনারেট
        $hash = md5(strtolower(trim($this->email)));

        // ৪. ডিফল্ট ইমেজ স্টাইল নির্ধারণ
        // এখানে আপনার আগের কোডের সমস্যা ছিল। আপনি 'gravatar' কি-তে একটি লিংক দিচ্ছিলেন যা প্যারামিটারে কাজ করবে না।
        // আমরা ডিফল্ট হিসেবে 'identicon' সেট করছি, যা আপনার চাওয়া অনুযায়ী ডাইনামিক লোগো দেখাবে।

        $settingDefault = setting('comment_avatar_default', 'identicon');

        $default = match ($settingDefault) {
            'blank'      => 'blank',
            'wavatar'    => 'wavatar',   // কার্টুন মুখ
            'monsterid'  => 'monsterid', // মনস্টার
            'retro'      => 'retro',     // পিক্সেল আর্ট
            'robohash'   => 'robohash',  // রোবট
            'mp'         => 'mp',        // সাধারণ মানুষের আইকন
            default      => 'identicon', // **FIX:** অন্য সব ক্ষেত্রে জ্যামিতিক নকশা (Identicon) দেখাবে
        };

        $rating = setting('comment_avatar_rating', 'g');

        // ৫. কুয়েরি প্যারামিটার
        $queryParams = [
            's' => 80,       // Size
            'd' => $default, // এখানে 'identicon' বা সিলেক্ট করা স্টাইল বসবে
            'r' => $rating,  // Rating
        ];

        // বি:দ্র: আমি `f` (Force Default) লজিকটি বাদ দিয়েছি।
        // কারণ এটি থাকলে যার আসল Gravatar একাউন্ট আছে, তার ছবিও আসবে না, জোর করে identicon দেখাবে।
        // এখন নিয়ম হলো: আসল ছবি থাকলে দেখাবে, না থাকলে identicon দেখাবে।

        $query = http_build_query($queryParams);

        return "https://www.gravatar.com/avatar/{$hash}?{$query}";
    }

}
