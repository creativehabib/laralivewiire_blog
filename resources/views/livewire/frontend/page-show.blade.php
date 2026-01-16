<div class="container px-4 py-10 md:py-12 max-w-4xl mx-auto typography">
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
                <img src="{{ $page->image }}" alt="{{ $page->name }}" class="w-full h-auto">
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
                                            @switch($layout)
                                                @case('list-sidebar')
                                                    @php
                                                        $featuredPost = $postItems->first();
                                                        $sidebarPosts = $postItems->slice(1);
                                                    @endphp

                                                    <div class="grid gap-6 lg:gap-8 lg:grid-cols-[1.4fr_1fr] items-start">

                                                        {{-- === Featured Post (Left Side) === --}}
                                                        @if ($featuredPost)
                                                            <article class="group relative flex flex-col gap-4">
                                                                {{-- Image Container with Zoom Effect --}}
                                                                <a href="{{ post_permalink($featuredPost) }}" class="block w-full overflow-hidden rounded-2xl bg-slate-100 dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700/50" wire:navigate>
                                                                    <img src="{{ the_thumbnail($featuredPost) }}"
                                                                         alt="{{ $featuredPost->name }}"
                                                                         class="w-full aspect-video object-cover transform transition-transform duration-700 group-hover:scale-105">
                                                                </a>

                                                                <div class="space-y-3">
                                                                    {{-- Title --}}
                                                                    <h2 class="text-xl md:text-2xl lg:text-3xl font-bold text-slate-800 dark:text-slate-100 leading-tight">
                                                                        <a href="{{ post_permalink($featuredPost) }}" class="group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors block" wire:navigate>
                                                                            {{ $titleLength > 0 ? \Illuminate\Support\Str::limit($featuredPost->name, $titleLength) : $featuredPost->name }}
                                                                        </a>
                                                                    </h2>

                                                                    {{-- Meta Data (Clean Line) --}}
                                                                    @if ($postMeta)
                                                                        <div class="flex flex-wrap items-center gap-3 text-xs font-medium text-slate-500 dark:text-slate-400">
                                                                             <span class="flex items-center gap-1">
                                                                                <i class="fa-regular fa-calendar opacity-70"></i>
                                                                                {{ the_date($featuredPost, 'diff') }}
                                                                             </span>
                                                                            <span class="text-slate-300 dark:text-slate-600">•</span>
                                                                            <span class="flex items-center gap-1 text-primary-600 dark:text-primary-400">
                                                                                {!! the_category($featuredPost) !!}
                                                                             </span>
                                                                            <span class="text-slate-300 dark:text-slate-600">•</span>
                                                                            <span>{{ the_view_count($featuredPost, 'ভিউ') }}</span>
                                                                        </div>
                                                                    @endif

                                                                    {{-- Excerpt --}}
                                                                    @if ($showExcerpt)
                                                                        <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed line-clamp-3">
                                                                            {{ $excerptLength > 0 ? \Illuminate\Support\Str::limit($featuredPost->excerpt, $excerptLength) : $featuredPost->excerpt }}
                                                                        </p>
                                                                    @endif

                                                                    {{-- Read More Button --}}
                                                                    @if ($readMoreButton)
                                                                        <div class="pt-1">
                                                                            <a href="{{ post_permalink($featuredPost) }}" class="inline-flex items-center gap-1 text-xs font-bold uppercase tracking-wider text-primary-600 hover:text-primary-700 dark:text-primary-400 transition-colors" wire:navigate>
                                                                                বিস্তারিত পড়ুন <i class="fa-solid fa-arrow-right-long mt-0.5"></i>
                                                                            </a>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </article>
                                                        @endif

                                                        {{-- === Sidebar Posts (Right Side) === --}}
                                                        <div class="flex flex-col gap-5 border-t lg:border-t-0 border-slate-100 dark:border-slate-800 pt-6 lg:pt-0 pl-0 lg:pl-4">
                                                            @foreach ($sidebarPosts as $index => $post)
                                                                @php
                                                                    $shouldHideThumb = $hideSmallThumbnails && $index >= 0;
                                                                @endphp
                                                                <article class="group flex gap-4 items-start relative">
                                                                    {{-- Thumbnail --}}
                                                                    @unless ($shouldHideThumb)
                                                                        <div class="shrink-0 relative w-24 h-16 sm:w-28 sm:h-20 rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-800 border border-slate-100 dark:border-slate-700/50">
                                                                            <a href="{{ post_permalink($post) }}" wire:navigate class="block w-full h-full">
                                                                                <img src="{{ the_thumbnail($post) }}"
                                                                                     alt="{{ $post->name }}"
                                                                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                                                            </a>
                                                                            @if ($mediaIcon)
                                                                                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                                                                    <span class="bg-black/50 rounded-full p-1.5 backdrop-blur-sm">
                                                                                        <i class="fa-solid fa-play text-[8px] text-white pl-0.5"></i>
                                                                                    </span>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    @endunless

                                                                    {{-- Content --}}
                                                                    <div class="flex-1 min-w-0 flex flex-col justify-center h-full">
                                                                        <h2 class="text-sm sm:text-base font-semibold text-slate-700 dark:text-slate-200 leading-snug line-clamp-2 mb-1.5">
                                                                            <a href="{{ post_permalink($post) }}" wire:navigate class="group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                                                                                {{ $titleLength > 0 ? \Illuminate\Support\Str::limit($post->name, $titleLength) : $post->name }}
                                                                            </a>
                                                                        </h2>

                                                                        @if ($postMeta)
                                                                            <div class="text-[11px] text-slate-400 font-medium flex items-center gap-2">
                                                                                <span>{{ the_date($post, 'diff') }}</span>
                                                                                {{-- Optional: Add category or views here if needed --}}
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </article>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    @break
                                                    {{-- === STACKED LAYOUT (Modern Cards) === --}}
                                                @case('stacked')
                                                    <div class="space-y-4">
                                                        @foreach ($postItems as $index => $post)
                                                            @php
                                                                $shouldHideThumb = ($hideFirstThumbnail && $index === 0) || ($hideSmallThumbnails && $index > 0);
                                                            @endphp
                                                            <article class="group relative flex gap-4 p-4 rounded-xl bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700/50 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-0.5">

                                                                {{-- Thumbnail Section --}}
                                                                @unless ($shouldHideThumb)
                                                                    <div class="relative shrink-0 w-28 h-20 sm:w-32 sm:h-24 rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-700">
                                                                        <a href="{{ post_permalink($post) }}" wire:navigate class="block w-full h-full">
                                                                            <img src="{{ the_thumbnail($post) }}"
                                                                                 alt="{{ $post->name }}"
                                                                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                                                        </a>
                                                                        @if ($mediaIcon)
                                                                            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                <span class="bg-black/50 backdrop-blur-sm rounded-full p-1.5">
                                    <svg class="w-3 h-3 text-white pl-0.5" fill="currentColor" viewBox="0 0 16 16"><path d="m11.596 8.697-6.363 3.692c-.54.313-1.233-.066-1.233-.697V4.308c0-.63.692-1.01 1.233-.696l6.363 3.692a.802.802 0 0 1 0 1.393z"/></svg>
                                </span>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                @endunless

                                                                {{-- Content Section --}}
                                                                <div class="flex-1 min-w-0 flex flex-col justify-center">
                                                                    {{-- Title --}}
                                                                    <h2 class="text-sm sm:text-base font-semibold text-slate-800 dark:text-slate-100 leading-snug mb-1.5">
                                                                        <a href="{{ post_permalink($post) }}" wire:navigate class="group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors line-clamp-2">
                                                                            {{ $titleLength > 0 ? \Illuminate\Support\Str::limit($post->name, $titleLength) : $post->name }}
                                                                        </a>
                                                                    </h2>

                                                                    {{-- Meta --}}
                                                                    @if ($postMeta)
                                                                        <div class="flex items-center gap-2 text-[11px] text-slate-400 font-medium mb-1.5">
                            <span class="flex items-center gap-1">
                                <svg class="w-3 h-3 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                {{ the_date($post, 'diff') }}
                            </span>
                                                                        </div>
                                                                    @endif

                                                                    {{-- Excerpt --}}
                                                                    @if ($showExcerpt)
                                                                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed line-clamp-2 hidden sm:block">
                                                                            {{ $excerptLength > 0 ? \Illuminate\Support\Str::limit($post->excerpt, $excerptLength) : $post->excerpt }}
                                                                        </p>
                                                                    @endif

                                                                    {{-- Read More --}}
                                                                    @if ($readMoreButton)
                                                                        <div class="mt-auto pt-1">
                                                                            <a href="{{ post_permalink($post) }}" wire:navigate class="text-[10px] font-bold uppercase tracking-wider text-primary-600 hover:text-primary-700 dark:text-primary-400 transition-colors">
                                                                                আরও পড়ুন &rarr;
                                                                            </a>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </article>
                                                        @endforeach
                                                    </div>
                                                    @break


                                                    {{-- === FEATURED LIST LAYOUT (Hero + Grid) === --}}
                                                @case('featured-list')
                                                    @php
                                                        $featuredPost = $postItems->first();
                                                        $listPosts = $postItems->slice(1);
                                                    @endphp

                                                    <div class="flex flex-col gap-8">
                                                        {{-- Featured Hero Item --}}
                                                        @if ($featuredPost)
                                                            <article class="group grid md:grid-cols-12 gap-5 items-start bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-100 dark:border-slate-700/50 shadow-sm hover:shadow-md transition-all">
                                                                {{-- Image --}}
                                                                <div class="md:col-span-5 h-full">
                                                                    <a href="{{ post_permalink($featuredPost) }}" wire:navigate class="block w-full h-48 md:h-full min-h-[180px] rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-700 relative">
                                                                        <img src="{{ the_thumbnail($featuredPost) }}"
                                                                             alt="{{ $featuredPost->name }}"
                                                                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                                                                    </a>
                                                                </div>

                                                                {{-- Content --}}
                                                                <div class="md:col-span-7 flex flex-col justify-center h-full space-y-3">
                                                                    <div class="flex items-center gap-2">
                         <span class="bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400 text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider">
                             Featured
                         </span>
                                                                        @if ($postMeta)
                                                                            <span class="text-[11px] text-slate-400 font-medium">{{ the_date($featuredPost, 'diff') }}</span>
                                                                        @endif
                                                                    </div>

                                                                    <h2 class="text-xl md:text-2xl font-bold text-slate-800 dark:text-slate-100 leading-tight">
                                                                        <a href="{{ post_permalink($featuredPost) }}" wire:navigate class="group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors line-clamp-2">
                                                                            {{ $titleLength > 0 ? \Illuminate\Support\Str::limit($featuredPost->name, $titleLength) : $featuredPost->name }}
                                                                        </a>
                                                                    </h2>

                                                                    @if ($showExcerpt)
                                                                        <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed line-clamp-2">
                                                                            {{ $excerptLength > 0 ? \Illuminate\Support\Str::limit($featuredPost->excerpt, $excerptLength) : $featuredPost->excerpt }}
                                                                        </p>
                                                                    @endif

                                                                    <div class="pt-2">
                                                                        <a href="{{ post_permalink($featuredPost) }}" wire:navigate class="inline-flex items-center gap-1 text-xs font-semibold text-primary-600 dark:text-primary-400 hover:underline">
                                                                            বিস্তারিত <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </article>
                                                        @endif

                                                        {{-- Sub-List Grid --}}
                                                        <div class="grid gap-x-6 gap-y-6 sm:grid-cols-2">
                                                            @foreach ($listPosts as $index => $post)
                                                                @php
                                                                    $shouldHideThumb = $hideSmallThumbnails && $index >= 0;
                                                                @endphp
                                                                <article class="group flex gap-4 items-start border-b sm:border-b-0 border-slate-100 dark:border-slate-800 pb-4 sm:pb-0 last:border-0 last:pb-0">
                                                                    @unless ($shouldHideThumb)
                                                                        <div class="relative shrink-0 w-24 h-16 rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-700">
                                                                            <a href="{{ post_permalink($post) }}" wire:navigate class="block w-full h-full">
                                                                                <img src="{{ the_thumbnail($post) }}"
                                                                                     alt="{{ $post->name }}"
                                                                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                                                            </a>
                                                                            @if ($mediaIcon)
                                                                                <span class="absolute bottom-1 right-1 bg-black/60 rounded text-[8px] px-1 text-white backdrop-blur-sm">▶</span>
                                                                            @endif
                                                                        </div>
                                                                    @endunless

                                                                    <div class="flex-1 min-w-0">
                                                                        <h2 class="text-sm font-bold text-slate-700 dark:text-slate-200 leading-snug mb-1">
                                                                            <a href="{{ post_permalink($post) }}" wire:navigate class="group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors line-clamp-2">
                                                                                {{ $titleLength > 0 ? \Illuminate\Support\Str::limit($post->name, $titleLength) : $post->name }}
                                                                            </a>
                                                                        </h2>
                                                                        @if ($postMeta)
                                                                            <div class="text-[10px] text-slate-400 font-medium">
                                                                                {{ the_date($post, 'diff') }}
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </article>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    @break
                                                    {{-- === HERO LIST LAYOUT (Featured Hero + Grid) === --}}
                                                @case('hero-list')
                                                    @php
                                                        $heroPost = $postItems->first();
                                                        $listPosts = $postItems->slice(1);
                                                    @endphp
                                                    <div class="space-y-6">
                                                        @if ($heroPost)
                                                            <article class="group relative overflow-hidden rounded-2xl shadow-sm">
                                                                <a href="{{ post_permalink($heroPost) }}" wire:navigate class="block w-full h-64 md:h-72 relative">
                                                                    {{-- Image with Zoom --}}
                                                                    <img src="{{ the_thumbnail($heroPost) }}"
                                                                         alt="{{ $heroPost->name }}"
                                                                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">

                                                                    {{-- Improved Gradient Overlay --}}
                                                                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent opacity-90 transition-opacity duration-300 group-hover:opacity-100"></div>
                                                                </a>

                                                                {{-- Content Overlay --}}
                                                                <div class="absolute bottom-0 left-0 p-5 md:p-6 w-full space-y-2">
                                                                    @if ($postMeta)
                                                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md bg-white/20 backdrop-blur-md border border-white/10 text-[10px] font-bold text-white mb-1">
                                                                            {{ the_date($heroPost, 'diff') }}
                                                                        </span>
                                                                    @endif

                                                                    <h2 class="text-xl md:text-2xl font-bold text-white leading-tight drop-shadow-sm">
                                                                        <a href="{{ post_permalink($heroPost) }}" wire:navigate class="block hover:text-primary-400 transition-colors line-clamp-2">
                                                                            {{ $titleLength > 0 ? \Illuminate\Support\Str::limit($heroPost->name, $titleLength) : $heroPost->name }}
                                                                        </a>
                                                                    </h2>
                                                                </div>
                                                            </article>
                                                        @endif

                                                        {{-- Sub Grid --}}
                                                        <div class="grid gap-x-5 gap-y-6 sm:grid-cols-2">
                                                            @foreach ($listPosts as $index => $post)
                                                                @php
                                                                    $shouldHideThumb = ($hideSmallThumbnails && $index >= 0);
                                                                @endphp
                                                                <article class="group flex gap-3.5 items-start">
                                                                    @unless ($shouldHideThumb)
                                                                        <div class="relative shrink-0 w-24 h-16 rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-700">
                                                                            <a href="{{ post_permalink($post) }}" wire:navigate class="block w-full h-full">
                                                                                <img src="{{ the_thumbnail($post)}}"
                                                                                     alt="{{ $post->name }}"
                                                                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                                                            </a>
                                                                            @if ($mediaIcon)
                                                                                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                                                                    <span class="bg-black/50 backdrop-blur-sm rounded-full p-1">
                                                                                        <svg class="w-2.5 h-2.5 text-white pl-0.5" fill="currentColor" viewBox="0 0 16 16"><path d="m11.596 8.697-6.363 3.692c-.54.313-1.233-.066-1.233-.697V4.308c0-.63.692-1.01 1.233-.696l6.363 3.692a.802.802 0 0 1 0 1.393z"/></svg>
                                                                                    </span>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    @endunless

                                                                    <div class="flex-1 min-w-0">
                                                                        <h2 class="text-sm font-bold text-slate-700 dark:text-slate-200 leading-snug mb-1">
                                                                            <a href="{{ post_permalink($post) }}" wire:navigate class="block group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors line-clamp-2">
                                                                                {{ $titleLength > 0 ? \Illuminate\Support\Str::limit($post->name, $titleLength) : $post->name }}
                                                                            </a>
                                                                        </h2>
                                                                        @if ($postMeta)
                                                                            <p class="text-[10px] text-slate-400 font-medium">{{ the_date($post, 'diff') }}</p>
                                                                        @endif
                                                                    </div>
                                                                </article>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    @break

                                                    {{-- === HALF WIDTH LAYOUT (Clean Vertical List) === --}}
                                                @case('half-width')
                                                    @php
                                                        $featuredPost = $postItems->first();
                                                        $listPosts = $postItems->slice(1);
                                                    @endphp
                                                    <div class="flex flex-col gap-6">

                                                        {{-- Featured Top Post --}}
                                                        @if ($featuredPost)
                                                            <article class="group space-y-3">
                                                                @unless ($hideFirstThumbnail)
                                                                    <a href="{{ post_permalink($featuredPost) }}" wire:navigate class="block w-full overflow-hidden rounded-xl bg-slate-100 dark:bg-slate-800">
                                                                        <img src="{{ the_thumbnail($featuredPost) }}"
                                                                             alt="{{ $featuredPost->name }}"
                                                                             class="w-full aspect-video object-cover transition-transform duration-700 group-hover:scale-105">
                                                                    </a>
                                                                @endunless

                                                                <div class="space-y-2">
                                                                    <h2 class="text-xl md:text-2xl font-bold text-slate-800 dark:text-slate-100 leading-tight">
                                                                        <a href="{{ post_permalink($featuredPost) }}" wire:navigate class="block group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                                                                            {{ $titleLength > 0 ? \Illuminate\Support\Str::limit($featuredPost->name, $titleLength) : $featuredPost->name }}
                                                                        </a>
                                                                    </h2>

                                                                    @if ($postMeta)
                                                                        <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 font-medium">
                                                                            <span>{{ the_date($featuredPost, 'diff') }}</span>
                                                                            @if($showExcerpt)<span class="text-slate-300 dark:text-slate-600">•</span>@endif
                                                                        </div>
                                                                    @endif

                                                                    @if ($showExcerpt)
                                                                        <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed line-clamp-2">
                                                                            {{ $excerptLength > 0 ? \Illuminate\Support\Str::limit($featuredPost->excerpt, $excerptLength) : $featuredPost->excerpt }}
                                                                        </p>
                                                                    @endif
                                                                </div>
                                                            </article>
                                                        @endif

                                                        {{-- Vertical List --}}
                                                        @if ($listPosts->isNotEmpty())
                                                            <div class="flex flex-col gap-5 border-t border-slate-100 dark:border-slate-800 pt-5">
                                                                @foreach ($listPosts as $post)
                                                                    <article class="group flex gap-4 items-start">
                                                                        @unless ($hideSmallThumbnails)
                                                                            <div class="shrink-0 relative w-28 h-20 rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-700">
                                                                                <a href="{{ post_permalink($post) }}" wire:navigate class="block w-full h-full">
                                                                                    <img src="{{ the_thumbnail($post) }}"
                                                                                         alt="{{ $post->name }}"
                                                                                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                                                                </a>
                                                                            </div>
                                                                        @endunless

                                                                        <div class="flex-1 min-w-0 flex flex-col justify-center h-20"> {{-- h-20 aligns text vertically with image --}}
                                                                            <h3 class="text-sm sm:text-base font-semibold text-slate-700 dark:text-slate-200 leading-snug mb-1.5">
                                                                                <a href="{{ post_permalink($post) }}" wire:navigate class="block group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors line-clamp-2">
                                                                                    {{ $titleLength > 0 ? \Illuminate\Support\Str::limit($post->name, $titleLength) : $post->name }}
                                                                                </a>
                                                                            </h3>
                                                                            @if ($postMeta)
                                                                                <p class="text-[11px] text-slate-400 font-medium">{{ the_date($post, 'diff') }}</p>
                                                                            @endif
                                                                        </div>
                                                                    </article>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                    @break
                                                @default
                                                    <div class="grid gap-3 sm:grid-cols-2">
                                                        @foreach ($postItems as $index => $post)
                                                            @php
                                                                $shouldHideThumb = ($hideFirstThumbnail && $index === 0) || ($hideSmallThumbnails && $index > 0);
                                                            @endphp
                                                            <article class="flex gap-3">
                                                                @unless ($shouldHideThumb)
                                                                    <div class="relative flex-shrink-0">
                                                                        <a href="{{ post_permalink($post) }}" wire:navigate class="block">
                                                                            <img src="{{ the_thumbnail($post) }}" alt="{{ $post->name }}" class="h-20 w-28 rounded object-cover">
                                                                        </a>
                                                                        @if ($mediaIcon)
                                                                            <span class="absolute bottom-1 right-1 rounded bg-black/70 px-1 text-[10px] text-white">▶</span>
                                                                        @endif
                                                                    </div>
                                                                @endunless
                                                                <div class="space-y-1.5 min-w-0 flex-1">
                                                                    <a href="{{ post_permalink($post) }}" wire:navigate class="text-xs font-bold text-slate-700 hover:text-sky-600 dark:text-slate-200 leading-snug line-clamp-2 transition-colors block">
                                                                        {{ $titleLength > 0 ? \Illuminate\Support\Str::limit($post->name, $titleLength) : $post->name }}
                                                                    </a>
                                                                    @if ($postMeta)
                                                                        <p class="text-[11px] text-slate-400 font-medium">{{ the_date($post, 'diff') }}</p>
                                                                    @endif
                                                                    @if ($showExcerpt)
                                                                        <p class="text-xs text-slate-500 leading-relaxed line-clamp-2">
                                                                            {{ $excerptLength > 0 ? \Illuminate\Support\Str::limit($post->excerpt, $excerptLength) : $post->excerpt }}
                                                                        </p>
                                                                    @endif
                                                                    @if ($readMoreButton)
                                                                        <a href="{{ post_permalink($post) }}" wire:navigate class="text-xs font-semibold text-sky-600 hover:text-sky-500 inline-block transition-colors">Read more</a>
                                                                    @endif
                                                                </div>
                                                            </article>
                                                        @endforeach
                                                    </div>
                                            @endswitch
                                            @if (method_exists($posts, 'links'))
                                                @php
                                                    $paginationMode = $block['pagination_mode'] ?? 'disable';
                                                    $pageName = $block['page_name'] ?? 'page';
                                                @endphp
                                                @if ($paginationMode === 'numeric')
                                                    <div class="pt-3">
                                                        {{ $posts->links() }}
                                                    </div>
                                                @elseif ($paginationMode === 'ajax-next-prev')
                                                    <div class="flex items-center justify-between pt-3 text-xs">
                                                        <button type="button"
                                                                wire:click="previousPage('{{ $pageName }}')"
                                                                wire:loading.attr="disabled"
                                                                @disabled($posts->onFirstPage())
                                                                class="group inline-flex items-center gap-2 cursor-pointer rounded border border-slate-200 bg-white px-3 py-1.5 font-medium text-slate-600 transition-all hover:bg-slate-50 hover:text-slate-900 disabled:opacity-40 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 dark:hover:text-slate-200">

                                                            <i wire:loading wire:target="previousPage('{{ $pageName }}')"
                                                               class="fas fa-circle-notch fa-spin"></i>

                                                            <i wire:loading.remove wire:target="previousPage('{{ $pageName }}')"
                                                               class="fas fa-chevron-left text-[10px] transition-transform group-hover:-translate-x-0.5"></i>

                                                            <span>Previous</span>
                                                        </button>

                                                        <button type="button"
                                                                wire:click="nextPage('{{ $pageName }}')"
                                                                wire:loading.attr="disabled"
                                                                @disabled(! $posts->hasMorePages())
                                                                class="group inline-flex items-center gap-2 cursor-pointer rounded border border-slate-200 bg-white px-3 py-1.5 font-medium text-slate-600 transition-all hover:bg-slate-50 hover:text-slate-900 disabled:opacity-40 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 dark:hover:text-slate-200">

                                                            <span>Next</span>

                                                            <i wire:loading.remove wire:target="nextPage('{{ $pageName }}')"
                                                               class="fas fa-chevron-right text-[10px] transition-transform group-hover:translate-x-0.5"></i>

                                                            <i wire:loading wire:target="nextPage('{{ $pageName }}')"
                                                               class="fas fa-circle-notch fa-spin"></i>
                                                        </button>
                                                    </div>
                                                @elseif (in_array($paginationMode, ['ajax-show-more', 'ajax-load-more'], true))
                                                    <div class="pt-3 text-center">
                                                        <button type="button"
                                                                wire:click="nextPage('{{ $pageName }}')"
                                                                wire:loading.attr="disabled"
                                                                @disabled(! $posts->hasMorePages())
                                                                class="group relative inline-flex items-center justify-center gap-2 cursor-pointer rounded border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-600 transition-all hover:bg-slate-50 hover:text-slate-900 disabled:opacity-40 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 dark:hover:text-slate-200">

                                                            <i wire:loading wire:target="nextPage('{{ $pageName }}')"
                                                               class="fas fa-spinner fa-spin text-slate-500 dark:text-slate-400"></i>

                                                            <i wire:loading.remove wire:target="nextPage('{{ $pageName }}')"
                                                               class="fas {{ $paginationMode === 'ajax-show-more' ? 'fa-eye' : 'fa-plus-circle' }} opacity-70"></i>

                                                            <span wire:loading.remove wire:target="nextPage('{{ $pageName }}')">
                                                                {{ $paginationMode === 'ajax-show-more' ? 'Show More' : 'Load More' }}
                                                            </span>

                                                            <span wire:loading wire:target="nextPage('{{ $pageName }}')">
                                                                Loading...
                                                            </span>
                                                        </button>
                                                    </div>
                                                @endif
                                            @endif
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
                                <aside class="rounded-lg lg:sticky lg:top-24 self-start space-y-6 border-slate-200 bg-slate-50 text-xs text-slate-500 dark:border-slate-700 dark:bg-slate-800">
                                    <!-- Newsletter -->
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

                                    <!-- Social -->
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

                                    <!-- Ad Placeholder -->
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
            <div class="prose prose-slate max-w-none dark:prose-invert prose-headings:tracking-tight prose-headings:font-bold prose-p:leading-relaxed prose-li:leading-relaxed">
                {!! $page->content !!}
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
</div>
