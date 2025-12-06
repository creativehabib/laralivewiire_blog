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
    class="mt-4 rounded-xl border border-slate-200 bg-white shadow-sm
           dark:border-slate-700 dark:bg-slate-800">

    {{-- Header --}}
    <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200 dark:border-slate-700">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 flex items-center justify-center rounded-full text-white text-sm font-bold {{ $color }}">
                {{ $score }}
            </div>
            <div>
                <div class="text-xs font-semibold text-slate-800 dark:text-slate-100">
                    SEO analysis
                    <span class="ml-1 text-[11px] font-normal text-slate-500 dark:text-slate-400">
                        ({{ $label }})
                    </span>
                </div>
                <div class="text-[11px] text-slate-500 dark:text-slate-400">
                    Based on focus keyword & on-page SEO rules
                </div>
            </div>
        </div>

        <button type="button"
                @click="open = !open"
                class="text-[11px] text-sky-600 dark:text-sky-400 hover:underline">
            <span x-show="open">Hide details</span>
            <span x-show="!open">Show details</span>
        </button>
    </div>

    <div x-show="open" x-cloak class="px-4 py-3 space-y-3 text-xs">

        {{-- Focus keyword input --}}
        <div class="space-y-1">
            <label class="font-semibold text-slate-700 dark:text-slate-200">
                Focus keyword
            </label>
            <input type="text"
                   wire:model.live="focus_keyword"
                   class="block w-full rounded-md border px-3 py-1.5 text-xs
                          border-slate-300 bg-white text-slate-800
                          focus:border-sky-500 focus:ring-sky-500
                          dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100"
                   placeholder="Ex: best laravel livewire blog">
            <p class="text-[11px] text-slate-400 mt-0.5">
                This keyword will be used to calculate the SEO score.
            </p>
        </div>

        {{-- Checklist --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
            {{-- TITLE --}}
            <div class="flex items-start gap-2">
                <span class="{{ $seo['title_ok'] ? 'text-emerald-500' : 'text-rose-500' }}">
                    {{ $seo['title_ok'] ? '✔' : '✖' }}
                </span>
                <div>
                    <div class="font-medium">SEO title length</div>
                    <div class="text-[11px] text-slate-500">
                        30–65 characters is recommended.
                    </div>
                </div>
            </div>

            {{-- DESC --}}
            <div class="flex items-start gap-2">
                <span class="{{ $seo['desc_ok'] ? 'text-emerald-500' : 'text-rose-500' }}">
                    {{ $seo['desc_ok'] ? '✔' : '✖' }}
                </span>
                <div>
                    <div class="font-medium">Meta description length</div>
                    <div class="text-[11px] text-slate-500">
                        80–160 characters is recommended.
                    </div>
                </div>
            </div>

            {{-- CONTENT LENGTH --}}
            <div class="flex items-start gap-2">
                <span class="{{ $seo['content_ok'] ? 'text-emerald-500' : 'text-rose-500' }}">
                    {{ $seo['content_ok'] ? '✔' : '✖' }}
                </span>
                <div>
                    <div class="font-medium">Content length</div>
                    <div class="text-[11px] text-slate-500">
                        600+ words recommended.
                    </div>
                </div>
            </div>

            {{-- IMAGES --}}
            <div class="flex items-start gap-2">
                <span class="{{ $seo['image_ok'] ? 'text-emerald-500' : 'text-rose-500' }}">
                    {{ $seo['image_ok'] ? '✔' : '✖' }}
                </span>
                <div>
                    <div class="font-medium">Images with alt text</div>
                    <div class="text-[11px] text-slate-500">
                        At least one image with descriptive alt text.
                    </div>
                </div>
            </div>

            {{-- HEADINGS --}}
            <div class="flex items-start gap-2">
                <span class="{{ $seo['head_ok'] ? 'text-emerald-500' : 'text-rose-500' }}">
                    {{ $seo['head_ok'] ? '✔' : '✖' }}
                </span>
                <div>
                    <div class="font-medium">Headings</div>
                    <div class="text-[11px] text-slate-500">
                        Use H2/H3 headings to structure content.
                    </div>
                </div>
            </div>

            {{-- SLUG --}}
            <div class="flex items-start gap-2">
                <span class="{{ $seo['slug_ok'] ? 'text-emerald-500' : 'text-rose-500' }}">
                    {{ $seo['slug_ok'] ? '✔' : '✖' }}
                </span>
                <div>
                    <div class="font-medium">Clean permalink</div>
                    <div class="text-[11px] text-slate-500">
                        Use a human-readable, hyphenated slug.
                    </div>
                </div>
            </div>

            {{-- LINKS --}}
            <div class="flex items-start gap-2">
                <span class="{{ $seo['links_ok'] ? 'text-emerald-500' : 'text-rose-500' }}">
                    {{ $seo['links_ok'] ? '✔' : '✖' }}
                </span>
                <div>
                    <div class="font-medium">Links</div>
                    <div class="text-[11px] text-slate-500">
                        Use internal/external links where relevant.
                    </div>
                </div>
            </div>

            {{-- KEYWORD DENSITY --}}
            <div class="flex items-start gap-2">
                <span class="{{ $seo['kw_density_ok'] ? 'text-emerald-500' : 'text-rose-500' }}">
                    {{ $seo['kw_density_ok'] ? '✔' : '✖' }}
                </span>
                <div>
                    <div class="font-medium">
                        Keyword density ({{ $seo['kw_density'] ?? 0 }}%)
                    </div>
                    <div class="text-[11px] text-slate-500">
                        Recommended: 0.5% – 3% for "{{ $seo['focus_keyword'] ?? 'no keyword' }}".
                    </div>
                </div>
            </div>

            {{-- KEYWORD POSITION CHECKS --}}
            <div class="flex flex-col gap-1 col-span-1 md:col-span-2">
                <div class="flex items-center gap-2">
                    <span class="{{ $seo['kw_in_title'] ? 'text-emerald-500' : 'text-rose-500' }}">
                        {{ $seo['kw_in_title'] ? '✔' : '✖' }}
                    </span>
                    <span class="text-[11px] text-slate-600">
                        Focus keyword in SEO title
                    </span>
                </div>

                <div class="flex items-center gap-2">
                    <span class="{{ $seo['kw_in_slug'] ? 'text-emerald-500' : 'text-rose-500' }}">
                        {{ $seo['kw_in_slug'] ? '✔' : '✖' }}
                    </span>
                    <span class="text-[11px] text-slate-600">
                        Focus keyword in URL slug
                    </span>
                </div>

                <div class="flex items-center gap-2">
                    <span class="{{ $seo['kw_in_desc'] ? 'text-emerald-500' : 'text-rose-500' }}">
                        {{ $seo['kw_in_desc'] ? '✔' : '✖' }}
                    </span>
                    <span class="text-[11px] text-slate-600">
                        Focus keyword in meta description
                    </span>
                </div>

                <div class="flex items-center gap-2">
                    <span class="{{ $seo['kw_in_intro'] ? 'text-emerald-500' : 'text-rose-500' }}">
                        {{ $seo['kw_in_intro'] ? '✔' : '✖' }}
                    </span>
                    <span class="text-[11px] text-slate-600">
                        Focus keyword in first paragraph
                    </span>
                </div>

                <div class="flex items-center gap-2">
                    <span class="{{ $seo['kw_in_head'] ? 'text-emerald-500' : 'text-rose-500' }}">
                        {{ $seo['kw_in_head'] ? '✔' : '✖' }}
                    </span>
                    <span class="text-[11px] text-slate-600">
                        Focus keyword in H2/H3 heading
                    </span>
                </div>

                <div class="flex items-center gap-2">
                    <span class="{{ $seo['kw_in_alt'] ? 'text-emerald-500' : 'text-rose-500' }}">
                        {{ $seo['kw_in_alt'] ? '✔' : '✖' }}
                    </span>
                    <span class="text-[11px] text-slate-600">
                        Focus keyword in image alt text
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
