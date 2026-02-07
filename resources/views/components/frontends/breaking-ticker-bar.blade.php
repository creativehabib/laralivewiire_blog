@php
    use App\Models\Post;

    $breakingEnabled = filter_var(setting('breaking_news_enabled', true), FILTER_VALIDATE_BOOLEAN);
    $breakingLimit = max(1, (int) setting('show_more_breaking_news', 10));
    $breakingCategoryId = setting('breaking_news_category_id');
@endphp

@if($breakingEnabled)
    @php
        $breakingQuery = Post::query()
            ->published()
            ->where('is_breaking', true)
            ->latest('created_at');

        if ($breakingCategoryId) {
            $breakingQuery->whereHas('categories', fn ($query) => $query->where('categories.id', $breakingCategoryId));
        }

        $breakingTicker = $breakingQuery->take($breakingLimit)->get();

        if ($breakingTicker->isEmpty()) {
            $breakingTicker = Post::query()
                ->published()
                ->latest('created_at')
                ->take($breakingLimit)
                ->get();
        }
    @endphp

    <x-frontends.breaking-ticker :breakingTicker="$breakingTicker" />
@endif
