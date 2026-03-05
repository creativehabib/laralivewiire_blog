<?php

namespace App\Livewire\Admin\Posts;

use App\Models\Category;
use App\Models\Post;
use App\Support\ActivityLogger;
use Illuminate\Support\Facades\Auth;
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


    private function isAdmin(): bool
    {
        return (bool) auth()->user()?->hasRole('admin');
    }

    private function scopeToOwnedPostsIfNeeded($query)
    {
        if ($this->isAdmin()) {
            return $query;
        }

        return $query->where('author_id', auth()->id());
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
        $post = Post::findOrFail($id);
        abort_unless($this->isAdmin() || (int) $post->author_id === (int) auth()->id(), 403);
        $post->delete();
        ActivityLogger::log(
            Auth::user(),
            'moved post "' . $post->name . '" to trash',
            $post
        );

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

        $count = count($this->selected);
        $query = Post::whereIn('id', $this->selected);
        $query = $this->scopeToOwnedPostsIfNeeded($query);
        $query->delete();
        ActivityLogger::log(
            Auth::user(),
            'moved ' . $count . ' posts to trash'
        );

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

        $count = count($this->selected);
        $query = Post::onlyTrashed()->whereIn('id', $this->selected);
        $query = $this->scopeToOwnedPostsIfNeeded($query);
        $query->restore();
        ActivityLogger::log(
            Auth::user(),
            'restored ' . $count . ' posts'
        );

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

        $count = count($this->selected);
        $query = Post::onlyTrashed()->whereIn('id', $this->selected);
        $query = $this->scopeToOwnedPostsIfNeeded($query);
        $query->forceDelete();
        ActivityLogger::log(
            Auth::user(),
            'permanently deleted ' . $count . ' posts'
        );

        $this->selected  = [];
        $this->selectAll = false;
        $this->dispatch('media-toast', title: 'success', message: 'Selected posts permanently deleted.');
    }

    public function toggleStatus(int $id): void
    {
        abort_unless(auth()->user()->can('post.view'), 403);

        $post = Post::findOrFail($id);
        abort_unless($this->isAdmin() || (int) $post->author_id === (int) auth()->id(), 403);
        $post->status = $post->status === 'published' ? 'draft' : 'published';
        $post->save();
        ActivityLogger::log(
            Auth::user(),
            'updated post status for "' . $post->name . '" to ' . $post->status,
            $post
        );
        $this->dispatch('media-toast', title: 'success', message: 'Successfully updated post status.');

    }

    public function restore(int $id): void
    {
        $post = Post::onlyTrashed()
            ->findOrFail($id);
        abort_unless($this->isAdmin() || (int) $post->author_id === (int) auth()->id(), 403);
        $post->restore();
        ActivityLogger::log(
            Auth::user(),
            'restored post "' . $post->name . '"',
            $post
        );
        $this->dispatch('media-toast', title: 'success', message: 'Successfully restored post to trashed.');
    }
    public function forceDelete(int $id): void
    {
        abort_unless(auth()->user()->can('post.delete'), 403);
        $post = Post::onlyTrashed()
            ->findOrFail($id);
        abort_unless($this->isAdmin() || (int) $post->author_id === (int) auth()->id(), 403);
        $post->forceDelete();
        ActivityLogger::log(
            Auth::user(),
            'permanently deleted post "' . $post->name . '"'
        );
        $this->selected = array_diff($this->selected, [$id]);
        $this->dispatch('media-toast', title: 'success', message: 'Post permanently deleted from database.');
    }
    protected function baseQuery()
    {
        $query = Post::query()
            ->with(['categories', 'author']);

        $query = $this->scopeToOwnedPostsIfNeeded($query);

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
                    ->orWhereHas('slugRecord', function ($slugQuery) use ($search) {
                        $slugQuery->where('key', 'like', '%' . $search . '%');
                    })
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
