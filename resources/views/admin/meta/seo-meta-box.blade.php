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
     class="border border-gray-200 rounded bg-white">

    {{-- Header + "Edit SEO meta" --}}
    <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
        <div class="text-sm font-semibold text-gray-800">
            Search Engine Optimize
        </div>

        <button type="button"
                @click="open = !open"
                class="text-xs text-blue-600 hover:underline cursor-pointer">
            Edit SEO meta
        </button>
    </div>

    {{-- Google-style preview / বা help text --}}
    <div class="px-4 py-3 border-b border-gray-100 text-sm">
        @if($previewTitle || $previewDesc)
            {{-- যখন data আছে → normal preview --}}
            <div class="text-sm font-medium text-blue-600 hover:underline truncate">
                {{ $previewTitle ?: 'Preview title will appear here' }}
            </div>

            <div class="text-xs text-emerald-700">
                {{ $previewUrl }}
            </div>

            <div class="text-xs text-gray-500 mt-1">
                {{ now()->format('M d, Y') }}
                @if($previewDesc)
                    &nbsp;-&nbsp;{{ $previewDesc }}
                @endif
            </div>
        @else
            {{-- যখন কিছুই সেট করা নেই --}}
            <p class="text-xs text-gray-500">
                Setup meta title &amp; description to make your site easy to discovered on search engines such as Google.
            </p>
        @endif
    </div>

    {{-- নিচের অংশ আগের মতই থাকবে --}}
    <div class="px-4 py-4 space-y-4" x-show="open" x-cloak>
        {{-- ========== SEO TITLE ========== --}}
        <div>
            <div class="flex items-center justify-between mb-1">
                <label class="block text-xs font-semibold text-gray-700">
                    SEO Title
                </label>

                {{-- character counter --}}
                <span
                    class="text-xs"
                    x-data
                    :class="(($wire.seo_title || '').length > {{ $seoTitleLimit }})
                        ? 'text-red-500'
                        : 'text-gray-400'">
                    <span x-text="($wire.seo_title || '').length"></span>
                    /
                    {{ $seoTitleLimit }}
                </span>
            </div>

            <input type="text"
                   wire:model="seo_title"
                   class="w-full border rounded px-3 py-2 text-sm"
                   placeholder="SEO Title">

            @error('seo_title')
            <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- ========== SEO DESCRIPTION ========== --}}
        <div>
            <div class="flex items-center justify-between mb-1">
                <label class="block text-xs font-semibold text-gray-700">
                    SEO description
                </label>

                {{-- character counter --}}
                <span
                    class="text-xs"
                    x-data
                    :class="(($wire.seo_description || '').length > {{ $seoDescLimit }})
                        ? 'text-red-500'
                        : 'text-gray-400'">
                    <span x-text="($wire.seo_description || '').length"></span>
                    /
                    {{ $seoDescLimit }}
                </span>
            </div>

            <textarea wire:model="seo_description"
                      rows="3"
                      class="w-full border rounded px-3 py-2 text-sm"
                      placeholder="SEO Description"></textarea>

            @error('seo_description')
            <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Info box (keywords removed) --}}
        <div class="flex items-start gap-3 p-2 rounded-lg border border-slate-300 bg-blue-50 text-slate-700
            dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200">
            <i class="fa-solid fa-circle-info text-slate-500 dark:text-slate-300 text-xl mt-0.5"></i>
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
            <div class="text-xs font-semibold text-gray-700 mb-1">
                Index
            </div>
            <div class="flex items-center space-x-4 text-xs">
                <label class="flex items-center space-x-1">
                    <input type="radio" value="index" wire:model="seo_index"
                           class="rounded border-gray-300">
                    <span>Index</span>
                </label>

                <label class="flex items-center space-x-1">
                    <input type="radio" value="noindex" wire:model="seo_index"
                           class="rounded border-gray-300">
                    <span>No index</span>
                </label>
            </div>
            @error('seo_index')
            <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
