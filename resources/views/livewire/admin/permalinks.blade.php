<div class="rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm p-5 bg-white dark:bg-slate-900">
    {{-- Header --}}
    <div class="flex items-center justify-between gap-2">
        <h6 class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-700 dark:text-slate-200">
            PERMALINK SETTINGS
        </h6>

        {{-- Optional subtle badge --}}
        <span class="inline-flex items-center rounded-full bg-slate-100 dark:bg-slate-800 px-2.5 py-0.5 text-[11px] font-medium text-slate-600 dark:text-slate-300">
            SEO friendly URLs
        </span>
    </div>

    <hr class="my-3 border-slate-200 dark:border-slate-700">

    <form wire:submit.prevent="updatePermalinks" class="space-y-6">
        <p class="text-xs text-slate-500 dark:text-slate-400">
            আপনার পোস্টের URL কোন ফরম্যাটে থাকবে তা নির্বাচন করুন। এসইও বান্ধব URL-এর জন্য <span class="font-semibold">"Post name"</span>
            বা কাস্টম স্ট্রাকচার ব্যবহার করুন।
        </p>

        {{-- Preset structures --}}
        <div class="space-y-3">
            @foreach ($permalinkOptions as $key => $option)
                @php
                    $inputId = 'permalink-' . $key;
                    $sampleUrl = \App\Support\PermalinkManager::previewSample($key);
                @endphp

                <label
                    for="{{ $inputId }}"
                    class="flex items-start gap-3 rounded-lg border border-slate-200 dark:border-slate-700 px-3 py-2 cursor-pointer
                           bg-white dark:bg-slate-900
                           hover:border-indigo-400 hover:bg-indigo-50/50
                           dark:hover:bg-slate-800 dark:hover:border-indigo-400 transition-colors"
                >
                    <input
                        type="radio"
                        id="{{ $inputId }}"
                        class="mt-1 h-4 w-4 border-slate-300 dark:border-slate-600 dark:bg-slate-900 text-indigo-600 focus:ring-indigo-500"
                        wire:model="permalink_structure"
                        value="{{ $key }}"
                    >

                    <span>
                        <span class="block text-sm font-semibold text-slate-800 dark:text-slate-100">
                            {{ $option['label'] }}
                        </span>
                        <span class="block text-xs text-slate-500 dark:text-slate-400">
                            {{ $sampleUrl }}
                        </span>
                    </span>
                </label>
            @endforeach

            {{-- Custom structure radio --}}
            <label
                for="permalink-custom"
                class="flex items-start gap-3 rounded-lg border border-slate-200 dark:border-slate-700 px-3 py-2 cursor-pointer
                       bg-white dark:bg-slate-900
                       hover:border-indigo-400 hover:bg-indigo-50/50
                       dark:hover:bg-slate-800 dark:hover:border-indigo-400 transition-colors"
            >
                <input
                    type="radio"
                    id="permalink-custom"
                    class="mt-1 h-4 w-4 border-slate-300 dark:border-slate-600 dark:bg-slate-900 text-indigo-600 focus:ring-indigo-500"
                    wire:model="permalink_structure"
                    value="custom"
                >

                <span>
                    <span class="block text-sm font-semibold text-slate-800 dark:text-slate-100">
                        Custom structure
                    </span>
                    <span class="block text-xs text-slate-500 dark:text-slate-400">
                        নিজের ইচ্ছামতো URL প্যাটার্ন ব্যবহার করুন (যেমন: /news/%year%/%postname%).
                    </span>
                </span>
            </label>

            @error('permalink_structure')
            <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
            @enderror
        </div>

        {{-- Custom structure input --}}
        @if ($permalink_structure === 'custom')
            <div class="space-y-1">
                <label for="custom-permalink" class="block text-sm font-semibold text-slate-800 dark:text-slate-100">
                    Custom structure
                </label>

                <div class="flex rounded-lg shadow-sm">
                    <span
                        class="inline-flex items-center rounded-l-lg border border-r-0 border-slate-300 dark:border-slate-700
                               bg-slate-50 dark:bg-slate-800 px-3 text-xs text-slate-500 dark:text-slate-300"
                    >
                        {{ rtrim(url('/'), '/') }}/
                    </span>
                    <input
                        type="text"
                        id="custom-permalink"
                        class="block w-full rounded-r-lg border border-slate-300 dark:border-slate-700
                               bg-white dark:bg-slate-900 px-3 text-sm text-slate-900 dark:text-slate-100
                               placeholder-slate-400 dark:placeholder-slate-500
                               focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/70 focus:outline-none"
                        wire:model.lazy="custom_permalink_structure"
                        placeholder="%category%/%postname%"
                    >
                </div>

                <small class="text-xs text-slate-500 dark:text-slate-400">
                    সম্ভাব্য ট্যাগসমূহ: {{ implode(', ', $permalinkTokens) }}
                </small>

                @error('custom_permalink_structure')
                <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                @enderror
            </div>
        @endif

        {{-- Category prefix toggle --}}
        <div class="space-y-1">
            <label class="block text-sm font-semibold text-slate-800 dark:text-slate-100">
                Category URL prefix
            </label>

            <label class="inline-flex items-center gap-2 cursor-pointer">
                <input
                    type="checkbox"
                    class="peer sr-only"
                    id="category-prefix-toggle"
                    wire:model.defer="category_slug_prefix_enabled"
                >
                <span
                    class="h-5 w-9 rounded-full bg-slate-300 dark:bg-slate-700 peer-checked:bg-indigo-600 transition-colors relative"
                >
                    <span
                        class="absolute left-0.5 top-0.5 h-4 w-4 rounded-full bg-white shadow-sm transform transition-transform peer-checked:translate-x-4"
                    ></span>
                </span>
                <span class="text-xs text-slate-700 dark:text-slate-200">
                    {{ $category_slug_prefix_enabled ? 'Category prefix enabled' : 'Category prefix disabled' }}
                </span>
            </label>

            <small class="block text-xs text-slate-500 dark:text-slate-400">
                এই অপশন চালু থাকলে ক্যাটাগরি স্লাগের আগে "category" যোগ হবে (যেমন: /category/news)। বন্ধ করলে স্লাগটি সরাসরি ব্যবহার হবে (যেমন: /news)।
            </small>
        </div>

        {{-- Tag URL base --}}
        <div class="space-y-1 mt-4">
            <label class="block text-sm font-semibold text-slate-800 dark:text-slate-100">
                Tag URL base
            </label>

            <div class="flex rounded-lg shadow-sm">
        <span
            class="inline-flex items-center rounded-l-lg border border-r-0 border-slate-300 dark:border-slate-700
                   bg-slate-50 dark:bg-slate-800 px-3 text-xs text-slate-500 dark:text-slate-300"
        >
            {{ rtrim(url('/'), '/') }}/
        </span>
                <input
                    type="text"
                    class="block w-full rounded-r-lg border border-slate-300 dark:border-slate-700
                   bg-white dark:bg-slate-900 px-3 text-sm text-slate-900 dark:text-slate-100
                   placeholder-slate-400 dark:placeholder-slate-500
                   focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/70 focus:outline-none"
                    wire:model.lazy="tag_slug_prefix"
                    placeholder="tag"
                >
            </div>

            <small class="block text-xs text-slate-500 dark:text-slate-400">
                Example:
                <span class="font-mono">
            {{ \App\Support\PermalinkManager::tagPreview('laravel') }}
        </span>
            </small>

            @error('tag_slug_prefix')
            <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
            @enderror
        </div>


        {{-- Preview --}}
        <div class="space-y-1">
            <label for="permalink-preview" class="block text-sm font-semibold text-slate-800 dark:text-slate-100">
                Sample URL
            </label>
            <div
                id="permalink-preview"
                class="rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3 py-2 text-xs text-slate-700 dark:text-slate-200"
            >
                {{ $this->permalinkPreview }}
            </div>
        </div>

        {{-- Submit --}}
        <div class="pt-1">
            <button
                type="submit"
                class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm
                       hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500
                       focus:ring-offset-1 focus:ring-offset-slate-50 dark:focus:ring-offset-slate-900"
            >
                Save permalinks
            </button>
        </div>
    </form>
</div>
