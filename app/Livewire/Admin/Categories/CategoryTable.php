<?php
// app/Livewire/Admin/Categories/CategoryTable.php

namespace App\Livewire\Admin\Categories;

use App\Models\Category;
use App\Support\ActivityLogger;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryTable extends Component
{
    use WithPagination;
    public $search = '';
    public int $perPage = 10;

    public array $selected = [];
    public bool $selectAll = false;
    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if (! $user->can('manage categories') && ! $user->hasRole('admin')) {
                abort(403);
            }
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function refreshTable(): void
    {
        $this->resetPage();
    }

    protected function baseQuery()
    {
        return Category::with('parent')
            ->when($this->search, function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%');
            })
            ->orderBy('order')
            ->orderBy('name');
    }

    public function toggleSelectAll(): void
    {
        $this->selectAll = ! $this->selectAll;

        if ($this->selectAll) {
            $this->selected = $this->baseQuery()
                ->paginate($this->perPage)
                ->pluck('id')
                ->toArray();
        } else {
            $this->selected = [];
        }
    }

    public function deleteCategory($id)
    {
        $cat = Category::findOrFail($id);
        $cat->delete();
        ActivityLogger::log(
            Auth::user(),
            'deleted category "' . $cat->name . '"',
            $cat
        );
        $this->selected = array_values(array_diff($this->selected, [$id]));
        $this->selectAll = false;
        session()->flash('success', 'Category deleted successfully.');
    }

    public function bulkDelete(): void
    {
        if (empty($this->selected)) {
            session()->flash('success', 'No categories selected.');
            return;
        }

        $count = count($this->selected);
        Category::whereIn('id', $this->selected)->delete();
        ActivityLogger::log(
            Auth::user(),
            'deleted ' . $count . ' categories'
        );

        $this->selected = [];
        $this->selectAll = false;
        session()->flash('success', 'Selected categories deleted successfully.');
    }

    public function bulkPublish(): void
    {
        if (empty($this->selected)) {
            session()->flash('success', 'No categories selected.');
            return;
        }

        $count = count($this->selected);
        Category::whereIn('id', $this->selected)->update(['status' => 'published']);
        ActivityLogger::log(
            Auth::user(),
            'published ' . $count . ' categories'
        );

        $this->selected = [];
        $this->selectAll = false;
        session()->flash('success', 'Selected categories published successfully.');
    }

    public function bulkDraft(): void
    {
        if (empty($this->selected)) {
            session()->flash('success', 'No categories selected.');
            return;
        }

        $count = count($this->selected);
        Category::whereIn('id', $this->selected)->update(['status' => 'draft']);
        ActivityLogger::log(
            Auth::user(),
            'moved ' . $count . ' categories to draft'
        );

        $this->selected = [];
        $this->selectAll = false;
        session()->flash('success', 'Selected categories moved to draft.');
    }

    public function render()
    {
        $categories = $this->baseQuery()->paginate($this->perPage);
        $categories->withPath(route('blogs.categories.index'));

        return view('livewire.admin.categories.category-table', [
            'categories' => $categories,
        ]);
    }
}
