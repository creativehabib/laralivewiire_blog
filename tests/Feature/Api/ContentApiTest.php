<?php

use App\Models\Admin\Comment;
use App\Models\Admin\Page;
use App\Models\Admin\Tag;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;

it('serves API index and content endpoints', function () {
    $user = User::factory()->create();

    $category = Category::create([
        'name' => 'Tech',
        'status' => 'published',
        'author_id' => $user->id,
        'author_type' => User::class,
    ]);

    $tag = Tag::create([
        'name' => 'Laravel',
        'status' => 'published',
        'author_id' => $user->id,
        'author_type' => User::class,
    ]);

    $post = Post::create([
        'name' => 'API Post',
        'description' => 'Post description',
        'content' => 'Post content',
        'status' => 'published',
        'author_id' => $user->id,
        'author_type' => User::class,
        'allow_comments' => true,
    ]);

    $post->categories()->attach($category->id);
    $post->tags()->attach($tag->id);

    $page = Page::create([
        'name' => 'About',
        'content' => 'About content',
        'status' => 'published',
        'author_id' => $user->id,
        'author_type' => User::class,
    ]);

    $comment = Comment::create([
        'name' => 'Reader',
        'email' => 'reader@example.com',
        'content' => 'Great post',
        'status' => 'approved',
        'commentable_id' => $post->id,
        'commentable_type' => Post::class,
        'user_id' => $user->id,
    ]);

    $this->getJson('/api/v1')
        ->assertOk()
        ->assertJsonPath('version', 'v1');

    $this->getJson('/api/v1/posts')
        ->assertOk()
        ->assertJsonPath('data.0.name', 'API Post');

    $this->getJson('/api/v1/posts/'.$post->slug)
        ->assertOk()
        ->assertJsonPath('data.id', $post->id);

    $this->getJson('/api/v1/categories/'.$category->slug)
        ->assertOk()
        ->assertJsonPath('data.id', $category->id);

    $this->getJson('/api/v1/tags/'.$tag->slug)
        ->assertOk()
        ->assertJsonPath('data.id', $tag->id);

    $this->getJson('/api/v1/pages/'.$page->slug)
        ->assertOk()
        ->assertJsonPath('data.id', $page->id);

    $this->getJson('/api/v1/comments/'.$comment->id)
        ->assertOk()
        ->assertJsonPath('data.id', $comment->id);
});
