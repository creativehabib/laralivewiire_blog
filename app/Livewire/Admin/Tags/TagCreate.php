<?php

namespace App\Livewire\Admin\Tags;

use App\Models\Admin\Tag;
use App\Support\ActivityLogger;
use App\Support\SlugService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;

class TagCreate extends Component
{
    public $tagId = null;
    public $name = '';
    public $slug = '';
    public $description = '';
    public $status = 'published';

    // author ফিল্ড আমরা auth user থেকে নেব
    public $author_id;
    public $author_type;

    public $seo_title;
    public $seo_description;
    public $seo_index = 'index';
    public $seo_image;


    protected function rules()
    {
        return [
            'name'        => 'required|string|max:255',
            'slug'        => ['required', 'string', 'max:255', Rule::unique('slugs', 'key')],
            'description' => 'nullable|string',
            'status'      => 'required|in:published,draft',

            'seo_title'       => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'seo_image'       => 'nullable|string|max:255',
            'seo_index'       => 'nullable|string|max:50',
        ];
    }

    public function mount()
    {
        if (auth()->check()) {
            $this->author_id   = auth()->id();
            $this->author_type = get_class(auth()->user()); // যেমন: App\Models\User
        }
    }

    public function updatedName($value)
    {
        // name থেকে slug অটো জেনারেট (যদি ইউজার আগে থেকে না দেয়)
        if (! $this->slug) {
            $this->slug = Str::slug($value);
        }
    }

    public function save($redirect = 'stay')
    {
        $this->slug = SlugService::create($this->slug ?: $this->name);

        $this->validate($this->rules());


        $user = Auth::user();
        $isNew = ! $this->tagId;

        if ($this->tagId) {
            $tag = Tag::findOrFail($this->tagId);
        } else {
            $tag = new Tag();
            $tag->author_id   = $user?->id;
            $tag->author_type = $user ? get_class($user) : null;
        }

        $tag->name        = $this->name;
        $tag->slug        = $this->slug;
        $tag->description = $this->description;
        $tag->status      = $this->status;
        $tag->save();

        $tag->setMeta('seo_meta', [[
            'seo_title'       => $this->seo_title,
            'seo_description' => $this->seo_description,
            'seo_image'       => $this->seo_image,
            'index'           => $this->seo_index ?: 'index',
        ]]);

        ActivityLogger::log(
            $user,
            ($isNew ? 'created' : 'updated') . ' tag "' . $tag->name . '"',
            $tag
        );

        session()->flash('message', 'Tag created successfully.');

        if ($redirect === 'exit') {
            return redirect()->route('blogs.tags.index');
        }

        // stay on the form, but reset fields
        $this->reset(['name', 'slug', 'description', 'status']);
        $this->status = 'published';
    }



    public function render()
    {
        return view('livewire.admin.tags.tag-create');
    }
}
