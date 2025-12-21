<?php

namespace App\Livewire\Admin;

use App\Models\Admin\Comment;
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

    // বাল্ক অ্যাকশন
    public function deleteSelected()
    {
        Comment::whereIn('id', $this->selected)->delete();
        $this->selected = [];
        $this->dispatch('media-toast', type: 'success', message: 'Comments deleted successfully!');
    }

    public function updateStatusSelected($status)
    {
        Comment::whereIn('id', $this->selected)->update(['status' => $status]);
        $this->selected = [];
        $this->dispatch('media-toast', type: 'success', message: 'Status updated successfully!');
    }

    // সিঙ্গেল অ্যাকশন
    public function updateStatus($id, $status)
    {
        Comment::find($id)->update(['status' => $status]);
        $this->dispatch('media-toast', type: 'success', message: 'Comment status changed to ' . ucfirst($status));
    }

    public function delete($id)
    {
        Comment::find($id)->delete();
        $this->dispatch('media-toast', type: 'success', message: 'Comment deleted!');
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
