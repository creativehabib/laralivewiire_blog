<?php

namespace App\Livewire\Frontend;

use App\Models\Post;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Livewire\Component;

class PostComments extends Component
{
    public Post $post;

    public Collection $comments;

    public string $name = '';

    public string $email = '';

    public ?string $website = null;

    public string $content = '';

    public ?string $successMessage = null;

    public function mount(Post $post): void
    {
        $this->post = $post;
        $this->prefillUserDetails();
        $this->loadComments();
    }

    protected function rules(): array
    {
        return [
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'content' => ['required', 'string', 'min:5'],
        ];
    }

    public function submit(): void
    {
        abort_unless($this->post->allow_comments, 403);

        $validated = $this->validate();

        $this->post->comments()->create([
            'name'           => $validated['name'],
            'email'          => $validated['email'],
            'website'        => $validated['website'] ?? null,
            'content'        => $validated['content'],
            'status'         => 'pending',
            'user_id'        => Auth::id(),
            'ip_address'     => Request::ip(),
            'user_agent'     => Request::userAgent(),
        ]);

        $this->content = '';
        $this->successMessage = 'আপনার মন্তব্যটি রিভিউর জন্য পাঠানো হয়েছে।';
        $this->loadComments();
    }

    public function render()
    {
        return view('livewire.frontend.post-comments', [
            'allowComments' => (bool) $this->post->allow_comments,
        ]);
    }

    protected function loadComments(): void
    {
        $this->comments = $this->post
            ->comments()
            ->approved()
            ->latest()
            ->get();
    }

    protected function prefillUserDetails(): void
    {
        if (! Auth::check()) {
            return;
        }

        $user = Auth::user();
        $this->name = $user?->name ?? '';
        $this->email = $user?->email ?? '';
    }
}
