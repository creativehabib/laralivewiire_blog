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

    public ?int $parentId = null;

    public ?string $replyingTo = null;

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
            'parentId' => ['nullable', 'integer', 'exists:comments,id'],
        ];
    }

    public function submit(): void
    {
        abort_unless($this->post->allow_comments, 403);

        $validated = $this->validate();

        $parentId = null;

        if (! empty($validated['parentId'])) {
            $parentId = $this->post
                ->comments()
                ->approved()
                ->findOrFail($validated['parentId'])
                ->id;
        }

        $this->post->comments()->create([
            'name'           => $validated['name'],
            'email'          => $validated['email'],
            'website'        => $validated['website'] ?? null,
            'content'        => $validated['content'],
            'status'         => 'pending',
            'user_id'        => Auth::id(),
            'ip_address'     => Request::ip(),
            'user_agent'     => Request::userAgent(),
            'parent_id'      => $parentId,
        ]);

        $this->content = '';
        $this->successMessage = 'আপনার মন্তব্যটি রিভিউর জন্য পাঠানো হয়েছে।';
        $this->parentId = null;
        $this->replyingTo = null;
        $this->loadComments();
    }

    public function setReply(int $commentId): void
    {
        $comment = $this->post->comments()->approved()->findOrFail($commentId);

        $this->parentId = $comment->id;
        $this->replyingTo = $comment->name;
        $this->successMessage = null;
    }

    public function cancelReply(): void
    {
        $this->parentId = null;
        $this->replyingTo = null;
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
            ->whereNull('parent_id')
            ->approved()
            ->latest()
            ->with(['parent', 'repliesRecursive'])
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
