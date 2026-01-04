<?php

namespace App\Livewire\Admin;

use App\Models\Admin\Comment;
use App\Support\ActivityLogger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Livewire\Component;
use Livewire\WithPagination;

class CommentsManager extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = 'all'; // all, pending, approved, spam, trash
    public $selected = [];
    public $selectAll = false;
    public $perPage = 10;
    public bool $showReplyModal = false;
    public ?int $replyParentId = null;
    public ?int $replyCommentableId = null;
    public ?string $replyCommentableType = null;
    public ?string $replyTargetName = null;
    public string $replyContent = '';

    // বাল্ক অ্যাকশন
    public function deleteSelected()
    {
        $count = count($this->selected);
        Comment::whereIn('id', $this->selected)->delete();
        ActivityLogger::log(
            Auth::user(),
            'deleted ' . $count . ' comment(s)'
        );
        $this->selected = [];
        $this->dispatch('media-toast', type: 'success', message: 'Comments deleted successfully!');
    }

    public function updateStatusSelected($status)
    {
        $count = count($this->selected);
        Comment::whereIn('id', $this->selected)->update(['status' => $status]);
        ActivityLogger::log(
            Auth::user(),
            'updated status for ' . $count . ' comment(s) to ' . $status
        );
        $this->selected = [];
        $this->dispatch('media-toast', type: 'success', message: 'Status updated successfully!');
    }

    // সিঙ্গেল অ্যাকশন
    public function updateStatus($id, $status)
    {
        $comment = Comment::find($id);

        if ($comment) {
            $comment->update(['status' => $status]);
            ActivityLogger::log(
                Auth::user(),
                'updated comment #' . $comment->id . ' status to ' . $status,
                $comment
            );
        }
        $this->dispatch('media-toast', type: 'success', message: 'Comment status changed to ' . ucfirst($status));
    }

    public function delete($id)
    {
        $comment = Comment::find($id);

        if ($comment) {
            $comment->delete();
            ActivityLogger::log(
                Auth::user(),
                'deleted comment #' . $comment->id,
                $comment
            );
        }
        $this->dispatch('media-toast', type: 'success', message: 'Comment deleted!');
    }

    public function openReplyModal(int $commentId): void
    {
        $comment = Comment::with('commentable')->findOrFail($commentId);

        $this->replyParentId = $comment->id;
        $this->replyCommentableId = $comment->commentable_id;
        $this->replyCommentableType = $comment->commentable_type;
        $this->replyTargetName = $comment->name;
        $this->replyContent = '';
        $this->showReplyModal = true;

        $this->dispatch('init-reply-editor', content: $this->replyContent);
    }

    public function closeReplyModal(): void
    {
        $this->reset([
            'showReplyModal',
            'replyParentId',
            'replyCommentableId',
            'replyCommentableType',
            'replyTargetName',
            'replyContent',
        ]);
    }

    public function submitReply(): void
    {
        $this->validate([
            'replyContent' => ['required', 'string', 'min:3'],
            'replyParentId' => ['required', 'integer', 'exists:comments,id'],
            'replyCommentableId' => ['required', 'integer'],
            'replyCommentableType' => ['required', 'string'],
        ]);

        $user = Auth::user();

        $comment = Comment::create([
            'name' => $user?->name ?? 'Admin',
            'email' => $user?->email ?? 'admin@example.com',
            'website' => null,
            'content' => $this->replyContent,
            'status' => 'approved',
            'user_id' => $user?->id,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'parent_id' => $this->replyParentId,
            'commentable_id' => $this->replyCommentableId,
            'commentable_type' => $this->replyCommentableType,
        ]);
        ActivityLogger::log(
            $user,
            'replied to comment #' . $this->replyParentId,
            $comment
        );

        $this->closeReplyModal();
        $this->resetPage();
        $this->dispatch('media-toast', type: 'success', message: 'Reply posted successfully!');
    }

    // Select All Logic
    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selected = $this->getCommentsQuery()->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->selected = [];
        }
    }

    public function getCommentsQuery()
    {
        return Comment::query()
            ->when($this->search, function($q) {
                $q->where('content', 'like', '%' . $this->search . '%')
                    ->orWhere('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterStatus !== 'all', function($q) {
                $q->where('status', $this->filterStatus);
            })
            ->latest();
    }

    public function render()
    {
        return view('livewire.admin.comments-manager', [
            'comments' => $this->getCommentsQuery()->paginate($this->perPage)
        ])->layout('components.layouts.app', ['title' => 'Comments']);
    }
}
