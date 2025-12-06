@php
    /** @var array $seo */
    $score = $seo['score'] ?? 0;

    $color = $score >= 80 ? 'bg-emerald-500' :
             ($score >= 50 ? 'bg-amber-500' : 'bg-rose-500');

    $label = $score >= 80 ? 'Good' :
             ($score >= 50 ? 'Needs improvement' : 'Poor');
@endphp

<div
    x-data="{ open: true }"
    class="mt-4 overflow-hidden rounded-2xl border border-slate-200 bg-gradient-to-br from-white via-white to-slate-50 shadow-sm
           dark:border-slate-700 dark:from-slate-800 dark:via-slate-800 dark:to-slate-900">

    {{-- Header --}}
    <div class="flex items-center justify-between gap-4 border-b border-slate-200 bg-white/80 px-6 py-4 backdrop-blur-sm dark:border-slate-700 dark:bg-slate-800/80">
        <div class="relative">
            <div class="flex h-14 w-14 items-center justify-center rounded-full border border-slate-200 text-sm font-bold text-white shadow-sm dark:border-slate-600 {{ $color }}">
                {{ $score }}
            </div>
            <span class="absolute -bottom-1 -right-1 inline-flex items-center gap-1 rounded-full bg-white px-2 py-0.5 text-[10px] font-semibold text-slate-600 shadow dark:bg-slate-700 dark:text-slate-200 whitespace-nowrap">
                <i class="fa-solid fa-chart-simple text-[11px] text-slate-500 dark:text-slate-300"></i>
                {{ $label }}
            </span>
        </div>

        <div class="flex flex-col items-end gap-2 text-right">
            <h3 class="text-sm font-semibold text-slate-800 dark:text-slate-100">SEO overview</h3>
            <div class="flex flex-wrap items-center justify-end gap-2">
                <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-[11px] font-semibold uppercase tracking-wide text-slate-600 dark:bg-slate-700 dark:text-slate-100">
                    <i class="fa-solid fa-circle-info text-slate-500 dark:text-slate-200"></i>
                    SEO Analysis
                </span>
                <span class="inline-flex items-start gap-2 rounded-full bg-slate-50 px-3 py-1 text-[11px] text-slate-600 shadow-sm ring-1 ring-slate-200 dark:bg-slate-700/60 dark:text-slate-100 dark:ring-slate-600">
                    <div class="flex flex-col leading-tight text-left">
                        <span class="font-semibold">Focus keyword rules</span>
                        <span class="text-slate-500 dark:text-slate-300">Based on keyword &amp; on-page SEO</span>
                    </div>
                </span>
            </div>
            <button type="button"
                    @click="open = !open"
                    class="inline-flex items-center gap-1 text-xs font-semibold text-sky-600 transition hover:text-sky-500 dark:text-sky-400 dark:hover:text-sky-300">
                <i class="fa-solid" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                <span x-show="open">Hide details</span>
                <span x-show="!open">Show details</span>
            </button>
        </div>
    </div>

    <div x-show="open" x-cloak class="px-4 py-3 space-y-3 text-xs">

        {{-- Focus keyword input --}}
        <div class="space-y-1">
            <label class="font-semibold text-slate-700 dark:text-slate-200">
                Focus keyword
            </label>
            <input type="text"
                   wire:model.live="focus_keyword"
                   class="block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-800
                          focus:border-sky-500 focus:ring-sky-500
                          dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100"
                   placeholder="Ex: best laravel livewire blog">
            <p class="text-[11px] text-slate-400 mt-0.5">
                This keyword will be used to calculate the SEO score.
            </p>
        </div>

        {{-- Checklist --}}
        <div class="grid grid-cols-1 gap-2 md:grid-cols-2">
            {{-- TITLE --}}
            <div class="flex items-start gap-3 rounded-xl border border-slate-200 bg-white/80 px-3 py-2 shadow-sm transition hover:-translate-y-0.5 hover:shadow dark:border-slate-700 dark:bg-slate-800/70">
                <span class="flex h-7 w-7 items-center justify-center rounded-full border text-xs {{ $seo['title_ok'] ? 'border-emerald-200 bg-emerald-50 text-emerald-600 dark:border-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300' : 'border-rose-200 bg-rose-50 text-rose-600 dark:border-rose-800 dark:bg-rose-900/40 dark:text-rose-200' }}">
                    <i class="fa-solid {{ $seo['title_ok'] ? 'fa-circle-check' : 'fa-circle-xmark' }}"></i>
                </span>
                <div>
                    <div class="font-medium text-slate-800 dark:text-slate-100">SEO title length</div>
                    <div class="text-[11px] text-slate-500 dark:text-slate-400">
                        30–65 characters is recommended.
                    </div>
                </div>
            </div>

            {{-- TITLE SENTIMENT --}}
            <div class="flex items-start gap-3 rounded-xl border border-slate-200 bg-white/80 px-3 py-2 shadow-sm transition hover:-translate-y-0.5 hover:shadow dark:border-slate-700 dark:bg-slate-800/70">
                <span class="flex h-7 w-7 items-center justify-center rounded-full border text-xs {{ $seo['title_sentiment_ok'] ? 'border-emerald-200 bg-emerald-50 text-emerald-600 dark:border-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300' : 'border-rose-200 bg-rose-50 text-rose-600 dark:border-rose-800 dark:bg-rose-900/40 dark:text-rose-200' }}">
                    <i class="fa-solid {{ $seo['title_sentiment_ok'] ? 'fa-face-laugh-beam' : 'fa-face-frown-open' }}"></i>
                </span>
                <div>
                    <div class="font-medium text-slate-800 dark:text-slate-100">Title sentiment</div>
                    <div class="text-[11px] text-slate-500 dark:text-slate-400">
                        Add a positive or negative sentiment word to inspire action.
                    </div>
                </div>
            </div>

            {{-- TITLE POWER WORD --}}
            <div class="flex items-start gap-3 rounded-xl border border-slate-200 bg-white/80 px-3 py-2 shadow-sm transition hover:-translate-y-0.5 hover:shadow dark:border-slate-700 dark:bg-slate-800/70">
                <span class="flex h-7 w-7 items-center justify-center rounded-full border text-xs {{ $seo['title_power_ok'] ? 'border-emerald-200 bg-emerald-50 text-emerald-600 dark:border-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300' : 'border-rose-200 bg-rose-50 text-rose-600 dark:border-rose-800 dark:bg-rose-900/40 dark:text-rose-200' }}">
                    <i class="fa-solid {{ $seo['title_power_ok'] ? 'fa-bolt-lightning' : 'fa-ban' }}"></i>
                </span>
                <div>
                    <div class="font-medium text-slate-800 dark:text-slate-100">Title power word</div>
                    <div class="text-[11px] text-slate-500 dark:text-slate-400">
                        Use at least one power word (free, exclusive, secret, etc.).
                    </div>
                </div>
            </div>

            {{-- DESC --}}
            <div class="flex items-start gap-3 rounded-xl border border-slate-200 bg-white/80 px-3 py-2 shadow-sm transition hover:-translate-y-0.5 hover:shadow dark:border-slate-700 dark:bg-slate-800/70">
                <span class="flex h-7 w-7 items-center justify-center rounded-full border text-xs {{ $seo['desc_ok'] ? 'border-emerald-200 bg-emerald-50 text-emerald-600 dark:border-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300' : 'border-rose-200 bg-rose-50 text-rose-600 dark:border-rose-800 dark:bg-rose-900/40 dark:text-rose-200' }}">
                    <i class="fa-solid {{ $seo['desc_ok'] ? 'fa-align-left' : 'fa-triangle-exclamation' }}"></i>
                </span>
                <div>
                    <div class="font-medium text-slate-800 dark:text-slate-100">Meta description length</div>
                    <div class="text-[11px] text-slate-500 dark:text-slate-400">
                        80–160 characters is recommended.
                    </div>
                </div>
            </div>

            {{-- CONTENT LENGTH --}}
            <div class="flex items-start gap-3 rounded-xl border border-slate-200 bg-white/80 px-3 py-2 shadow-sm transition hover:-translate-y-0.5 hover:shadow dark:border-slate-700 dark:bg-slate-800/70">
                <span class="flex h-7 w-7 items-center justify-center rounded-full border text-xs {{ $seo['content_ok'] ? 'border-emerald-200 bg-emerald-50 text-emerald-600 dark:border-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300' : 'border-rose-200 bg-rose-50 text-rose-600 dark:border-rose-800 dark:bg-rose-900/40 dark:text-rose-200' }}">
                    <i class="fa-solid {{ $seo['content_ok'] ? 'fa-file-lines' : 'fa-pen-ruler' }}"></i>
                </span>
                <div>
                    <div class="font-medium text-slate-800 dark:text-slate-100">Content length</div>
                    <div class="text-[11px] text-slate-500 dark:text-slate-400">
                        600+ words recommended.
                    </div>
                </div>
            </div>

            {{-- IMAGES --}}
            <div class="flex items-start gap-3 rounded-xl border border-slate-200 bg-white/80 px-3 py-2 shadow-sm transition hover:-translate-y-0.5 hover:shadow dark:border-slate-700 dark:bg-slate-800/70">
                <span class="flex h-7 w-7 items-center justify-center rounded-full border text-xs {{ $seo['image_ok'] ? 'border-emerald-200 bg-emerald-50 text-emerald-600 dark:border-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300' : 'border-rose-200 bg-rose-50 text-rose-600 dark:border-rose-800 dark:bg-rose-900/40 dark:text-rose-200' }}">
                    <i class="fa-solid {{ $seo['image_ok'] ? 'fa-image' : 'fa-image-slash' }}"></i>
                </span>
                <div>
                    <div class="font-medium text-slate-800 dark:text-slate-100">Images with alt text</div>
                    <div class="text-[11px] text-slate-500 dark:text-slate-400">
                        At least one image with descriptive alt text.
                    </div>
                </div>
            </div>

            {{-- HEADINGS --}}
            <div class="flex items-start gap-3 rounded-xl border border-slate-200 bg-white/80 px-3 py-2 shadow-sm transition hover:-translate-y-0.5 hover:shadow dark:border-slate-700 dark:bg-slate-800/70">
                <span class="flex h-7 w-7 items-center justify-center rounded-full border text-xs {{ $seo['head_ok'] ? 'border-emerald-200 bg-emerald-50 text-emerald-600 dark:border-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300' : 'border-rose-200 bg-rose-50 text-rose-600 dark:border-rose-800 dark:bg-rose-900/40 dark:text-rose-200' }}">
                    <i class="fa-solid {{ $seo['head_ok'] ? 'fa-bridge' : 'fa-circle-exclamation' }}"></i>
                </span>
                <div>
                    <div class="font-medium text-slate-800 dark:text-slate-100">Headings</div>
                    <div class="text-[11px] text-slate-500 dark:text-slate-400">
                        Use H2/H3 headings to structure content.
                    </div>
                </div>
            </div>

            {{-- SLUG --}}
            <div class="flex items-start gap-3 rounded-xl border border-slate-200 bg-white/80 px-3 py-2 shadow-sm transition hover:-translate-y-0.5 hover:shadow dark:border-slate-700 dark:bg-slate-800/70">
                <span class="flex h-7 w-7 items-center justify-center rounded-full border text-xs {{ $seo['slug_ok'] ? 'border-emerald-200 bg-emerald-50 text-emerald-600 dark:border-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300' : 'border-rose-200 bg-rose-50 text-rose-600 dark:border-rose-800 dark:bg-rose-900/40 dark:text-rose-200' }}">
                    <i class="fa-solid {{ $seo['slug_ok'] ? 'fa-link' : 'fa-link-slash' }}"></i>
                </span>
                <div>
                    <div class="font-medium text-slate-800 dark:text-slate-100">Clean permalink</div>
                    <div class="text-[11px] text-slate-500 dark:text-slate-400">
                        Use a human-readable, hyphenated slug.
                    </div>
                </div>
            </div>

            {{-- LINKS --}}
            <div class="flex items-start gap-3 rounded-xl border border-slate-200 bg-white/80 px-3 py-2 shadow-sm transition hover:-translate-y-0.5 hover:shadow dark:border-slate-700 dark:bg-slate-800/70">
                <span class="flex h-7 w-7 items-center justify-center rounded-full border text-xs {{ $seo['links_ok'] ? 'border-emerald-200 bg-emerald-50 text-emerald-600 dark:border-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300' : 'border-rose-200 bg-rose-50 text-rose-600 dark:border-rose-800 dark:bg-rose-900/40 dark:text-rose-200' }}">
                    <i class="fa-solid {{ $seo['links_ok'] ? 'fa-earth-americas' : 'fa-link-slash' }}"></i>
                </span>
                <div>
                    <div class="font-medium text-slate-800 dark:text-slate-100">Links</div>
                    <div class="text-[11px] text-slate-500 dark:text-slate-400">
                        Use internal/external links where relevant.
                    </div>
                </div>
            </div>

            {{-- KEYWORD DENSITY --}}
            <div class="flex items-start gap-3 rounded-xl border border-slate-200 bg-white/80 px-3 py-2 shadow-sm transition hover:-translate-y-0.5 hover:shadow dark:border-slate-700 dark:bg-slate-800/70">
                <span class="flex h-7 w-7 items-center justify-center rounded-full border text-xs {{ $seo['kw_density_ok'] ? 'border-emerald-200 bg-emerald-50 text-emerald-600 dark:border-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300' : 'border-rose-200 bg-rose-50 text-rose-600 dark:border-rose-800 dark:bg-rose-900/40 dark:text-rose-200' }}">
                    <i class="fa-solid {{ $seo['kw_density_ok'] ? 'fa-percent' : 'fa-gauge' }}"></i>
                </span>
                <div>
                    <div class="font-medium text-slate-800 dark:text-slate-100">
                        Keyword density ({{ $seo['kw_density'] ?? 0 }}%)
                    </div>
                    <div class="text-[11px] text-slate-500 dark:text-slate-400">
                        Recommended: 0.5% – 3% for "{{ $seo['focus_keyword'] ?? 'no keyword' }}".
                    </div>
                </div>
            </div>

            {{-- KEYWORD POSITION CHECKS --}}
            <div class="col-span-1 flex flex-col gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-3 shadow-sm dark:border-slate-700 dark:bg-slate-800/60 md:col-span-2">
                <div class="flex items-center gap-2 text-[11px] font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-300">
                    <i class="fa-solid fa-key text-slate-400 dark:text-slate-200"></i>
                    Keyword placement
                </div>

                <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 lg:grid-cols-3">
                    <div class="flex items-center gap-2 rounded-lg bg-white/80 px-2 py-1.5 text-[11px] text-slate-600 shadow-sm dark:bg-slate-900/60 dark:text-slate-200">
                        <i class="fa-solid fa-heading {{ $seo['kw_in_title'] ? 'text-emerald-500' : 'text-rose-500' }}"></i>
                        <span>Focus keyword in SEO title</span>
                        <i class="fa-solid ml-auto {{ $seo['kw_in_title'] ? 'fa-circle-check text-emerald-500' : 'fa-circle-xmark text-rose-500' }}"></i>
                    </div>

                    <div class="flex items-center gap-2 rounded-lg bg-white/80 px-2 py-1.5 text-[11px] text-slate-600 shadow-sm dark:bg-slate-900/60 dark:text-slate-200">
                        <i class="fa-solid fa-link {{ $seo['kw_in_slug'] ? 'text-emerald-500' : 'text-rose-500' }}"></i>
                        <span>Focus keyword in URL slug</span>
                        <i class="fa-solid ml-auto {{ $seo['kw_in_slug'] ? 'fa-circle-check text-emerald-500' : 'fa-circle-xmark text-rose-500' }}"></i>
                    </div>

                    <div class="flex items-center gap-2 rounded-lg bg-white/80 px-2 py-1.5 text-[11px] text-slate-600 shadow-sm dark:bg-slate-900/60 dark:text-slate-200">
                        <i class="fa-solid fa-align-justify {{ $seo['kw_in_desc'] ? 'text-emerald-500' : 'text-rose-500' }}"></i>
                        <span>Focus keyword in meta description</span>
                        <i class="fa-solid ml-auto {{ $seo['kw_in_desc'] ? 'fa-circle-check text-emerald-500' : 'fa-circle-xmark text-rose-500' }}"></i>
                    </div>

                    <div class="flex items-center gap-2 rounded-lg bg-white/80 px-2 py-1.5 text-[11px] text-slate-600 shadow-sm dark:bg-slate-900/60 dark:text-slate-200">
                        <i class="fa-solid fa-paragraph {{ $seo['kw_in_intro'] ? 'text-emerald-500' : 'text-rose-500' }}"></i>
                        <span>Focus keyword in first paragraph</span>
                        <i class="fa-solid ml-auto {{ $seo['kw_in_intro'] ? 'fa-circle-check text-emerald-500' : 'fa-circle-xmark text-rose-500' }}"></i>
                    </div>

                    <div class="flex items-center gap-2 rounded-lg bg-white/80 px-2 py-1.5 text-[11px] text-slate-600 shadow-sm dark:bg-slate-900/60 dark:text-slate-200">
                        <i class="fa-solid fa-list-ol {{ $seo['kw_in_head'] ? 'text-emerald-500' : 'text-rose-500' }}"></i>
                        <span>Focus keyword in H2/H3 heading</span>
                        <i class="fa-solid ml-auto {{ $seo['kw_in_head'] ? 'fa-circle-check text-emerald-500' : 'fa-circle-xmark text-rose-500' }}"></i>
                    </div>

                    <div class="flex items-center gap-2 rounded-lg bg-white/80 px-2 py-1.5 text-[11px] text-slate-600 shadow-sm dark:bg-slate-900/60 dark:text-slate-200">
                        <i class="fa-solid fa-image {{ $seo['kw_in_alt'] ? 'text-emerald-500' : 'text-rose-500' }}"></i>
                        <span>Focus keyword in image alt text</span>
                        <i class="fa-solid ml-auto {{ $seo['kw_in_alt'] ? 'fa-circle-check text-emerald-500' : 'fa-circle-xmark text-rose-500' }}"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
