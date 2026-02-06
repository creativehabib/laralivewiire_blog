<div
    x-data="{ open: false }"
    class="relative {{ $wrapperClass }}"
    @click.outside="open = false; $wire.clear()"
>
    <label class="sr-only" for="{{ $inputId }}">{{ __('Search') }}</label>
    <div class="flex items-center justify-end">
        <button
            type="button"
            class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 text-slate-500 shadow-sm transition hover:bg-slate-100 hover:text-slate-700 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800"
            @click="open = true; $nextTick(() => $refs.searchInput?.focus())"
            x-show="!open"
            x-cloak
            aria-label="{{ __('Search') }}"
        >
            <i class="fa-solid fa-magnifying-glass text-sm"></i>
        </button>
    </div>
    <div class="relative" x-show="open" x-transition x-cloak>
        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
            <i class="fa-solid fa-magnifying-glass"></i>
        </span>
        <input
            id="{{ $inputId }}"
            x-ref="searchInput"
            type="search"
            wire:model.live.debounce.300ms="query"
            wire:keydown.escape="clear"
            x-on:keydown.escape.stop="open = false"
            autocomplete="off"
            placeholder="{{ $placeholder }}"
            class="w-full rounded-full border border-slate-200 bg-white py-2.5 pl-10 pr-10 text-sm text-slate-700 shadow-sm placeholder:text-slate-400 focus:border-primary focus:ring-primary/40 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 {{ $inputClass }}"
        />
        @if($query !== '')
            <button
                type="button"
                wire:click="clear"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200"
                aria-label="{{ __('Clear search') }}"
            >
                <i class="fa-solid fa-xmark"></i>
            </button>
        @endif
    </div>

    <div x-show="open" x-transition x-cloak>
        <p class="mt-2 text-xs text-slate-500">
            {{ $activeEngine === 'google' ? __('Searching with Google.') : __('Searching this site.') }}
        </p>
        @if($term !== '' && mb_strlen($term) >= 1)
            @if($activeEngine === 'google')
                <div class="absolute left-0 right-0 top-full z-50 mt-2 rounded-2xl border border-slate-200 bg-white px-4 py-4 text-sm text-slate-600 shadow-xl dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                        {{ __('Google search') }}
                    </p>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                        {{ __('Continue your search on Google for') }} "{{ $term }}"
                    </p>
                    <a
                        class="mt-3 inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-600 shadow-sm transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800"
                        href="https://www.google.com/search?q={{ urlencode($term) }}"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        <i class="fa-brands fa-google text-sm"></i>
                        {{ __('Search on Google') }}
                    </a>
                </div>
            @else
                <div class="absolute left-0 right-0 top-full z-50 mt-2 rounded-2xl border border-slate-200 bg-white shadow-xl dark:border-slate-700 dark:bg-slate-900">
                    <div class="px-4 py-2 text-xs font-semibold uppercase tracking-wide text-slate-400">
                        {{ __('Search results') }}
                    </div>
                    <div wire:loading.flex wire:target="query" class="px-4 pb-2 text-xs text-slate-500">
                        {{ __('Searching...') }}
                    </div>
                    @if($results->isEmpty())
                        <div class="px-4 pb-4 text-sm text-slate-500">
                            {{ __('No results found for') }} "{{ $term }}"
                        </div>
                    @else
                        <ul class="max-h-80 overflow-y-auto">
                            @foreach($results as $post)
                                <li class="px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-800">
                                    <a href="{{ post_permalink($post) }}" class="flex items-center gap-3">
                                        <img
                                            src="{{ $post->image_url }}"
                                            alt="{{ $post->name }}"
                                            class="h-14 w-20 rounded-lg object-cover"
                                            loading="lazy"
                                        />
                                        <div class="flex-1">
                                            <p class="text-sm font-semibold text-slate-800 line-clamp-2 dark:text-slate-100">
                                                {{ $post->name }}
                                            </p>
                                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                                {{ $post->created_at?->diffForHumans() }}
                                            </p>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endif
        @elseif($term !== '')
            <div class="absolute left-0 right-0 top-full z-50 mt-2 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-500 shadow-xl dark:border-slate-700 dark:bg-slate-900">
                {{ __('Type at least 1 character to search.') }}
            </div>
        @endif
    </div>
</div>
