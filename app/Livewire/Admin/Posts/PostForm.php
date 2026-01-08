<?php

namespace App\Livewire\Admin\Posts;

use App\Models\Post;
use App\Models\Category;
use App\Models\Admin\Tag;
use App\Support\ActivityLogger;
use App\Support\SeoAnalyzer;
use App\Support\SlugService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use function Livewire\Volt\title;

class PostForm extends Component
{
    public ?Post $post = null;
    public ?int  $postId = null;
    public ?int $slugId = null;
    public ?string $focus_keyword = null;
    public int $nameMax = 250;
    public int $descMax = 400;
    // posts table fields
    public string $categorySearch = '';
    public string  $name = '';
    public string  $slug = '';
    public ?string $description = null;
    public ?string $content = null;

    public string $status = 'published';
    public bool   $is_featured = false;
    public ?string $image = null;
    public bool   $allow_comments = true;
    public bool   $is_breaking = false;
    public ?string $format_type = null;

    public array $category_ids = [];

    public array  $selectedTagIds   = [];
    public string $tagInput         = '';
    public array  $tagSuggestions   = [];

    // SEO meta
    public ?string $seo_title = null;
    public ?string $seo_description = null;
    public ?string $seo_image = null;
    public string  $seo_index = 'index';
    public bool $autoSeoTitle = true;

    public function mount(Post $post = null): void
    {
        // create vs edit
        if ($post && $post->exists) {
            $this->post   = $post;
            $this->postId = $post->id;
            $this->slugId = $post->slugRecord?->id;

            $this->name        = $post->name;
            $this->slug        = $post->slug ?? '';
            $this->description = $post->description;
            $this->content     = $post->content;
            $this->status      = $post->status;
            $this->is_featured = (bool) $post->is_featured;
            $this->image       = $post->image;
            $this->allow_comments = (bool) $post->allow_comments;
            $this->is_breaking    = (bool) $post->is_breaking;
            $this->format_type    = $post->format_type;

            // categories
            if (method_exists($post, 'categories')) {
                $this->category_ids = $post->categories()->pluck('categories.id')->all();
            }

            // 🔥 tags → শুধু ID গুলো অ্যারে তে
            if (method_exists($post, 'tags')) {
                $this->selectedTagIds = $post->tags->pluck('id')->all();
            }

            // SEO meta
            if (method_exists($post, 'getMeta')) {
                $seoMeta = $post->getMeta('seo_meta', []);
                $seo     = $seoMeta[0] ?? [];

                $this->seo_title       = $seo['seo_title']       ?? null;
                $this->seo_description = $seo['seo_description'] ?? null;
                $this->seo_image       = $seo['seo_image']       ?? null;
                $this->seo_index       = $seo['index']           ?? 'index';
                $this->focus_keyword   = $seo['focus_keyword']   ?? null;
            }
        } else {
            // নতুন পোস্ট
            $this->status         = 'published';
            $this->allow_comments = (bool) setting('comment_allow_new_posts', true);
            $this->is_breaking    = false;
        }

        // redirect এর পর toast success থাকলে show করো
        if (session()->has('toast_success')) {
            $this->dispatch(
                'media-toast',
                type: 'success',
                message: session('toast_success')
            );
        }
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required', 'string', 'max:255',
                Rule::unique('slugs', 'key')->ignore($this->slugId),
            ],
            'description' => ['nullable', 'string', 'max:400'],
            'content'     => ['nullable', 'string'],

            'status'      => ['required', Rule::in(['published', 'draft'])],
            'is_featured' => ['boolean'],
            'image'       => ['nullable', 'string', 'max:255'],
            'allow_comments' => ['boolean'],
            'is_breaking'    => ['boolean'],
            'format_type'    => ['nullable', 'string', 'max:30'],

            'category_ids'   => ['required', 'array', 'min:1', 'max:3'],
            'category_ids.*' => ['integer', 'exists:categories,id'],

            // tags: আইডি গুলো valid কিনা
            'selectedTagIds'   => ['array'],
            'selectedTagIds.*' => ['integer', 'exists:tags,id'],

            'seo_title'       => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string'],
            'seo_image'       => ['nullable', 'string', 'max:255'],
            'seo_index'       => ['nullable', 'string', 'max:50'],
            'focus_keyword'   => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_ids.required' => 'অন্তত ১টি ক্যাটাগরি নির্বাচন করতে হবে।',
            'category_ids.min'      => 'অন্তত ১টি ক্যাটাগরি নির্বাচন করতে হবে।',
            'category_ids.max'      => 'আপনি সর্বোচ্চ ৩টি ক্যাটাগরি নির্বাচন করতে পারবেন।',
        ];
    }

    /**
     * Category change হলে live-বার্তা দেখাতে চাইলে (optional)
     */
    public function updatedCategoryIds(): void
    {
        if (count($this->category_ids) > 3) {
            $this->addError('category_ids', 'আপনি সর্বোচ্চ ৩টি ক্যাটাগরি নির্বাচন করতে পারবেন।');

            $this->dispatch(
                'media-toast',
                type: 'warning',
                title: 'Category limit',
                message: 'আপনি সর্বোচ্চ ৩টি ক্যাটাগরি সিলেক্ট করতে পারবেন।'
            );
            return;
        }
        $this->resetErrorBag('category_ids');
    }

    public function syncSlugFromName($value) : void
    {
        $this->name = $value;
        $this->slug = SlugService::create($value, '', $this->slugId);

        if ($this->autoSeoTitle) {
            $this->seo_title = $value;
        }
    }
    public function updatedSlug($value): void
    {
        $this->slug = SlugService::create($value, '', $this->slugId);
    }

    public function updatedSeoTitle($value): void
    {
        $this->slug = SlugService::create($value, '', $this->slugId);
        $this->autoSeoTitle = trim((string) $value) === '';
    }

    public function generateSlug(): void
    {
        $this->slug = SlugService::create($this->name, '', $this->slugId);
    }

    public function toggleCategory($categoryId): void
    {
        $category = Category::with('childrenRecursive')->find($categoryId);

        if (! $category) return;

        // যদি সিলেক্ট করা থাকে → চাইল্ডগুলোও সিলেক্ট করো
        if (in_array($categoryId, $this->category_ids)) {
            $this->selectDescendants($category);
        }
        else {
            // unselect হলে সব child remove করো
            $this->unselectDescendants($category);
        }

        // max 3 check
        if (count($this->category_ids) > 3) {
            $this->dispatch('media-toast', type: 'warning', message: 'You can select maximum 3 categories only.');
            // অতিরিক্তগুলো remove করুন
            $this->category_ids = array_slice($this->category_ids, 0, 3);
        }
    }

    private function selectDescendants($category)
    {
        foreach ($category->childrenRecursive as $child) {

            if (! in_array($child->id, $this->category_ids)) {
                $this->category_ids[] = $child->id;
            }

            // child-এর child recursive
            if ($child->childrenRecursive->count()) {
                $this->selectDescendants($child);
            }
        }
    }

    private function unselectDescendants($category)
    {
        foreach ($category->childrenRecursive as $child) {

            $this->category_ids = array_diff($this->category_ids, [$child->id]);

            if ($child->childrenRecursive->count()) {
                $this->unselectDescendants($child);
            }
        }
    }

    /**
     * Tag input change হলে সাজেশন লোড
     */
    public function updatedTagInput(string $value): void
    {
        $value = trim($value);

        if ($value === '') {
            $this->tagSuggestions = [];
            return;
        }

        $this->tagSuggestions = Tag::query()
            ->where('name', 'like', '%' . $value . '%')
            ->orderBy('name')
            ->limit(8)
            ->get(['id', 'name'])
            ->map(fn ($tag) => ['id' => $tag->id, 'name' => $tag->name])
            ->toArray();
    }

    /**
     * সাজেশন লিস্ট থেকে tag নির্বাচন
     */
    public function addTag(int $tagId): void
    {
        if (! in_array($tagId, $this->selectedTagIds, true)) {
            $this->selectedTagIds[] = $tagId;
        }

        // ইনপুট / সাজেশন ক্লিয়ার
        $this->tagInput       = '';
        $this->tagSuggestions = [];
    }

    /**
     * selected chip থেকে remove
     */
    public function removeTag(int $tagId): void
    {
        $this->selectedTagIds = array_values(
            array_filter($this->selectedTagIds, fn ($id) => (int) $id !== (int) $tagId)
        );
    }

    /**
     * Enter চেপে নতুন tag তৈরি করা + select এ যোগ করা
     */
    public function createTagFromInput(): void
    {
        $name = trim($this->tagInput);

        if ($name === '') {
            return;
        }

        $slug = Str::slug($name);

        // আগে থেকে আছে কিনা দেখি
        $tag = \App\Support\SlugHelper::resolveModel($slug, Tag::class)
            ?? Tag::where('name', $name)->first();

        if (! $tag) {
            $slug = SlugService::create($slug);
            $tag = new Tag();
            $tag->name = $name;
            $tag->slug = $slug;
            $tag->status = 'published';
            $tag->save();

            ActivityLogger::log(
                Auth::user(),
                'created tag "' . $tag->name . '"',
                $tag
            );
        }

        if (! in_array($tag->id, $this->selectedTagIds, true)) {
            $this->selectedTagIds[] = $tag->id;
        }

        $this->tagInput       = '';
        $this->tagSuggestions = [];
    }

    public function save(string $redirect = 'stay')
    {
        $this->slug = SlugService::create($this->slug ?: $this->name, '', $this->slugId);

        // validation + error–গুলোকে toast এও দেখাতে চাইলে try/catch করতে পারো (আগে দেখিয়েছি)
        $this->validate();

        $user = Auth::user();
        $isNew = ! $this->postId;

        if ($this->postId) {
            $post = Post::findOrFail($this->postId);
        } else {
            $post = new Post();
            $post->author_id   = $user?->id;
            $post->author_type = $user ? get_class($user) : null;
        }

        $post->name           = $this->name;
        $post->slug           = $this->slug;
        $post->description    = $this->description;
        $post->content        = $this->content;
        $post->status         = $this->status;
        $post->is_featured    = $this->is_featured ? 1 : 0;
        $post->image          = $this->image;
        $post->allow_comments = $this->allow_comments ? 1 : 0;
        $post->is_breaking    = $this->is_breaking ? 1 : 0;
        $post->format_type    = $this->format_type;

        $analysis = SeoAnalyzer::analyzeContent(
            title: $this->seo_title ?: ($this->name ?? ''),
            description: $this->seo_description ?: ($this->description ?? ''),
            slug: $this->slug,
            contentHtml: (string) ($this->content ?? ''),
            focusKeyword: $this->focus_keyword
        );
        $post->seo_score = $analysis['score'];

        $post->save();

        // categories sync
        if (method_exists($post, 'categories')) {
            $post->categories()->sync($this->category_ids);
        }

        // tags sync (selectedTagIds থেকে)
        if (method_exists($post, 'tags')) {
            $post->tags()->sync($this->selectedTagIds);
        }

        // SEO meta
        if (method_exists($post, 'setMeta')) {
            $post->setMeta('seo_meta', [[
                'seo_title'       => $this->seo_title,
                'seo_description' => $this->seo_description,
                'seo_image'       => $this->seo_image,
                'index'           => $this->seo_index ?: 'index',
                'focus_keyword'   => $this->focus_keyword,
            ]]);
        }

        $this->post   = $post;
        $this->postId = $post->id;
        $this->slugId = $post->slugRecord?->id;

        ActivityLogger::log(
            $user,
            ($isNew ? 'created' : 'updated') . ' post "' . $post->name . '"',
            $post
        );

        // redirect এর পর toast show
        $this->dispatch('media-toast', title: 'success', message: 'Post saved successfully.');

        if ($redirect === 'exit') {
            return redirect()->route('blogs.posts.index');
        }

        return redirect()->route('blogs.posts.edit', $post->id);
    }

    public function getSeoAnalysisProperty(): array
    {
        // fake Post instance (DB তে save হচ্ছে না)
        $dummy = new \App\Models\Post();

        $dummy->name        = $this->name;
        $dummy->description = $this->description;
        $dummy->slug        = $this->slug;
        $dummy->content     = $this->content;

        // meta override array → analyzeSeo() এ পাঠাবো
        $meta = [
            'seo_title'       => $this->seo_title,
            'seo_description' => $this->seo_description,
            'seo_image'       => $this->seo_image,
            'index'           => $this->seo_index,
            'focus_keyword'   => $this->focus_keyword,
        ];

        return $dummy->analyzeSeo($this->focus_keyword, $meta);
    }

    public function render()
    {
        $rootCategories = Category::with('childrenRecursive')
            ->whereNull('parent_id')
            ->when($this->categorySearch, function ($q) {
                $search = $this->categorySearch;

                $q->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%'.$search.'%')
                        ->orWhereHas('slugRecord', function ($slugQuery) use ($search) {
                            $slugQuery->where('key', 'like', '%'.$search.'%');
                        });
                });
            })
            ->orderBy('name')
            ->get();

        return view('livewire.admin.posts.post-form', [
            'rootCategories' => $rootCategories,
            'baseUrl'        => config('app.url'),
        ])->layout('components.layouts.app', [
            'title' => $this->postId ? 'Edit post' : 'Create a new post',
        ]);
    }
}
