<?php

namespace App\Livewire\Admin;

use App\Models\Admin\Comment;
use Illuminate\Support\Collection;
use Livewire\Component;

class CommentNotifications extends Component
{
    public int $pendingCount = 0;

    public int $latestCommentId = 0;

    public function mount(): void
    {
        $this->pendingCount = Comment::where('status', 'pending')->count();
        $this->latestCommentId = (int) Comment::max('id');
    }

    public function checkForUpdates(): void
    {
        $newComments = Comment::with('commentable')
            ->where('id', '>', $this->latestCommentId)
            ->orderBy('id')
            ->get();

        if ($newComments->isNotEmpty()) {
            $this->latestCommentId = (int) $newComments->max('id');
            $this->pendingCount = Comment::where('status', 'pending')->count();
            $this->notifyAdmins($newComments);

            return;
        }

        $this->pendingCount = Comment::where('status', 'pending')->count();
    }

    protected function notifyAdmins(Collection $newComments): void
    {
        if ($newComments->count() === 1) {
            $comment = $newComments->first();
            $commentableName = data_get($comment->commentable, 'name');
            $context = $commentableName ? " on {$commentableName}" : '';
            $status = $comment->status === 'pending' ? 'pending review' : 'posted';

            $this->dispatch('media-toast', type: 'info', message: "New comment from {$comment->name}{$context} ({$status}).");

            return;
        }

        $this->dispatch('media-toast', type: 'info', message: "{$newComments->count()} new comments received.");
    }

    public function render()
    {
        return view('livewire.admin.comment-notifications');
    }
}
