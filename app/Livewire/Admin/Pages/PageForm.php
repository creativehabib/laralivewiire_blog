<?php

namespace App\Livewire\Admin\Pages;

use App\Models\Admin\Page;
use App\Models\Category;
use App\Models\Post;
use App\Rules\UniqueSlugAcrossContent;
use App\Support\ActivityLogger;
use App\Support\SeoAnalyzer;
use App\Support\SlugService;
use App\Support\PermalinkManager;
use App\Livewire\Concerns\HandlesSlug;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class PageForm extends Component
{
    use HandlesSlug;

    public ?Page $page = null;
    public ?int $pageId = null;

    // Fields
    public string $name = '';
    public string $description = '';
    public string $content = '';
    public string $status = 'published';
    public ?string $template = null;
    public ?string $image = null;

    // SEO (meta_boxes)
    public ?string $seo_title = null;
    public ?string $seo_description = null;
    public string  $seo_index = 'index';
    public ?string $seo_image = null;
    public string $focus_keyword = '';
    public bool $autoSeoTitle = true;

    // UI helpers
    public int $nameMax = 250;
    public int $descMax = 400;

    public function mount(?int $pageId = null): void
    {
        if ($pageId) {
            $page = Page::query()->withTrashed()->findOrFail($pageId);
            $this->page   = $page;
            $this->pageId = $page->id;
            $this->slugId = $page->slugRecord?->id;

            $this->name        = (string) $page->name;
            $this->slug        = (string) $page->slug;
            $this->description = (string) ($page->description ?? '');
            $this->content     = (string) ($page->content ?? '');
            $this->status      = (string) ($page->status ?? 'published');
            $this->template    = $page->template ?? null;
            $this->image       = $page->image ?? null;

            // load seo_meta if exists (HasMetaBoxes style)
            if (method_exists($page, 'getMeta')) {
                $meta = $page->getMeta('seo_meta', []);
                $meta = $meta[0] ?? $meta;

                $this->seo_title       = (string) ($meta['seo_title'] ?? '');
                $this->seo_description = (string) ($meta['seo_description'] ?? '');
                $this->seo_index       = (string) ($meta['index'] ?? 'index');
                $this->seo_image       = $meta['seo_image'] ?? null;
                $this->focus_keyword   = (string) ($meta['focus_keyword'] ?? '');
            }
        }
    }

    protected function rules(): array
    {
        $id = $this->pageId ?? 'NULL';

        $pagePrefixEnabled = PermalinkManager::pagePrefixEnabled();

        $crossRule = $pagePrefixEnabled ? [] : [
            new UniqueSlugAcrossContent(
                models: [Post::class, Category::class],
                ignore: $this->page
            ),
        ];

        return [
            'name'        => ['required', 'string', 'max:250'],
            'slug' => array_merge([
                'required',
                'string',
                'max:255',
                Rule::unique('slugs', 'key')->ignore($this->slugId),
            ], $crossRule),
            'description' => ['nullable', 'string', 'max:400'],
            'content'     => ['nullable', 'string'],
            'status'      => ['required', 'in:published,draft'],
            'template'    => ['nullable', 'string', 'max:120'],
            'image'       => ['nullable', 'string', 'max:2048'],

            // SEO
            'seo_title'       => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:400'],
            'seo_image'       => ['nullable', 'string', 'max:2048'],
            'focus_keyword'   => ['nullable', 'string', 'max:120'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Page name is required.',
            'slug.required' => 'Slug is required.',
            'slug.unique'   => 'This slug already exists.',
            'status.in'     => 'Status must be published or draft.',
        ];
    }

    public function updatedName($value): void
    {
        $this->slug = $this->generateSlugValue((string) $value);
        if ($this->autoSeoTitle) {
            $this->seo_title = $value;
        }
    }
    public function getSeoAnalysisProperty(): array
    {
        return SeoAnalyzer::analyzeContent(
            title: $this->seo_title ?: ($this->name ?? ''),
            description: $this->seo_description ?: ($this->description ?? ''),
            slug: $this->slug ?? '',
            contentHtml: (string) ($this->content ?? ''),
            focusKeyword: $this->focus_keyword,
            options: []
        );
    }


    public function save(string $redirect = 'stay')
    {
        $user = Auth::user();
        $isNew = ! $this->pageId;

        if ($this->pageId) {
            $page = Page::query()->withTrashed()->findOrFail($this->pageId);
        } else {
            $page = new Page();
            $page->author_id   = $user?->id;
            $page->author_type = $user ? get_class($user) : null;
        }

        if (! $isNew && $this->slug === $page->slug && $this->name !== $page->name) {
            $this->slug = $this->generateSlugValue((string) $this->name);
        }

        $this->slug = SlugService::create($this->slug ?: $this->name, '', $this->slugId);

        $this->validate();

        $page->name        = $this->name;
        $page->slug        = $this->slug;
        $page->description = $this->description;
        $page->content     = $this->content;
        $page->status      = $this->status;
        $page->template    = $this->template;
        $page->image       = $this->image;

        $page->save();

        $this->slugId = $page->slugRecord?->id;

        // Save SEO meta (HasMetaBoxes)

        $overrideMeta = [
            'seo_title'       => $this->seo_title ?: $this->name,
            'seo_description' => $this->seo_description ?: $this->description,
            'seo_image'       => $this->seo_image ?: $this->image,
            'index'           => $this->seo_index ?: 'index',
            'focus_keyword'   => $this->focus_keyword ?: null,
        ];
        if (method_exists($page, 'setMeta')) {
            $page->setMeta('seo_meta', [$overrideMeta]);
        }

        // SEO SCORE
        $analysis = SeoAnalyzer::analyze($page, $this->focus_keyword, $overrideMeta);
        $page->seo_score = $analysis['score'];
        $page->saveQuietly();

        $this->pageId = $page->id;

        ActivityLogger::log(
            $user,
            ($isNew ? 'created' : 'updated') . ' page "' . $page->name . '"',
            $page
        );

        $this->dispatch('media-toast', type: 'success', message: 'Page Saved successfully.');

        // Redirect (flash থাকবে)
        if ($redirect === 'exit') {
            return redirect()->route('admins.pages.index');
        }

        return redirect()->route('admins.pages.edit', ['pageId' => $page->id]);
    }


    public function render()
    {
        return view('livewire.admin.pages.page-form',[
            'baseUrl'        => config('app.url'),
        ])->layout('components.layouts.app', [
                'title' => $this->pageId ? 'Edit Page' : 'Create Page',
            ]);
    }
}
