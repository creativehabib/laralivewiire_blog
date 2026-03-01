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
    public bool $useGoogleSearch = false;

    public function mount(): void
    {
        $this->useGoogleSearch = trim((string) setting('google_search_engine_id', '')) !== '';
    }

    public function search(string $value): void
    {
        $this->query = trim($value);
    }

    public function clear(): void
    {
        $this->reset('query');
    }

    public function goToSearchResultsFromInput(string $value = '')
    {
        $this->query = trim($value);

        return $this->goToSearchResults();
    }

    public function goToSearchResults()
    {
        if (! $this->useGoogleSearch) {
            return null;
        }

        $term = trim($this->query);

        if ($term === '') {
            return null;
        }

        return redirect()->route('google.search', ['q' => $term]);
    }

    public function render()
    {
        $term = trim($this->query);
        $results = collect();

        if (! $this->useGoogleSearch && $term !== '' && mb_strlen($term) >= 1) {
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
            'useGoogleSearch' => $this->useGoogleSearch,
        ]);
    }
}
