<?php

namespace App\Livewire\Frontend;

use App\Models\Post;

class PostComments extends Comments
{
    public function mount(Post $post): void
    {
        $this->bootCommentable($post);
    }
}
