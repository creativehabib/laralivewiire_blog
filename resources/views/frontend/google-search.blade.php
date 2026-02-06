@php
    $title = $query !== '' ? "Search Results for: {$query}" : 'Search Results';
@endphp

<x-layouts.frontend.app :title="$title" :seo="$seo ?? null">
    <section class="container px-4 py-8">
        <div class="max-w-5xl mx-auto">
            <h1 class="text-4xl md:text-5xl font-bold text-slate-900 dark:text-white mb-6">
                {{ $title }}
            </h1>

            @if($searchEngineId === '')
                <div class="rounded-xl border border-amber-200 bg-amber-50 text-amber-800 px-4 py-3 dark:border-amber-900/50 dark:bg-amber-950/40 dark:text-amber-200">
                    {{ __('Google Search Engine ID is not configured yet. Please add it from Theme Options → SEO & Social.') }}
                </div>
            @else
                <div class="gcse-searchbox-only" data-resultsUrl="{{ route('google.search') }}"></div>
                <div class="mt-6">
                    <div class="gcse-searchresults-only"></div>
                </div>
            @endif
        </div>
    </section>
</x-layouts.frontend.app>
