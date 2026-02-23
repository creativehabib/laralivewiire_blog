<?php

use App\Models\Admin\Tag;
use App\Models\Post;
use App\Models\Slug;
use Illuminate\Support\Facades\DB;

it('deletes tag slug and post pivot rows without deleting posts', function () {
    $post = Post::create([
        'name' => 'Post kept after tag deletion',
    ]);

    $tag = Tag::create([
        'name' => 'Temporary tag',
        'status' => 'published',
    ]);

    $tag->posts()->attach($post->id);

    expect(DB::table('post_tags')->where('tag_id', $tag->id)->where('post_id', $post->id)->exists())->toBeTrue();
    expect(Slug::query()->where('reference_type', Tag::class)->where('reference_id', $tag->id)->exists())->toBeTrue();

    $tag->delete();

    expect(Tag::query()->find($tag->id))->toBeNull();
    expect(DB::table('post_tags')->where('tag_id', $tag->id)->exists())->toBeFalse();
    expect(Slug::query()->where('reference_type', Tag::class)->where('reference_id', $tag->id)->exists())->toBeFalse();
    expect(Post::query()->find($post->id))->not->toBeNull();
});
