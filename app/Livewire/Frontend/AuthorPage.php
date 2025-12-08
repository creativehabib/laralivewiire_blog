<?php

namespace App\Livewire\Frontend;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Component;

class AuthorPage extends Component
{
    public User $author;

    public bool $ready = false;

    public Collection $posts;

    public function mount(User $author)
    {
        $this->author = $author;
        $this->posts = collect();
    }

    public function loadAuthor(): void
    {
        $this->posts = Post::query()
            ->published()
            ->with([
                'categories:id,name,slug',
                'author:id,name',
            ])
            ->where('author_id', $this->author->id)
            ->latest('created_at')
            ->take(18)
            ->get();

        $this->ready = true;
    }

    public function render()
    {
        return view('livewire.frontend.author')
            ->layout('components.layouts.frontend.app', [
                'title' => $this->author->name,
            ]);
    }
}
