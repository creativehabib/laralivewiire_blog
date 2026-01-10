<?php

namespace App\Livewire\Frontend;

use App\Models\Admin\Page;
use App\Models\Post;
use App\Support\Seo;
use Livewire\Component;
use Livewire\WithPagination;

class PageShow extends Component
{
    use WithPagination;

    public Page $page;
    public array $builderState = [];

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

    public function render()
    {
        return view('livewire.frontend.page-show', [
            'builderSections' => $this->buildBuilderSections(),
        ])
            ->layout('components.layouts.frontend.app', [
                'title' => $this->page->name,
                'seo' => Seo::forPage($this->page),
            ]);
    }

    protected function buildBuilderSections(): array
    {
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

                        if ($days > 0) {
                            $query->where('created_at', '>=', now()->subDays($days));
                        }

                        if ($sort === 'popular') {
                            $query->orderBy('views', $order);
                        } else {
                            $query->orderBy('created_at', $order);
                        }

                        $query->skip($offset);

                        if ($pagination === 'enable') {
                            $blockId = (string) ($block['id'] ?? '0');
                            $pageName = 'block_' . preg_replace('/[^A-Za-z0-9_]/', '_', $blockId);
                            $posts = $query->paginate($count, ['*'], $pageName);
                        } else {
                            $posts = $query->take($count)->get();
                        }

                        return array_merge($block, [
                            'settings' => $settings,
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
