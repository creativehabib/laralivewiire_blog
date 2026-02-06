@php
    $title = $query !== '' ? "Search Results for: {$query}" : 'Search Results';
@endphp

<x-layouts.frontend.app :title="$title" :seo="$seo ?? null">
    <main class="container px-4 py-8 md:py-10 grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 typography">
        <article class="lg:col-span-8 bg-white dark:bg-slate-800 rounded-xl shadow-sm p-4 md:p-6 transition-all duration-200 hover:shadow-md">
            <nav class="text-xs text-gray-500 dark:text-slate-400 mb-3 flex items-center gap-1">
                <a href="{{ route('home') }}" class="hover:text-primary-dark dark:hover:text-primary-light">হোম</a>
                <span>/</span>
                <span class="text-primary-dark dark:text-primary-light">সার্চ রেজাল্ট</span>
            </nav>

            <h1 class="text-3xl md:text-5xl font-bold text-slate-900 dark:text-white mb-6">
                {{ $title }}
            </h1>

            @if($searchEngineId === '')
                <div class="rounded-xl border border-amber-200 bg-amber-50 text-amber-800 px-4 py-3 dark:border-amber-900/50 dark:bg-amber-950/40 dark:text-amber-200">
                    {{ __('Google Search Engine ID is not configured yet. Please add it from Theme Options → SEO & Social.') }}
                </div>
            @else
                <div class="gcse-search"></div>
            @endif
        </article>

        <aside class="lg:col-span-4 space-y-6 lg:sticky lg:top-32 self-start">
            <section class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 p-5">
                <h2 class="text-base font-bold text-slate-800 dark:text-slate-100 mb-4">সর্বশেষ খবর</h2>

                <div class="space-y-3 text-sm">
                    @forelse($latestPosts as $post)
                        <article class="flex gap-3">
                            <a href="{{ post_permalink($post) }}" class="shrink-0">
                                <img src="{{ $post->image_url }}" class="w-20 h-14 object-cover rounded-md" alt="{{ $post->name }}">
                            </a>
                            <div class="flex-1">
                                <a href="{{ post_permalink($post) }}" class="font-semibold leading-snug hover:text-primary-dark dark:hover:text-primary-light line-clamp-2">
                                    {{ $post->name }}
                                </a>
                                <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">{{ $post->created_at?->diffForHumans() }}</div>
                            </div>
                        </article>
                    @empty
                        <p class="text-sm text-slate-500 dark:text-slate-300">কোনো খবর পাওয়া যায়নি।</p>
                    @endforelse
                </div>
            </section>

            <section class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 p-5">
                <h2 class="text-base font-bold text-slate-800 dark:text-slate-100 mb-4">জনপ্রিয় খবর</h2>

                <ul class="space-y-3 text-sm">
                    @forelse($popularPosts as $post)
                        <li>
                            <a href="{{ post_permalink($post) }}" class="font-semibold leading-snug hover:text-primary-dark dark:hover:text-primary-light line-clamp-2">
                                {{ $post->name }}
                            </a>
                            <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">
                                <i class="fa-regular fa-eye"></i> {{ number_format($post->views ?? 0) }}
                            </div>
                        </li>
                    @empty
                        <li class="text-slate-500 dark:text-slate-300">কোনো জনপ্রিয় খবর নেই।</li>
                    @endforelse
                </ul>
            </section>

            <x-ad-unit placement="sidebar" />
        </aside>
    </main>
</x-layouts.frontend.app>
