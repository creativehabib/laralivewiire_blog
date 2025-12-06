@php
    use Illuminate\Support\Str;

    $seoTitleLimit = 70;
    $seoDescLimit  = 160;

    // কোন টাইপের জন্য SEO preview? (post / tag / category)
    // include করার সময় যদি 'seoType' পাঠাও, সেটা নেবে।
    // না পাঠালে ডিফল্ট 'post'
    $seoType = $seoType ?? ($permalinkType ?? 'post');

    // preview data
    $previewTitle = $seo_title ?? $name ?? '';

    $previewSlug = $slug ?? null;

    // আমাদের global helper দিয়ে final preview URL
    $previewUrl  = preview_url($seoType, $previewSlug);

    $previewDesc = Str::limit($seo_description ?? $description ?? '', $seoDescLimit);
@endphp

<div x-data="{ open: false }"
     class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">

    {{-- Header + "Edit SEO meta" --}}
    <div class="px-4 py-3 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
        <div class="flex items-center gap-2 text-sm font-semibold text-slate-800 dark:text-slate-100">
            <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-200">
                <i class="fa-solid fa-chart-simple"></i>
            </span>
            <span>Search Engine Optimize</span>
        </div>

        <button type="button"
                @click="open = !open"
                class="inline-flex items-center gap-1 rounded-full border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-50 dark:border-slate-600 dark:text-slate-200 dark:hover:bg-slate-700">
            <i class="fa-solid" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
            Edit SEO meta
        </button>
    </div>

    {{-- Google-style preview / বা help text --}}
    <div class="px-4 py-4 border-b border-slate-200 dark:border-slate-700 text-sm bg-slate-50/60 dark:bg-slate-900/40">
        @if($previewTitle || $previewDesc)
            {{-- যখন data আছে → normal preview --}}
            <div class="flex items-start gap-3 min-w-0">
                <div class="mt-0.5 text-sky-500">
                    <i class="fa-brands fa-google text-lg"></i>
                </div>
                <div class="flex-1 space-y-1 min-w-0">
                    <div class="text-sm font-medium text-blue-600 hover:underline truncate">
                        {{ $previewTitle ?: 'Preview title will appear here' }}
                    </div>

                    <div class="flex items-center gap-2 text-xs text-emerald-700">
                        <i class="fa-solid fa-link text-[10px]"></i>
                        <span class="truncate">{{ $previewUrl }}</span>
                    </div>

                    <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-slate-400 min-w-0">
                        <i class="fa-regular fa-clock"></i>
                        <span>{{ now()->format('M d, Y') }}</span>
                        @if($previewDesc)
                            <span class="text-slate-400">&bull;</span>
                            <span class="truncate">{{ $previewDesc }}</span>
                        @endif
                    </div>
                </div>
            </div>
        @else
            {{-- যখন কিছুই সেট করা নেই --}}
            <div class="flex items-start gap-3 text-xs text-gray-500 dark:text-slate-400">
                <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
                <p>
                    Setup meta title &amp; description to make your site easy to discovered on search engines such as Google.
                </p>
            </div>
        @endif
    </div>

    {{-- নিচের অংশ আগের মতই থাকবে --}}
    <div class="px-4 py-4 space-y-4" x-show="open" x-cloak>
        {{-- ========== SEO TITLE ========== --}}
        <div>
            <div class="flex items-center justify-between mb-1">
                <label class="flex items-center gap-2 text-xs font-semibold text-gray-700 dark:text-slate-100">
                    <i class="fa-regular fa-pen-to-square text-slate-400"></i>
                    SEO Title
                </label>

                {{-- character counter --}}
                <span
                    class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-0.5 text-[11px] text-slate-500 dark:bg-slate-700/60 dark:text-slate-200"
                    x-data
                    :class="(($wire.seo_title || '').length > {{ $seoTitleLimit }})
                        ? 'text-red-500 bg-rose-50 dark:bg-rose-900/30'
                        : 'text-slate-500'">
                    <i class="fa-regular fa-circle"></i>
                    <span x-text="($wire.seo_title || '').length"></span>
                    /
                    {{ $seoTitleLimit }}
                </span>
            </div>

            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                    <i class="fa-solid fa-heading"></i>
                </span>
                <input type="text"
                       wire:model="seo_title"
                       class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 pl-10 text-sm focus:border-sky-500 focus:ring-1 focus:ring-sky-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                       placeholder="SEO Title">
            </div>

            @error('seo_title')
            <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- ========== SEO DESCRIPTION ========== --}}
        <div>
            <div class="flex items-center justify-between mb-1">
                <label class="flex items-center gap-2 text-xs font-semibold text-gray-700 dark:text-slate-100">
                    <i class="fa-regular fa-note-sticky text-slate-400"></i>
                    SEO description
                </label>

                {{-- character counter --}}
                <span
                    class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-0.5 text-[11px] text-slate-500 dark:bg-slate-700/60 dark:text-slate-200"
                    x-data
                    :class="(($wire.seo_description || '').length > {{ $seoDescLimit }})
                        ? 'text-red-500 bg-rose-50 dark:bg-rose-900/30'
                        : 'text-slate-500'">
                    <i class="fa-regular fa-circle"></i>
                    <span x-text="($wire.seo_description || '').length"></span>
                    /
                    {{ $seoDescLimit }}
                </span>
            </div>

            <div class="relative">
                <span class="pointer-events-none absolute left-3 top-3 text-slate-400">
                    <i class="fa-regular fa-align-left"></i>
                </span>
                <textarea wire:model="seo_description"
                          rows="3"
                          class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 pl-10 text-sm focus:border-sky-500 focus:ring-1 focus:ring-sky-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                          placeholder="SEO Description"></textarea>
            </div>

            @error('seo_description')
            <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Info box (keywords removed) --}}
        <div class="flex items-start gap-3 p-3 rounded-lg border border-amber-200 bg-amber-50 text-amber-800 dark:bg-amber-900/30 dark:border-amber-800/60 dark:text-amber-100">
            <i class="fa-solid fa-circle-info text-lg"></i>
            <p class="text-sm leading-relaxed">
                Meta keywords was removed by Google, you don't need to add meta keywords to your website.
                Learn more:
                <a href="https://yoast.com/meta-keywords" target="_blank"
                   class="text-blue-600 dark:text-blue-400 underline hover:opacity-80">
                    https://yoast.com/meta-keywords
                </a>
            </p>
        </div>

        {{-- ========== SEO IMAGE ========== --}}
        @include('mediamanager::includes.media-input', [
            'name'  => 'seo_image',
            'id'    => 'seo_image',
            'label' => 'SEO image',
            'value' => $seo_image ?? '',
        ])

        {{-- ========== INDEX / NO INDEX ========== --}}
        <div>
            <div class="text-xs font-semibold text-gray-700 mb-1 dark:text-slate-100">
                Index
            </div>
            <div class="flex items-center space-x-4 text-xs text-slate-700 dark:text-slate-200">
                <label class="flex items-center space-x-2">
                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-200">
                        <i class="fa-solid fa-arrow-trend-up text-[11px]"></i>
                    </span>
                    <div class="flex items-center space-x-1">
                        <input type="radio" value="index" wire:model="seo_index"
                               class="rounded border-gray-300 focus:ring-sky-500">
                        <span>Index</span>
                    </div>
                </label>

                <label class="flex items-center space-x-2">
                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-slate-100 text-slate-600 dark:bg-slate-700/60 dark:text-slate-200">
                        <i class="fa-solid fa-ban text-[11px]"></i>
                    </span>
                    <div class="flex items-center space-x-1">
                        <input type="radio" value="noindex" wire:model="seo_index"
                               class="rounded border-gray-300 focus:ring-sky-500">
                        <span>No index</span>
                    </div>
                </label>
            </div>
            @error('seo_index')
            <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
