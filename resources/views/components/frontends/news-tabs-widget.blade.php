@props([
    'latestPosts' => collect(),
    'popularPosts' => collect(),
    'latestTabLabel' => 'সর্বশেষ খবর',
    'popularTabLabel' => 'জনপ্রিয় খবর',
    'latestEmptyText' => 'সর্বশেষ কোনো খবর নেই',
    'popularEmptyText' => 'জনপ্রিয় খবর পাওয়া যায়নি',
])

<section {{ $attributes->merge(['class' => 'bg-white dark:bg-slate-800 rounded-xl shadow-sm p-4']) }} data-news-tabs>
    <div class="flex items-center gap-3 border-b border-slate-200 dark:border-slate-700 mb-4 text-sm font-semibold">
        <button
            class="py-2 px-3 rounded-t-md border-b-2 border-primary-dark text-primary-dark dark:text-primary-light"
            type="button"
            data-news-tab-button="latest">
            {{ $latestTabLabel }}
        </button>
        <button
            class="py-2 px-3 rounded-t-md border-b-2 border-transparent text-slate-600 dark:text-slate-300 hover:text-primary-dark dark:hover:text-primary-light"
            type="button"
            data-news-tab-button="popular">
            {{ $popularTabLabel }}
        </button>
    </div>

    <div>
        <div class="space-y-3" data-news-tab-panel="latest">
            @forelse($latestPosts as $post)
                <article class="flex gap-3 hover:bg-slate-50 dark:hover:bg-slate-700/70 p-2 rounded-lg">
                    <a href="{{ post_permalink($post) }}" wire:navigate>
                        <img src="{{ the_thumbnail($post, 240, 160) }}" loading="lazy" width="240" height="160" class="w-24 h-16 rounded object-cover" alt="{{ $post->name }}">
                    </a>
                    <div class="flex-1">
                        <h3 class="text-sm font-semibold leading-snug hover:text-primary-dark dark:hover:text-primary-light">{{ $post->name }}</h3>
                        <div class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-2">
                            <span>{{ $post->created_at?->diffForHumans() }}</span>
                            <span>•</span>
                            <span><i class="fa-regular fa-eye"></i> {{ number_format($post->views ?? 0) }} ভিউ</span>
                        </div>
                    </div>
                </article>
            @empty
                <p class="text-sm text-slate-500">{{ $latestEmptyText }}</p>
            @endforelse
        </div>

        <div class="space-y-3 hidden" data-news-tab-panel="popular">
            @forelse($popularPosts as $post)
                <article class="flex gap-3 hover:bg-slate-50 dark:hover:bg-slate-700/70 p-2 rounded-lg">
                    <a href="{{ post_permalink($post) }}" wire:navigate>
                        <img src="{{ the_thumbnail($post, 240, 160) }}" loading="lazy" width="240" height="160" class="w-24 h-16 rounded object-cover" alt="{{ $post->name }}">
                    </a>
                    <div class="flex-1">
                        <h3 class="text-sm font-semibold leading-snug hover:text-primary-dark dark:hover:text-primary-light">{{ $post->name }}</h3>
                        <span class="text-xs text-slate-500 dark:text-slate-400">{{ number_format($post->views ?? 0) }} ভিউ</span>
                    </div>
                </article>
            @empty
                <p class="text-sm text-slate-500">{{ $popularEmptyText }}</p>
            @endforelse
        </div>
    </div>
</section>
