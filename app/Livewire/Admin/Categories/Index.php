<?php

// app/Livewire/Admin/Categories/Index.php

namespace App\Livewire\Admin\Categories;

use App\Models\Category;
use App\Support\SlugService;
use App\Livewire\Concerns\HandlesSlug;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    use WithFileUploads;
    use HandlesSlug;

    // form fields
    public $categoryId = null;
    public $name;
    public $parent_id = null;
    public $description;
    public $icon;
    public $is_default = false;
    public $is_featured = false;
    public $status = 'published';
    public $order = 0;

    public $image;        // uploaded file


    // SEO meta (meta_boxes -> seo_meta)
    public $seo_title;
    public $seo_description;
    public $seo_image;
    public $seo_index = 'index';

    // UI
    public $search = '';
    public $isEdit = false;

    // view type: tree / table  ( /categories?as=table হলে table )
    public $viewType = 'tree';

    protected function rules()
    {
        $slugRule = 'required|string|max:255|unique:slugs,key';

        if ($this->categoryId) {
            $slugRule = 'required|string|max:255|unique:slugs,key,' . $this->slugId;
        }

        return [
            'name'        => 'required|string|max:255',
            'slug'        => $slugRule,
            'parent_id'   => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'icon'        => 'nullable|string|max:255',
            'is_default'  => 'boolean',
            'is_featured' => 'boolean',
            'status'      => 'required|in:draft,published',
            'order'       => 'nullable|integer|min:0',
            'image'       => 'nullable|string|max:2048',

            'seo_title'       => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'seo_image'       => 'nullable|string|max:255',
            'seo_index'       => 'nullable|string|max:50',
        ];
    }

    public function mount()
    {
        // কোন ভিউ? /admin/categories?as=table হলে table
        $this->viewType = request('as') === 'table' ? 'table' : 'tree';

        // Spatie Permission check
        if (Auth::check()) {
            $user = Auth::user();
            if (! $user->can('manage categories') && ! $user->hasRole('admin')) {
                abort(403);
            }
        }
    }

    public function updatedName($value): void
    {
        $this->slug = $this->generateSlugValue((string) $value);
        if (empty($this->seo_title)) {
            $this->seo_title = $value;
        }
    }

    public function createRootCategory()
    {
        $this->resetForm();
        $this->parent_id = null;
    }

    public function createChildCategory($parentId)
    {
        $this->resetForm();
        $this->parent_id = $parentId;
    }

    public function selectCategory($id)
    {
        $this->edit($id);
    }

    public function edit($id)
    {
        $category = Category::with('metaBoxes')->findOrFail($id);

        $this->categoryId  = $category->id;
        $this->name        = $category->name;
        $this->slug        = $category->slug;
        $this->slugId      = $category->slugRecord?->id;
        $this->parent_id   = $category->parent_id;
        $this->description = $category->description;
        $this->icon        = $category->icon;
        $this->is_default  = $category->is_default;
        $this->is_featured = $category->is_featured;
        $this->status      = $category->status;
        $this->order       = $category->order;
        $this->image       = $category->image;
        $this->isEdit      = true;

        $seoMeta = $category->getMeta('seo_meta', []);
        $seo     = $seoMeta[0] ?? [];

        $this->seo_title       = $seo['seo_title']       ?? null;
        $this->seo_description = $seo['seo_description'] ?? null;
        $this->seo_image       = $seo['seo_image']       ?? null;
        $this->seo_index       = $seo['index']           ?? 'index';
    }

    /** Save / Update (Save & Save & Exit দুটোর জন্য) */
    public function save($exit = false)
    {
        $user = Auth::user();

        if ($this->categoryId) {
            $category = Category::findOrFail($this->categoryId);
        } else {
            $category = new Category();
            $category->author_id   = $user?->id;
            $category->author_type = $user ? get_class($user) : null;
        }

        if ($this->categoryId && $this->slug === $category->slug && $this->name !== $category->name) {
            $this->slug = $this->generateSlugValue((string) $this->name);
        }

        $this->slug = SlugService::create($this->slug ?: $this->name, '', $this->slugId);

        $this->validate($this->rules());

        if ($this->is_default) {
            Category::where('is_default', 1)
                ->when($this->categoryId, function ($q) {
                    $q->where('id', '!=', $this->categoryId);
                })
                ->update(['is_default' => 0]);
        }

        $category->name        = $this->name;
        $category->slug        = $this->slug;
        $category->parent_id   = $this->parent_id;
        $category->description = $this->description;
        $category->icon        = $this->icon;
        $category->is_default  = $this->is_default ? 1 : 0;
        $category->is_featured = $this->is_featured ? 1 : 0;
        $category->status      = $this->status;
        $category->order       = $this->order ?? 0;
        $category->image       = $this->image;
        $category->save();

        $this->slugId = $category->slugRecord?->id;

        $category->setMeta('seo_meta', [[
            'seo_title'       => $this->seo_title,
            'seo_description' => $this->seo_description,
            'seo_image'       => $this->seo_image,
            'index'           => $this->seo_index ?: 'index',
        ]]);

        $this->categoryId = $category->id;
        $this->isEdit     = true;

        $this->dispatch('media-toast', type: 'success', message: 'Category saved successfully.');

        if ($exit) {
            $this->resetForm();
        }
    }

    protected $listeners = [
        'updateCategoryTree' => 'updateCategoryTree',
    ];

    #[On('categories-tree-updated')]
    public function updateCategoryTree($items)
    {
        if (!is_array($items)) {
            return;
        }

        foreach ($items as $item) {
            if (!isset($item['id'])) {
                continue;
            }

            Category::where('id', $item['id'])->update([
                'parent_id' => $item['parent_id'] ?? null,
                'order'     => $item['order'] ?? 0,
            ]);
        }
        $this->dispatch('media-toast', type: 'success', message: 'Category tree updated successfully.');

        $this->resetForm();
    }

    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);

        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        if ($this->categoryId == $id) {
            $this->resetForm();
        }
        $this->dispatch('media_toast', type: 'success', message: 'Category deleted successfully.');
    }

    /** drag & drop থেকে আসা নতুন order হ্যান্ডল */
    public function reorder($orderedItems)
    {
        // $orderedItems => [ ['value' => 5, 'order' => 0], ... ]
        foreach ($orderedItems as $item) {
            Category::where('id', $item['value'])
                ->update(['order' => $item['order']]);
        }
    }

    public function resetForm()
    {
        $this->reset([
            'categoryId',
            'name',
            'slug',
            'parent_id',
            'description',
            'icon',
            'is_default',
            'is_featured',
            'status',
            'order',
            'image',
            'seo_title',
            'seo_description',
            'seo_image',
            'seo_index',
            'isEdit',
        ]);

        $this->status    = 'published';
        $this->order     = 0;
        $this->seo_index = 'index';
    }

    /** tree view এর root ক্যাটাগরি */
    public function getRootCategoriesProperty()
    {
        return Category::with('childrenRecursive')
            ->withCount('posts')
            ->when($this->search, function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get();
    }

    public function render()
    {
        $baseUrl = config('app.url');

        // table view এর জন্য flat list (paginate)
        $tableCategories = null;
        if ($this->viewType === 'table') {
            $tableCategories = Category::with('parent')
                ->when($this->search, function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })
                ->orderBy('order')
                ->orderBy('name')
                ->paginate(10);
        }

        return view('livewire.admin.categories.index', [
            'rootCategories'  => $this->rootCategories,
            'tableCategories' => $tableCategories,
            'baseUrl'         => $baseUrl,
        ])->layout('components.layouts.app', [
            'title' => 'Categories',
        ]);
    }
}
