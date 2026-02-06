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
    public string $engineMode = 'choice';

    public function mount(): void
    {
        $engineMode = (string) setting('search_engine', 'choice');
        $this->engineMode = in_array($engineMode, ['default', 'google', 'choice'], true)
            ? $engineMode
            : 'choice';

        if ($this->engineMode !== 'choice') {
            $this->engine = $this->engineMode;
        }
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

        $activeEngine = $this->engineMode === 'choice' ? $this->engine : $this->engineMode;

        if ($activeEngine === 'default' && $term !== '' && mb_strlen($term) >= 1) {
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
            'activeEngine' => $activeEngine,
        ]);
    }
}
