<?php

namespace App\Livewire\Admin\Posts;

use App\Models\Category;
use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class PostTable extends Component
{
    use WithPagination;
    protected $paginationTheme = 'tailwind';
    public $search = '';
    public $status = '';
    public $category = '';
    public $perPage = 5;

    public $selected = [];
    public $selectAll = false;

    public $sortField = 'id';
    public $sortDirection = 'desc';

    protected $queryString = ['search', 'status', 'category'];

    // search / filter change হলে page reset
    public function updatingSearch(): void
    { $this->resetPage(); }
    public function updatingStatus(): void
    { $this->resetPage(); }
    public function updatingCategory(): void
    { $this->resetPage(); }
    public function updatingPerPage(): void
    { $this->resetPage(); }

    /** Sort handler */
    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    /** পুরো পেজ থেকে select / unselect */
    public function toggleSelectAll(): void
    {
        $this->selectAll = ! $this->selectAll;

        if ($this->selectAll) {
            $this->selected = $this->baseQuery()->pluck('id')->toArray();
        } else {
            $this->selected = [];
        }
    }

    /** একক post soft delete */
    public function delete(int $id): void
    {
        abort_unless(auth()->user()->can('post.delete'), 404);
        Post::findOrFail($id)->delete();

        $this->selected = array_diff($this->selected, [$id]);
        $this->dispatch('media-toast', title: 'success', message: 'Successfully deleted post to trashed.');
    }

    /** Bulk soft delete */
    public function bulkDelete(): void
    {
        abort_unless(auth()->user()->can('post.delete'), 403);

        if (empty($this->selected)) {
            session()->flash('message', 'No posts selected.');
            return;
        }

        Post::whereIn('id', $this->selected)->delete();

        $this->selected  = [];
        $this->selectAll = false;
        $this->dispatch('media-toast', title: 'success', message: 'Successfully deleted post(s) from trashed.');
    }

    /** Bulk restore from trash */
    public function bulkRestore(): void
    {
        if (empty($this->selected)) {
            session()->flash('message', 'No posts selected.');
            return;
        }

        Post::onlyTrashed()->whereIn('id', $this->selected)->restore();

        $this->selected  = [];
        $this->selectAll = false;
        $this->dispatch('media-toast', title: 'success', message: 'Selected posts restored from trash.');
    }

    /** Bulk permanently delete */
    public function bulkForceDelete(): void
    {
        abort_unless(auth()->user()->can('post.delete'), 403);

        if (empty($this->selected)) {
            session()->flash('message', 'No posts selected.');
            return;
        }

        Post::onlyTrashed()->whereIn('id', $this->selected)->forceDelete();

        $this->selected  = [];
        $this->selectAll = false;
        $this->dispatch('media-toast', title: 'success', message: 'Selected posts permanently deleted.');
    }

    public function toggleStatus(int $id): void
    {
        abort_unless(auth()->user()->can('post.view'), 403);

        $post = Post::findOrFail($id);
        $post->status = $post->status === 'published' ? 'draft' : 'published';
        $post->save();
        $this->dispatch('media-toast', title: 'success', message: 'Successfully updated post status.');

    }

    public function restore(int $id): void
    {
        Post::onlyTrashed()
            ->findOrFail($id)
            ->restore();
        $this->dispatch('media-toast', title: 'success', message: 'Successfully restored post to trashed.');
    }
    public function forceDelete(int $id): void
    {
        abort_unless(auth()->user()->can('post.delete'), 403);
        Post::onlyTrashed()
            ->findOrFail($id)
            ->forceDelete();
        $this->selected = array_diff($this->selected, [$id]);
        $this->dispatch('media-toast', title: 'success', message: 'Post permanently deleted from database.');
    }
    protected function baseQuery()
    {
        $query = Post::query()
            ->with(['categories', 'author']);

        // published / draft / trash
        if ($this->status === 'trash') {
            $query->onlyTrashed();
        } elseif ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->search) {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('slug', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if ($this->category) {
            $categoryId = $this->category;
            $query->whereHas('categories', function ($q) use ($categoryId) {
                $q->where('categories.id', $categoryId);
            });
        }

        // 🔹 Sorting
        $sortable = ['id', 'name', 'created_at'];

        if (in_array($this->sortField, $sortable, true)) {
            $query->orderBy($this->sortField, $this->sortDirection);
        } else {
            $query->latest('id');
        }

        return $query;
    }

    public function render()
    {
        $posts = $this->baseQuery()->paginate($this->perPage);
        $posts->withPath(route('blogs.posts.index'));

        return view('livewire.admin.posts.post-table', [
            'posts'      => $posts,
            'categories' => Category::orderBy('name')->get(),
        ])->layout('components.layouts.app',[
            'title' => 'Post Table'
        ]);
    }
}
