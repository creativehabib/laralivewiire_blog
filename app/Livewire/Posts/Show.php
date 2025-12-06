<?php

namespace App\Livewire\Posts;

use App\Livewire\Forms\PostForm;
use App\Models\Post;
use Livewire\Component;

class Show extends Component
{
    public PostForm $form;

    public function mount(Post $post)
    {
        $this->form->setPostModel($post);
    }

    public function render()
    {
        return view('livewire.post.show', ['post' => $this->form->postModel]);
    }
}
