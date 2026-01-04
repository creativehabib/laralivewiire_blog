<?php

namespace App\Livewire\Admin\Pages;

use App\Models\Admin\Page;
use App\Support\ActivityLogger;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class PageTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public string $search = '';
    public string $status = ''; // '', published, draft, trash
    public int $perPage = 10;

    public array $selected = [];
    public bool $selectAll = false;

    public string $sortField = 'id';
    public string $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'perPage' => ['except' => 10],
        'sortField' => ['except' => 'id'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatus() { $this->resetPage(); }
    public function updatingPerPage() { $this->resetPage(); }

    public function sortBy(string $field): void
    {
        $sortable = ['id', 'name', 'created_at'];

        if (!in_array($field, $sortable, true)) return;

        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    protected function baseQuery()
    {
        $query = Page::query();

        // Trash filter
        if ($this->status === 'trash') {
            $query->onlyTrashed();
        } else {
            // default: exclude trashed
            $query->whereNull('deleted_at');
        }

        // status filter (published/draft)
        if ($this->status && $this->status !== 'trash') {
            $query->where('status', $this->status);
        }

        // search (name/slug/description)
        if ($this->search) {
            $s = $this->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                    ->orWhereHas('slugRecord', function ($slugQuery) use ($s) {
                        $slugQuery->where('key', 'like', "%{$s}%");
                    })
                    ->orWhere('description', 'like', "%{$s}%");
            });
        }

        // sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        return $query;
    }

    public function toggleSelectAll(): void
    {
        $this->selectAll = !$this->selectAll;

        if ($this->selectAll) {
            // only current page items
            $this->selected = $this->baseQuery()
                ->paginate($this->perPage)
                ->pluck('id')
                ->toArray();
        } else {
            $this->selected = [];
        }
    }

    public function delete(int $id): void
    {
        $page = Page::findOrFail($id);
        $page->delete(); // soft delete
        ActivityLogger::log(
            Auth::user(),
            'moved page "' . $page->name . '" to trash',
            $page
        );
        $this->selected = array_values(array_diff($this->selected, [$id]));
        $this->selectAll = false;

        $this->dispatch('media-toast', type: 'success', message: 'Page moved to Trash.');
    }

    public function restore(int $id): void
    {
        $page = Page::onlyTrashed()->findOrFail($id);
        $page->restore();
        ActivityLogger::log(
            Auth::user(),
            'restored page "' . $page->name . '"',
            $page
        );

        $this->selected = array_values(array_diff($this->selected, [$id]));
        $this->selectAll = false;

        $this->dispatch('media-toast', type: 'success', message: 'Page restored successfully.');
    }

    public function forceDelete(int $id): void
    {
        $page = Page::onlyTrashed()->findOrFail($id);
        $page->forceDelete();
        ActivityLogger::log(
            Auth::user(),
            'permanently deleted page "' . $page->name . '"'
        );

        $this->selected = array_values(array_diff($this->selected, [$id]));
        $this->selectAll = false;

        $this->dispatch('media-toast', type: 'success', message: 'Page permanently deleted.');
    }

    public function bulkDelete(): void
    {
        if (empty($this->selected)) {
            $this->dispatch('media-toast', type: 'warning', message: 'No pages selected.');
            return;
        }

        $count = count($this->selected);
        Page::whereIn('id', $this->selected)->delete();
        ActivityLogger::log(
            Auth::user(),
            'moved ' . $count . ' pages to trash'
        );

        $this->selected = [];
        $this->selectAll = false;

        $this->dispatch('media-toast', type: 'success', message: 'Selected pages moved to Trash.');
    }

    public function bulkRestore(): void
    {
        if (empty($this->selected)) {
            $this->dispatch('media-toast', type: 'warning', message: 'No pages selected.');
            return;
        }

        $count = count($this->selected);
        Page::onlyTrashed()->whereIn('id', $this->selected)->restore();
        ActivityLogger::log(
            Auth::user(),
            'restored ' . $count . ' pages'
        );

        $this->selected = [];
        $this->selectAll = false;

        $this->dispatch('media-toast', type: 'success', message: 'Selected pages restored.');
    }

    public function bulkForceDelete(): void
    {
        if (empty($this->selected)) {
            $this->dispatch('media-toast', type: 'warning', message: 'No pages selected.');
            return;
        }

        $count = count($this->selected);
        Page::onlyTrashed()->whereIn('id', $this->selected)->forceDelete();
        ActivityLogger::log(
            Auth::user(),
            'permanently deleted ' . $count . ' pages'
        );

        $this->selected = [];
        $this->selectAll = false;

        $this->dispatch('media-toast', type: 'success', message: 'Selected pages permanently deleted.');
    }

    public function toggleStatus(int $id): void
    {
        $page = Page::withTrashed()->findOrFail($id);

        // trash থাকলে status toggle করবে না
        if ($page->trashed()) return;

        $page->status = $page->status === 'published' ? 'draft' : 'published';
        $page->save();
        ActivityLogger::log(
            Auth::user(),
            'updated page status for "' . $page->name . '" to ' . $page->status,
            $page
        );

        $this->dispatch('media-toast', type: 'success', message: 'Status updated.');
    }

    public function render()
    {
        $pages = $this->baseQuery()->paginate($this->perPage);

        return view('livewire.admin.pages.page-table', [
            'pages' => $pages,
        ])->layout('components.layouts.app', [
            'title' => 'Pages',
        ]);
    }
}
