<div
    x-data="{ open: false, localQuery: @js($query) }"
    class="relative {{ $wrapperClass }}"
    @click.outside="open = false"
>
    <label class="sr-only" for="{{ $inputId }}">{{ __('Search') }}</label>
    <div class="flex items-center justify-end">
        <button
            type="button"
            class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 text-slate-500 shadow-sm transition hover:bg-slate-100 hover:text-slate-700 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800"
            @click="open = !open; $nextTick(() => open && $refs.searchInput?.focus())"
            aria-label="{{ __('Search') }}"
        >
            <i class="fa-solid fa-magnifying-glass text-sm"></i>
        </button>
    </div>
    <div class="absolute right-0 top-full z-50 mt-2 w-[280px] sm:w-[320px]" x-show="open" x-transition x-cloak>
        <div class="relative rounded-lg border border-slate-200 bg-white p-2 shadow-xl dark:border-slate-700 dark:bg-slate-900">
            <span class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400">
                <i class="fa-solid fa-magnifying-glass"></i>
            </span>
            <input
                id="{{ $inputId }}"
                x-ref="searchInput"
                x-model="localQuery"
                @input.debounce.300ms="$wire.search(localQuery)"
                type="search"
                @if($useGoogleSearch)
                    wire:keydown.enter="goToSearchResultsFromInput($event.target.value)"
                @endif
                x-on:keydown.escape.stop="open = false"
                autocomplete="off"
                placeholder="{{ $placeholder }}"
                class="w-full rounded-md border border-slate-300 bg-white py-2 pl-10 pr-20 text-sm text-slate-700 placeholder:text-slate-400 focus:border-primary focus:ring-primary/40 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 {{ $inputClass }}"
            />

            <div class="absolute right-4 top-1/2 -translate-y-1/2 flex items-center gap-1">
                @if($useGoogleSearch && $query !== '')
                    <button
                        type="button"
                        wire:click="goToSearchResults"
                        class="inline-flex h-7 w-7 items-center justify-center rounded-md text-slate-500 hover:bg-slate-100 hover:text-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
                        aria-label="{{ __('View full search results') }}"
                    >
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                @endif

                <button
                    type="button"
                    @if($query !== '')
                        wire:click="clear"
                    @endif
                    x-on:click="localQuery = ''; open = false"
                    class="inline-flex h-7 w-7 items-center justify-center rounded-md bg-slate-900 text-white hover:bg-slate-700 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-slate-300"
                    aria-label="{{ __('Close search') }}"
                >
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>
    </div>

    @if(! $useGoogleSearch)
        <div x-show="open" x-transition x-cloak>
            @if($term !== '' && mb_strlen($term) >= 1)
                <div class="absolute left-0 right-0 top-full z-50 mt-2 rounded-2xl border border-slate-200 bg-white shadow-xl dark:border-slate-700 dark:bg-slate-900">
                    <div class="px-4 py-2 text-xs font-semibold uppercase tracking-wide text-slate-400">
                        {{ __('Search results') }}
                    </div>
                    <div wire:loading.flex wire:target="search" class="px-4 pb-2 text-xs text-slate-500">
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
            @elseif($term !== '')
                <div class="absolute left-0 right-0 top-full z-50 mt-2 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-500 shadow-xl dark:border-slate-700 dark:bg-slate-900">
                    {{ __('Type at least 1 character to search.') }}
                </div>
            @endif
        </div>
    @endif
</div>
