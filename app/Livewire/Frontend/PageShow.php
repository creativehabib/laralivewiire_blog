<?php

namespace App\Livewire\Frontend;

use App\Models\Admin\Page;
use App\Support\Seo;
use Livewire\Component;

class PageShow extends Component
{
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
        return view('livewire.frontend.page-show')
            ->layout('components.layouts.frontend.app', [
                'title' => $this->page->name,
                'seo' => Seo::forPage($this->page),
            ]);
    }
}
