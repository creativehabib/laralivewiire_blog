<?php
// app/Livewire/Admin/Categories/CategoryForm.php

namespace App\Livewire\Admin\Categories;

use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class CategoryForm extends Component
{
    use WithFileUploads;

    public $categoryId = null;

    public $name;
    public $slug;
    public $parent_id = null;
    public $description;
    public $icon;
    public $is_default = false;
    public $is_featured = false;
    public $status = 'published';
    public $image;

    public $seo_title;
    public $seo_description;
    public $seo_index = 'index';
    public $seo_image;

    protected function rules()
    {
        $slugRule = 'required|string|max:255|unique:categories,slug';
        if ($this->categoryId) {
            $slugRule = 'required|string|max:255|unique:categories,slug,' . $this->categoryId;
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
            'image'       => 'nullable|string|max:255',

            'seo_title'       => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'seo_image'       => 'nullable|string|max:255',
            'seo_index'       => 'nullable|string|max:50',
        ];
    }

    public function mount($categoryId = null)
    {
        if (Auth::check()) {
            $user = Auth::user();
            if (! $user->can('manage categories') && ! $user->hasRole('admin')) {
                abort(403);
            }
        }

        $this->categoryId = $categoryId;

        if ($categoryId) {
            // edit
            $category = Category::with('metaBoxes')->findOrFail($categoryId);

            $this->name        = $category->name;
            $this->slug        = $category->slug;
            $this->parent_id   = $category->parent_id;
            $this->description = $category->description;
            $this->icon        = $category->icon;
            $this->is_default  = $category->is_default;
            $this->is_featured = $category->is_featured;
            $this->status      = $category->status;
            $this->image        = $category->image;

            $seoMeta = $category->getMeta('seo_meta', []);
            $seo     = $seoMeta[0] ?? [];
            $this->seo_title       = $seo['seo_title']       ?? null;
            $this->seo_description = $seo['seo_description'] ?? null;
            $this->seo_image       = $seo['seo_image']       ?? null;
            $this->seo_index       = $seo['index']           ?? 'index';
        }
    }

    public function updatedName($value)
    {
        if (!$this->slug) {
            $this->slug = Str::slug($value);
        }
        if (!$this->seo_title) {
            $this->seo_title = $value;
        }
    }

    public function updatedSlug($value)
    {
        $this->slug = Str::slug($value);
    }

    /** Save / Save & exit */
    public function save($exit = false)
    {
        $this->validate($this->rules());

        $user = Auth::user();

        if ($this->categoryId) {
            $category = Category::findOrFail($this->categoryId);
        } else {
            $category = new Category();
            $category->author_id   = $user?->id;
            $category->author_type = $user ? get_class($user) : null;
        }

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
        $category->image       = $this->image;
        $category->save();

        $category->setMeta('seo_meta', [[
            'seo_title'       => $this->seo_title,
            'seo_description' => $this->seo_description,
            'seo_image'       => $this->seo_image,
            'index'           => $this->seo_index ?: 'index',
        ]]);

        $this->categoryId = $category->id;

        session()->flash('success', 'Category saved successfully.');
        $this->dispatch('media-toast', type: 'success', message: 'Category saved successfully.');

        if ($exit) {
            return redirect()->route('blogs.categories.index');
        }

        // stay on same page (edit mode)
        return redirect()->route('blogs.categories.edit', $category->id);
    }

    public function render()
    {
        return view('livewire.admin.categories.category-form', [
            'baseUrl'    => config('app.url'),
            'categories' => Category::orderBy('name')->get(),
        ])->layout('components.layouts.app', [
            'title' => $this->categoryId ? 'Edit Category' : 'Create Category'
        ]);
    }
}

