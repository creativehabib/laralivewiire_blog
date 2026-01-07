<?php

namespace App\Http\Controllers\Frontend;

use App\Livewire\Frontend\CategoryPage;
use App\Livewire\Frontend\PageShow;
use App\Livewire\Frontend\SinglePost;
use App\Livewire\Frontend\TagPage;
use App\Models\Admin\Page;
use App\Models\Admin\Tag;
use App\Models\Category;
use App\Models\Post;
use App\Models\Slug;
use Illuminate\Http\Response;
use Livewire\Livewire;

class SlugRouterController
{
    public function __invoke(string $slug): Response
    {
        $record = Slug::query()
            ->where('key', $slug)
            ->first();

        abort_if(! $record, 404);

        $reference = $record->reference;

        if ($reference instanceof Category) {
            return $this->renderComponent(CategoryPage::class, ['category' => $reference]);
        }

        if ($reference instanceof Tag) {
            return $this->renderComponent(TagPage::class, ['tag' => $reference]);
        }

        if ($reference instanceof Page) {
            return $this->renderComponent(PageShow::class, ['page' => $reference]);
        }

        if ($reference instanceof Post) {
            return $this->renderComponent(SinglePost::class);
        }

        abort(404);
    }

    protected function renderComponent(string $component, array $params = []): Response
    {
        return response(Livewire::mount($component, $params)->html());
    }
}
