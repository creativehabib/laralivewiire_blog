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

    public array $selected = [];
    public bool $selectAll = false;

    protected $updatesQueryString = ['search', 'status', 'page'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    protected function baseQuery()
    {
        $query = Tag::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        return $query;
    }

    public function toggleSelectAll(): void
    {
        $this->selectAll = ! $this->selectAll;

        if ($this->selectAll) {
            $this->selected = $this->baseQuery()
                ->orderByDesc('id')
                ->paginate($this->perPage)
                ->pluck('id')
                ->toArray();
        } else {
            $this->selected = [];
        }
    }

    public function delete($id)
    {
        Tag::findOrFail($id)->delete();
        $this->selected = array_values(array_diff($this->selected, [$id]));
        $this->selectAll = false;
        session()->flash('message', 'Tag deleted successfully.');
    }

    public function bulkDelete(): void
    {
        if (empty($this->selected)) {
            session()->flash('message', 'No tags selected.');
            return;
        }

        Tag::whereIn('id', $this->selected)->delete();

        $this->selected = [];
        $this->selectAll = false;
        session()->flash('message', 'Selected tags deleted successfully.');
    }

    public function render()
    {
        $tags = $this->baseQuery()->orderByDesc('id')->paginate($this->perPage);
        $tags->withPath(route('blogs.tags.index'));

        return view('livewire.admin.tags.tags-index', compact('tags'))->layout('components.layouts.app', [
            'title' =>  'Tags List'
        ]);
    }
}
