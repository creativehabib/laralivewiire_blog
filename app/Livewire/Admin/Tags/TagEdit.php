<?php

namespace App\Livewire\Admin\Tags;

use App\Models\Admin\Tag;
use App\Support\ActivityLogger;
use App\Support\SlugService;
use App\Livewire\Concerns\HandlesSlug;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class TagEdit extends Component
{
    use HandlesSlug;

    public Tag $tag;
    public $name;
    public $description;
    public $status;

    public $author_id;
    public $author_type;

    // SEO fields (চাইলে এসব add করো)
    public $seo_title;
    public $seo_description;
    public $seo_image;
    public $seo_index = 'index';
    public $autoSeoTitle = true;

    public function mount(Tag $tag)
    {
        // route model binding থেকে আসা Tag model
        $this->tag = $tag;

        // form fields pre-fill
        $this->name        = $tag->name;
        $this->slug        = $tag->slug;
        $this->slugId      = $tag->slugRecord?->id;
        $this->description = $tag->description;
        $this->status      = $tag->status;
        $this->author_id   = $tag->author_id;
        $this->author_type = $tag->author_type;

        // যদি meta ব্যবহার করো (HasMetaBoxes trait)
        if (method_exists($tag, 'getMeta')) {
            $seoMeta = $tag->getMeta('seo_meta', []);
            $seo     = $seoMeta[0] ?? [];

            $this->seo_title       = $seo['seo_title']       ?? null;
            $this->seo_description = $seo['seo_description'] ?? null;
            $this->seo_image       = $seo['seo_image']       ?? null;
            $this->seo_index       = $seo['index']           ?? 'index';
        }
    }

    protected function rules()
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'slug'        => [
                'required',
                'string',
                'max:255',
                Rule::unique('slugs', 'key')->ignore($this->slugId),
            ],
            'description' => ['nullable', 'string'],
            'status'      => ['required', Rule::in(['published', 'draft'])],

            'seo_title'       => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string'],
            'seo_image'       => ['nullable', 'string', 'max:255'],
            'seo_index'       => ['nullable', 'string', 'max:50'],
        ];
    }

    public function updatedName($value): void
    {
        if ($this->autoSeoTitle) {
            $this->seo_title = $value;
        }
    }

    public function update($redirect = 'stay')
    {
        $this->slug = SlugService::create($this->slug ?: $this->name, '', $this->slugId);

        $this->validate();

        $user = Auth::user();
        if ($user && !$this->author_id) {
            $this->author_id   = $user->id;
            $this->author_type = get_class($user);
        }

        $this->tag->name        = $this->name;
        $this->tag->slug        = $this->slug;
        $this->tag->description = $this->description;
        $this->tag->status      = $this->status;
        $this->tag->author_id   = $this->author_id;
        $this->tag->author_type = $this->author_type;
        $this->tag->save();

        $this->slugId = $this->tag->slugRecord?->id;

        // meta থাকলে save করো
        if (method_exists($this->tag, 'setMeta')) {
            $this->tag->setMeta('seo_meta', [[
                'seo_title'       => $this->seo_title,
                'seo_description' => $this->seo_description,
                'seo_image'       => $this->seo_image,
                'index'           => $this->seo_index ?: 'index',
            ]]);
        }

        ActivityLogger::log(
            $user,
            'updated tag "' . $this->tag->name . '"',
            $this->tag
        );

        session()->flash('message', 'Tag updated successfully.');

        if ($redirect === 'exit') {
            return redirect()->route('blogs.tags.index');
        }
    }

    public function render()
    {
        return view('livewire.admin.tags.tag-edit')
            ->layout('components.layouts.app', [
                'title' => 'Edit tag',
            ]);
    }
}
