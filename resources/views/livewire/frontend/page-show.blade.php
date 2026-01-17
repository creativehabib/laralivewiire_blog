<div class="container px-4 py-10 md:py-12 max-w-6xl mx-auto typography" wire:init="loadReady">

    {{-- ======================================================================== --}}
    {{-- 1. SKELETON STATE (লোডিং এর সময় এটি দেখাবে) --}}
    {{-- ======================================================================== --}}
    @if(!$ready)
        <div class="w-full">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 lg:items-start">
                {{-- Main Content Skeleton --}}
                <div class="lg:col-span-8 space-y-8">
                    {{-- Hero Skeleton --}}
                    <flux:skeleton.group animate="shimmer" class="grid md:grid-cols-3 gap-4">
                        <div class="md:col-span-2 space-y-3">
                            <flux:skeleton class="h-72 w-full rounded-xl" />
                            <flux:skeleton.line />
                            <flux:skeleton.line class="w-1/2" />
                        </div>
                        <div class="space-y-3">
                            @for($i = 0; $i < 3; $i++)
                                <div class="flex gap-3">
                                    <flux:skeleton class="w-20 h-16 rounded-lg shrink-0" />
                                    <div class="flex-1 space-y-2">
                                        <flux:skeleton.line />
                                        <flux:skeleton.line class="w-2/3" />
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </flux:skeleton.group>

                    {{-- List Skeleton --}}
                    <flux:skeleton.group animate="shimmer" class="space-y-6">
                        <div class="flex items-center justify-between">
                            <flux:skeleton.line class="w-32 h-6" />
                        </div>
                        <div class="grid sm:grid-cols-2 gap-4">
                            @for($i = 0; $i < 4; $i++)
                                <div class="flex gap-4">
                                    <flux:skeleton class="w-24 h-20 rounded-lg shrink-0" />
                                    <div class="flex-1 space-y-2">
                                        <flux:skeleton.line />
                                        <flux:skeleton.line class="w-2/3" />
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </flux:skeleton.group>
                </div>

                {{-- Sidebar Skeleton --}}
                <div class="lg:col-span-4 space-y-6">
                    <flux:skeleton.group animate="shimmer" class="space-y-4">
                        <flux:skeleton.line class="h-5 w-24" />
                        <div class="space-y-2">
                            @for($i = 0; $i < 5; $i++)
                                <flux:skeleton.line />
                            @endfor
                        </div>
                    </flux:skeleton.group>
                    <flux:skeleton.group animate="shimmer" class="space-y-3">
                        <flux:skeleton.line class="h-5 w-32" />
                        <flux:skeleton class="h-40 w-full rounded-xl" />
                    </flux:skeleton.group>
                </div>
            </div>
        </div>

        {{-- ======================================================================== --}}
        {{-- 2. REAL CONTENT (আপনার দেওয়া কোড - কোনো পরিবর্তন ছাড়া) --}}
        {{-- ======================================================================== --}}
    @else
        <article class="space-y-4">
            @if ($showPageHeader ?? true)
                <header class="space-y-1.5">
                    <p class="text-xs uppercase tracking-[0.2em] font-medium text-slate-500 dark:text-slate-400">Page</p>
                    <h1 class="text-3xl md:text-4xl font-bold tracking-tight text-slate-900 dark:text-slate-100 leading-tight">{{ $page->name }}</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                        Updated : {{ the_date($page, 'diff', 'updated_at') }}
                    </p>
                </header>
            @endif

            @if ($page->image)
                <div class="rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700">
                    <img src="{{ image_optimize_url($page->image, 1200, 675) }}"
                         alt="{{ $page->name }}"
                         loading="eager"
                         fetchpriority="high"
                         decoding="async"
                         width="1200"
                         height="675"
                         class="w-full h-auto">
                </div>
            @endif

            @php
                $builderEnabled = ($builderState['enabled'] ?? false) && !empty($builderSections);
            @endphp

            @if ($builderEnabled)
                <div class="space-y-6">
                    @foreach ($builderSections as $section)
                        @php
                            $sidebar = $section['sidebar'] ?? 'none';
                            $blocks = $section['blocks'] ?? [];
                            $gridClass = match ($sidebar) {
                                'left' => 'md:grid-cols-[380px_1fr]',
                                'right' => 'md:grid-cols-[1fr_380px]',
                                default => 'md:grid-cols-1',
                            };
                        @endphp
                        <div class="">
                            <div class="grid gap-4 {{ $gridClass }}">
                                @if ($sidebar === 'left')
                                    <aside class="rounded-lg border border-dashed border-slate-200 bg-slate-50 p-3 text-xs text-slate-500 dark:border-slate-700 dark:bg-slate-800">
                                        <p class="font-semibold text-slate-600 dark:text-slate-200 mb-1">Sidebar</p>
                                        <p class="leading-relaxed">Manage Widgets</p>
                                    </aside>
                                @endif

                                <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                                    @forelse ($blocks as $block)
                                        @php
                                            $settings = $block['settings'] ?? [];
                                            $blockTitle = data_get($settings, 'title') ?: ($block['name'] ?? 'Block');
                                            $tagsValue = data_get($settings, 'tags');
                                            $posts = $block['posts'] ?? [];
                                            $layout = $block['layout'] ?? 'stacked';
                                            $postItems = collect($posts instanceof \Illuminate\Pagination\AbstractPaginator ? $posts->items() : $posts);
                                            $showExcerpt = data_get($settings, 'showExcerpt', true);
                                            $excerptLength = (int) data_get($settings, 'excerptLength', 0);
                                            $titleLength = (int) data_get($settings, 'titleLength', 0);
                                            $contentOnly = data_get($settings, 'contentOnly', false);
                                            $darkMode = data_get($settings, 'darkMode', false);
                                            $primaryColor = data_get($settings, 'primaryColor');
                                            $backgroundColor = data_get($settings, 'backgroundColor');
                                            $secondaryColor = data_get($settings, 'secondaryColor');
                                            $readMoreButton = data_get($settings, 'readMoreButton', false);
                                            $moreButton = data_get($settings, 'moreButton', false);
                                            $hideFirstThumbnail = data_get($settings, 'hideFirstThumbnail', false);
                                            $hideSmallThumbnails = data_get($settings, 'hideSmallThumbnails', false);
                                            $postMeta = data_get($settings, 'postMeta', true);
                                            $mediaIcon = data_get($settings, 'mediaIcon', false);
                                            $blockStyles = collect([
                                                $backgroundColor ? "background-color: {$backgroundColor}" : null,
                                                $primaryColor ? "--block-primary: {$primaryColor}" : null,
                                                $secondaryColor ? "--block-secondary: {$secondaryColor}" : null,
                                            ])->filter()->implode('; ');
                                        @endphp
                                        <div class="{{ $contentOnly ? 'text-sm text-slate-700 dark:text-slate-200 leading-relaxed' : 'space-y-3 rounded-lg border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200' }} {{ $darkMode ? 'bg-slate-900 text-slate-100' : '' }} {{ $layout === 'half-width' ? 'md:col-span-1' : 'md:col-span-2' }}"
                                             style="{{ $blockStyles }}">
                                            <div class="flex items-center justify-between gap-2" style="color: var(--block-primary, inherit);">
                                                <div>
                                                    <p class="font-bold text-base leading-snug">{{ $titleLength > 0 ? \Illuminate\Support\Str::limit($blockTitle, $titleLength) : $blockTitle }}</p>
                                                    @if (!empty($tagsValue))
                                                        <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">Tags: {{ $tagsValue }}</p>
                                                    @endif
                                                </div>
                                                @if (!empty($settings['url']) && $moreButton)
                                                    <a href="{{ $settings['url'] }}" class="text-xs font-semibold text-sky-600 hover:text-sky-500 transition-colors" wire:navigate>View all</a>
                                                @endif
                                            </div>
                                            @if (count($posts))
                                                @include('livewire.frontend.partials.page-show.articles')
                                            @else
                                                <p class="text-xs text-slate-500 leading-relaxed">No posts matched this block settings.</p>
                                            @endif
                                        </div>
                                    @empty
                                        <div class="rounded-lg border border-dashed border-slate-200 p-4 text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400 leading-relaxed">
                                            No blocks added yet.
                                        </div>
                                    @endforelse
                                </div>

                                @if ($sidebar === 'right')
                                    <aside class="rounded-lg lg:sticky lg:top-32 self-start space-y-6 border-slate-200 bg-slate-50 text-xs text-slate-500 dark:border-slate-700 dark:bg-slate-800">
                                        <section class="bg-primary-light/70 dark:bg-slate-800 rounded-xl p-4 border border-primary/20 dark:border-slate-700
                            transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
                                            <h2 class="text-base font-semibold mb-1 text-primary-dark dark:text-primary-light">
                                                নিউজলেটার সাবস্ক্রাইব করুন
                                            </h2>
                                            <p class="text-xs text-slate-700 dark:text-slate-300 mb-3">
                                                দিনের গুরুত্বপূর্ণ খবর সরাসরি পেতে ইমেইল দিন।
                                            </p>
                                            <form class="space-y-2">
                                                <input type="email" placeholder="আপনার ইমেইল"
                                                       class="w-full px-3 py-2 text-sm rounded-md border border-primary/40 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary" />
                                                <button type="submit" class="w-full bg-primary-dark text-white text-sm font-semibold py-2 rounded-md hover:bg-primary">
                                                    সাবস্ক্রাইব
                                                </button>
                                            </form>
                                        </section>

                                        <section class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-4
                            transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
                                            <h2 class="text-base font-semibold border-b pb-2 mb-3 border-slate-200 dark:border-slate-700">
                                                আমাদের সাথে থাকুন
                                            </h2>
                                            <div class="grid grid-cols-2 gap-2 text-sm">
                                                <a href="#" class="bg-blue-600 text-white py-2 rounded-md text-center font-semibold">Facebook</a>
                                                <a href="#" class="bg-red-600 text-white py-2 rounded-md text-center font-semibold">YouTube</a>
                                                <a href="#" class="bg-sky-500 text-white py-2 rounded-md text-center font-semibold">Twitter</a>
                                                <a href="#" class="bg-green-600 text-white py-2 rounded-md text-center font-semibold">WhatsApp</a>
                                            </div>
                                        </section>

                                        <section class="bg-slate-100 dark:bg-slate-800 border border-dashed border-slate-300 dark:border-slate-600 rounded-xl p-4 text-center text-xs text-slate-500 dark:text-slate-400">
                                            বিজ্ঞাপনের স্থান
                                        </section>
                                    </aside>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                    <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
                        <div class="lg:col-span-8">
                            <div class="prose prose-slate prose-lg md:prose-xl max-w-none dark:prose-invert prose-headings:tracking-tight prose-headings:font-bold prose-p:leading-relaxed prose-li:leading-relaxed">
                                {!! $page->content !!}
                            </div>
                        </div>
                        <aside class="lg:col-span-4 lg:sticky lg:top-32 self-start space-y-6">
                            <section class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-4 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
                                <h2 class="text-base font-semibold border-b pb-2 mb-3 border-slate-200 dark:border-slate-700">
                                    সর্বশেষ খবর
                                </h2>
                                <div class="space-y-3 text-sm">
                                    @forelse ($sidebarLatest as $post)
                                        <article class="flex gap-3">
                                            <a href="{{ post_permalink($post) }}" class="shrink-0 block" wire:navigate>
                                                <img src="{{ the_thumbnail($post, 200, 140) }}"
                                                     alt="{{ $post->name }}"
                                                     loading="lazy"
                                                     decoding="async"
                                                     width="200"
                                                     height="140"
                                                     class="w-20 h-14 object-cover rounded-md">
                                            </a>
                                            <div class="flex-1">
                                                <a href="{{ post_permalink($post) }}" class="font-semibold leading-snug hover:text-primary-dark dark:hover:text-primary-light line-clamp-2" wire:navigate>
                                                    {{ $post->name }}
                                                </a>
                                                <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">
                                                    {{ the_date($post, 'diff') }}
                                                </div>
                                            </div>
                                        </article>
                                    @empty
                                        <p class="text-xs text-slate-500 dark:text-slate-400">কোনো খবর পাওয়া যায়নি।</p>
                                    @endforelse
                                </div>
                            </section>

                            <section class="bg-primary-light/70 dark:bg-slate-800 rounded-xl p-4 border border-primary/20 dark:border-slate-700 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
                                <h2 class="text-base font-semibold mb-1 text-primary-dark dark:text-primary-light">
                                    নিউজলেটার সাবস্ক্রাইব করুন
                                </h2>
                                <p class="text-xs text-slate-700 dark:text-slate-300 mb-3">
                                    দিনের গুরুত্বপূর্ণ খবর সরাসরি পেতে ইমেইল দিন।
                                </p>
                                <form class="space-y-2">
                                    <input type="email" placeholder="আপনার ইমেইল"
                                           class="w-full px-3 py-2 text-sm rounded-md border border-primary/40 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary" />
                                    <button type="submit" class="w-full bg-primary-dark text-white text-sm font-semibold py-2 rounded-md hover:bg-primary">
                                        সাবস্ক্রাইব
                                    </button>
                                </form>
                            </section>

                            <section class="bg-slate-100 dark:bg-slate-800 border border-dashed border-slate-300 dark:border-slate-600 rounded-xl p-4 text-center text-xs text-slate-500 dark:text-slate-400">
                                বিজ্ঞাপনের স্থান
                            </section>
                        </aside>
                </div>
            @endif
        </article>

        @if ($showPageComments ?? true)
            <section class="mt-8">
                <h2 class="text-sm font-bold border-b pb-2 mb-3 border-slate-200 dark:border-slate-700 tracking-tight">
                    মন্তব্য করুন
                </h2>
                <x-comments.section :commentable="$page" :canonical-url="page_permalink($page)" />
            </section>
        @endif
    @endif
</div>
