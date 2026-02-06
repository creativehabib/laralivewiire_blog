<?php

namespace App\Livewire\Frontend;

use App\Models\Post;
use Livewire\Component;

class LiveSearch extends Component
{
    public string $query = '';
    public int $limit = 6;
    public string $wrapperClass = '';
    public string $inputClass = '';
    public string $placeholder = 'খুঁজুন...';
    public string $inputId = 'frontend-live-search-desktop';
    public string $engine = 'default';

    public function mount(): void
    {
        $engine = (string) setting('search_engine', 'default');
        $this->engine = in_array($engine, ['default', 'google'], true) ? $engine : 'default';
    }

    public function updatedQuery(): void
    {
        $this->query = trim($this->query);
    }

    public function clear(): void
    {
        $this->reset('query');
    }

    public function render()
    {
        $term = trim($this->query);
        $results = collect();

        if ($this->engine === 'default' && $term !== '' && mb_strlen($term) >= 1) {
            $results = Post::query()
                ->published()
                ->where('name', 'like', "%{$term}%")
                ->latest('created_at')
                ->limit($this->limit)
                ->get();
        }

        return view('livewire.frontend.live-search', [
            'results' => $results,
            'term' => $term,
            'activeEngine' => $this->engine,
        ]);
    }
}
