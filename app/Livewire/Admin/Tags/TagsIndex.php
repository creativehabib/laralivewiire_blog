<?php

namespace App\Livewire\Admin\Tags;

use App\Models\Admin\Tag;
use Livewire\Component;
use Livewire\WithPagination;

class TagsIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $perPage = 6;

    protected $updatesQueryString = ['search', 'status', 'page'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        Tag::findOrFail($id)->delete();
        session()->flash('message', 'Tag deleted successfully.');
    }

    public function render()
    {
        $query = Tag::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        $tags = $query->orderByDesc('id')->paginate($this->perPage);
        $tags->withPath(route('blogs.tags.index'));

        return view('livewire.admin.tags.tags-index', compact('tags'))->layout('components.layouts.app', [
            'title' =>  'Tags List'
        ]);
    }
}
