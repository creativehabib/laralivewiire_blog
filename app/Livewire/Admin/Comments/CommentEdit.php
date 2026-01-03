<?php

namespace App\Livewire\Admin\Comments;

use App\Models\Admin\Comment;
use App\Models\Admin\Page;
use App\Models\Post;
use Livewire\Component;

class CommentEdit extends Component
{
    public Comment $comment;

    public string $name = '';
    public string $email = '';
    public string $website = '';
    public string $content = '';
    public string $status = 'pending';

    public ?string $permalink = null;
    public ?string $submittedOn = null;

    public function mount(Comment $comment): void
    {
        $this->comment = $comment->load('commentable');

        $this->name = (string) $comment->name;
        $this->email = (string) $comment->email;
        $this->website = (string) ($comment->website ?? '');
        $this->content = (string) ($comment->content ?? '');
        $this->status = (string) ($comment->status ?? 'pending');
        $this->submittedOn = $comment->created_at?->format('Y-m-d H:i:s');

        $commentable = $comment->commentable;
        if ($commentable instanceof Post) {
            $this->permalink = post_permalink($commentable) . '#comment-' . $comment->id;
        } elseif ($commentable instanceof Page) {
            $this->permalink = page_permalink($commentable) . '#comment-' . $comment->id;
        }
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'content' => ['required', 'string', 'min:2'],
            'status' => ['required', 'in:approved,pending,spam,trash'],
        ];
    }

    public function save(string $redirect = 'stay')
    {
        $this->validate();

        $this->comment->update([
            'name' => $this->name,
            'email' => $this->email ?: null,
            'website' => $this->website ?: null,
            'content' => $this->content,
            'status' => $this->status,
        ]);

        $this->dispatch('media-toast', type: 'success', message: 'Comment updated successfully!');

        if ($redirect === 'exit') {
            return redirect()->route('admin.comments.moderation');
        }

        return null;
    }

    public function render()
    {
        return view('livewire.admin.comments.comment-edit')
            ->layout('components.layouts.app', [
                'title' => 'Edit Comment',
            ]);
    }
}
