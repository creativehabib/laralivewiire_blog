<?php

namespace App\Livewire\Frontend;

use App\Models\Admin\Page;
use App\Support\Seo;

class PostsIndex extends Homepage
{
    public ?Page $page = null;

    public function mount(): void
    {
        parent::mount();

        $pageId = (int) setting('posts_page_id');
        $this->page = $pageId ? Page::query()->published()->find($pageId) : null;
    }

    public function render()
    {
        $title = $this->page?->name ?? 'Latest posts';
        $seo = $this->page ? Seo::forPage($this->page) : Seo::forHomepage(['title' => $title]);

        return view('livewire.frontend.homepage')
            ->layout('components.layouts.frontend.app', [
                'title' => $title,
                'seo' => $seo,
            ]);
    }
}
