<?php

namespace App\Livewire\Frontend;

use App\Models\Admin\Page;
use App\Models\Post;
use App\Support\PermalinkManager;
use App\Support\SlugHelper;
use App\Support\Seo;
use Livewire\Component;

class PageShow extends Component
{
    public Page $page;

    public function mount(Page|string $page)
    {
        if (! $page instanceof Page) {
            $slug = (string) $page;
            $page = SlugHelper::resolveModel($slug, Page::class);

            if (! $page) {
                if (! PermalinkManager::pagePrefixEnabled()
                    && PermalinkManager::routeDefinition()['template'] === '%postname%') {
                    $post = SlugHelper::resolveModel($slug, Post::class);

                    if ($post && in_array($post->status, ['published', 'publish'], true)) {
                        return redirect()->route('posts.show', ['post' => $slug]);
                    }
                }

                abort(404);
            }
        }

        abort_if($page->status !== 'published', 404);

        $this->page = $page;
    }

    public function render()
    {
        return view('livewire.frontend.page-show')
            ->layout('components.layouts.frontend.app', [
                'title' => $this->page->name,
                'seo' => Seo::forPage($this->page),
            ]);
    }
}
