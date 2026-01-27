<?php

namespace Database\Seeders;

use App\Models\Admin\Tag;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class DefaultContentSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::firstOrCreate(
            ['name' => 'Uncategorized'],
            [
                'description' => 'Default category created during installation.',
                'status' => 'published',
                'is_default' => true,
            ]
        );

        if (! $category->is_default) {
            $category->update(['is_default' => true]);
        }

        $tag = Tag::firstOrCreate(
            ['name' => 'Uncategorized'],
            [
                'description' => 'Default tag created during installation.',
                'status' => 'published',
            ]
        );

        $post = Post::firstOrCreate(
            ['name' => 'Uncategorized'],
            [
                'description' => 'Your first post is ready to be edited.',
                'content' => 'Welcome! You can edit or replace this post anytime.',
                'status' => 'published',
                'author_id' => null,
                'author_type' => User::class,
            ]
        );

        $post->categories()->syncWithoutDetaching([$category->id]);
        $post->tags()->syncWithoutDetaching([$tag->id]);
    }
}
