<?php

namespace App\Livewire\Frontend;

use App\Models\Admin\Page;
use App\Models\Post;
use App\Support\Seo;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class PageShow extends Component
{
    use WithPagination;
    use WithoutUrlPagination;

    public Page $page;
    public bool $ready = false;
    public array $builderState = [];
    public $sidebarLatest;
    public $sidebarPopular;

    public function mount(Page $page): void
    {
        abort_if($page->status !== 'published', 404);

        $this->page = $page;
        if (method_exists($page, 'getMeta')) {
            $builderMeta = $page->getMeta('builder_state', []);
            $builderMeta = $builderMeta[0] ?? $builderMeta;
            $this->builderState = is_array($builderMeta) ? $builderMeta : [];
        }
    }
    public function loadReady(): void
    {
        $this->sidebarLatest = Post::query()
            ->published()
            ->latest()
            ->take(5)
            ->get();

        $this->sidebarPopular = Post::query()
            ->published()
            ->orderByDesc('views')
            ->latest('id')
            ->take(5)
            ->get();

        $this->ready = true;
    }
    public function render()
    {
        return view('livewire.frontend.page-show', [
            'builderSections' => $this->buildBuilderSections(),
            'sidebarLatest' => $this->sidebarLatest ?? collect(),
            'sidebarPopular' => $this->sidebarPopular ?? collect(),
        ])
            ->layout('components.layouts.frontend.app', [
                'title' => $this->page->name,
                'seo' => Seo::forPage($this->page),
            ]);
    }

    protected function buildBuilderSections(): array
    {
        if (! $this->ready) {
            return [];
        }

        $sections = $this->builderState['sections'] ?? [];

        if (! is_array($sections)) {
            return [];
        }

        return collect($sections)
            ->map(function (array $section) {
                $blocks = collect($section['blocks'] ?? [])
                    ->map(function (array $block) {
                        $settings = $block['settings'] ?? [];
                        $categoryIds = collect($settings['categories'] ?? [])
                            ->map(fn ($id) => (int) $id)
                            ->filter()
                            ->values()
                            ->all();
                        $tagNames = collect(explode(',', (string) ($settings['tags'] ?? '')))
                            ->map(fn (string $tag) => trim($tag))
                            ->filter()
                            ->values()
                            ->all();
                        $excludeIds = collect(explode(',', (string) ($settings['exclude'] ?? '')))
                            ->map(fn (string $id) => (int) trim($id))
                            ->filter()
                            ->values()
                            ->all();
                        $count = max(1, (int) ($settings['count'] ?? 5));
                        $offset = max(0, (int) ($settings['offset'] ?? 0));
                        $days = (int) ($settings['days'] ?? 0);
                        $order = ($settings['order'] ?? 'desc') === 'asc' ? 'asc' : 'desc';
                        $sort = $settings['sort'] ?? 'recent';
                        $pagination = $settings['pagination'] ?? 'disable';
                        if ($pagination === 'enable') {
                            $pagination = 'numeric';
                        }

                        $query = Post::query()
                            ->published()
                            ->with(['categories', 'tags']);

                        if ($categoryIds) {
                            $query->whereHas('categories', function ($builderQuery) use ($categoryIds) {
                                $builderQuery->whereIn('categories.id', $categoryIds);
                            });
                        }

                        if ($tagNames) {
                            $query->whereHas('tags', function ($builderQuery) use ($tagNames) {
                                $builderQuery->whereIn('tags.name', $tagNames);
                            });
                        }

                        if (! empty($settings['trending'])) {
                            $query->where('is_featured', true);
                        }

                        if ($excludeIds) {
                            $query->whereNotIn('id', $excludeIds);
                        }

                        if ($sort === 'most_viewed_7_days') {
                            $days = 7;
                        }

                        if ($days > 0) {
                            $query->where('created_at', '>=', now()->subDays($days));
                        }

                        switch ($sort) {
                            case 'random':
                                $query->inRandomOrder();
                                break;
                            case 'featured':
                                $query->where('is_featured', true)
                                    ->orderBy('created_at', $order);
                                break;
                            case 'last_modified':
                                $query->orderBy('updated_at', $order);
                                break;
                            case 'most_commented':
                                $query->withCount('comments')
                                    ->orderBy('comments_count', $order);
                                break;
                            case 'alphabetical':
                                $query->orderBy('name', $order);
                                break;
                            case 'popular':
                            case 'most_viewed':
                            case 'most_viewed_7_days':
                                $query->orderBy('views', $order);
                                break;
                            case 'recent':
                            default:
                                $query->orderBy('created_at', $order);
                                break;
                        }

                        $query->skip($offset);

                        if ($pagination !== 'disable') {
                            $blockId = (string) ($block['id'] ?? '0');
                            $pageName = 'block_' . preg_replace('/[^A-Za-z0-9_]/', '_', $blockId);
                            $posts = $query->paginate($count, ['*'], $pageName);
                        } else {
                            $posts = $query->take($count)->get();
                        }

                        return array_merge($block, [
                            'settings' => $settings,
                            'pagination_mode' => $pagination,
                            'page_name' => $pageName ?? null,
                            'posts' => $posts,
                        ]);
                    })
                    ->all();

                return array_merge($section, [
                    'blocks' => $blocks,
                ]);
            })
            ->all();
    }
}
