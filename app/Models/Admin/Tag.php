<?php

namespace App\Models\Admin;

use App\Models\Post;
use App\Models\Concerns\HasMetaBoxes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory, HasMetaBoxes;
    protected $fillable = [
        'name',
        'description',
        'slug',
        'status',
        'author_id',
        'author_type',
    ];

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_tags');
    }
}
