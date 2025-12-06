<?php
// app/Livewire/Admin/Categories/CategoryTable.php

namespace App\Livewire\Admin\Categories;

use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryTable extends Component
{
    use WithPagination;
    public $search = '';
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

    public function deleteCategory($id)
    {
        $cat = Category::findOrFail($id);
        $cat->delete();
        session()->flash('success', 'Category deleted successfully.');
    }

    public function render()
    {
        $categories = Category::with('parent')
            ->when($this->search, function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%');
            })
            ->orderBy('order')
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.categories.category-table', [
            'categories' => $categories,
        ]);
    }
}
