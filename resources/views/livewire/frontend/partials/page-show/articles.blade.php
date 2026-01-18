@php
    $layout = $layout ?? 'stacked';
    $postItems = $postItems ?? collect();
    $posts = $posts ?? null;
    $block = $block ?? [];
    $hideSmallThumbnails = $hideSmallThumbnails ?? false;
    $hideFirstThumbnail = $hideFirstThumbnail ?? false;
    $postMeta = $postMeta ?? true;
    $showExcerpt = $showExcerpt ?? true;
    $excerptLength = $excerptLength ?? 0;
    $titleLength = $titleLength ?? 0;
    $readMoreButton = $readMoreButton ?? false;
    $mediaIcon = $mediaIcon ?? false;
@endphp

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
                        <img src="{{ the_thumbnail($featuredPost, 1200, 675) }}"
                             alt="{{ $featuredPost->name }}"
                             loading="eager"
                             fetchpriority="high"
                             decoding="async"
                             width="1200"
                             height="675"
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
                                <span>{{ the_view_count($featuredPost, __('Views')) }}</span>
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
                                    {{ __('Read More') }} <i class="fa-solid fa-arrow-right-long mt-0.5"></i>
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
                                    <img src="{{ the_thumbnail($post, 280, 200) }}"
                                         alt="{{ $post->name }}"
                                         loading="lazy"
                                         decoding="async"
                                         width="280"
                                         height="200"
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

                            {{-- Read More Button --}}
                            @if ($readMoreButton)
                                <div class="pt-1">
                                    <a href="{{ post_permalink($post) }}" class="inline-flex items-center gap-1 text-xs font-bold uppercase tracking-wider text-primary-600 hover:text-primary-700 dark:text-primary-400 transition-colors" wire:navigate>
                                        {{ __('Read More') }} <i class="fa-solid fa-arrow-right-long mt-0.5"></i>
                                    </a>
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
                <article class="group relative flex gap-4 dark:bg-slate-800 transition-all duration-300 hover:-translate-y-0.5">

                    {{-- Thumbnail Section --}}
                    @unless ($shouldHideThumb)
                        <div class="relative shrink-0 w-28 h-20 sm:w-32 sm:h-24 rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-700">
                            <a href="{{ post_permalink($post) }}" wire:navigate class="block w-full h-full">
                                <img src="{{ the_thumbnail($post, 320, 240) }}"
                                     alt="{{ $post->name }}"
                                     loading="lazy"
                                     decoding="async"
                                     width="320"
                                     height="240"
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
                                    {{__('Read More')}} <i class="fa-solid fa-arrow-right-long mt-0.5"></i>
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

        <div class="flex flex-col gap-6 md:gap-8">
            {{-- === Featured Hero Item === --}}
            @if ($featuredPost)
                <article class="group bg-white dark:bg-slate-800 rounded-xl overflow-hidden md:grid md:grid-cols-12 md:gap-6 items-start transition-all">

                    {{-- Image Section --}}
                    <div class="md:col-span-5 relative h-full">
                        <a href="{{ post_permalink($featuredPost) }}" wire:navigate class="block w-full h-full">
                            {{-- Fix: Added min-h-[220px] to prevent image collapse on desktop if text is short --}}
                            <div class="relative w-full aspect-video md:aspect-auto md:h-full min-h-[220px] overflow-hidden bg-slate-100 dark:bg-slate-700 rounded-xl">
                                <img src="{{ the_thumbnail($featuredPost, 1200, 675) }}"
                                     alt="{{ $featuredPost->name }}"
                                     loading="eager"
                                     fetchpriority="high"
                                     decoding="async"
                                     width="1200"
                                     height="675"
                                     class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                            </div>
                        </a>
                    </div>

                    {{-- Content Section --}}
                    <div class="md:col-span-7 flex flex-col justify-center py-3 md:py-1 h-full space-y-3">

                        {{-- Title --}}
                        <h2 class="text-xl md:text-2xl lg:text-3xl font-bold text-slate-800 dark:text-slate-100 leading-tight">
                            <a href="{{ post_permalink($featuredPost) }}" wire:navigate class="group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors line-clamp-2">
                                {{ $titleLength > 0 ? \Illuminate\Support\Str::limit($featuredPost->name, $titleLength) : $featuredPost->name }}
                            </a>
                        </h2>

                        {{-- Meta --}}
                        @if ($postMeta)
                            <div class="flex items-center gap-3 text-xs text-slate-500 dark:text-slate-400 font-medium">
                                <span class="flex items-center gap-1.5">
                                    <i class="fa-regular fa-calendar opacity-70"></i>
                                    {{ the_date($featuredPost, 'diff') }}
                                </span>
                            </div>
                        @endif

                        {{-- Excerpt --}}
                        @if ($showExcerpt)
                            <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed line-clamp-2 md:line-clamp-3">
                                {{ $excerptLength > 0 ? \Illuminate\Support\Str::limit($featuredPost->excerpt, $excerptLength) : $featuredPost->excerpt }}
                            </p>
                        @endif

                        {{-- Read More --}}
                        @if ($readMoreButton)
                            <div class="pt-2 mt-auto">
                                <a href="{{ post_permalink($featuredPost) }}" wire:navigate class="inline-flex items-center gap-1 text-[11px] font-bold uppercase tracking-wider text-primary-600 hover:text-primary-700 dark:text-primary-400 transition-colors">
                                    {{__('Read More')}} <i class="fa-solid fa-arrow-right-long"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                </article>
            @elseif($postItems->isEmpty())
                {{-- 3. EMPTY STATE --}}
                <div class="text-center py-10 text-slate-500 dark:text-slate-400">
                    <p>কোনো পোস্ট পাওয়া যায়নি।</p>
                </div>
            @endif

            {{-- === Sub-List Grid === --}}
            @if($listPosts->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-6">
                    @foreach ($listPosts as $index => $post)
                        @php
                            $shouldHideThumb = $hideSmallThumbnails && $index >= 0;
                        @endphp
                        <article class="group flex gap-4 items-start border-b sm:border-b-0 border-slate-100 dark:border-slate-800 pb-4 sm:pb-0 last:border-0 last:pb-0">

                            {{-- Thumbnail --}}
                            @unless ($shouldHideThumb)
                                <div class="relative shrink-0 w-24 h-16 sm:w-28 sm:h-20 rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-700 shadow-sm border border-slate-100 dark:border-slate-700/50">
                                    <a href="{{ post_permalink($post) }}" wire:navigate class="block w-full h-full">
                                        <img src="{{ the_thumbnail($post, 280, 200) }}"
                                             alt="{{ $post->name }}"
                                             loading="lazy"
                                             decoding="async"
                                             width="280"
                                             height="200"
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

                            {{-- Content --}}
                            <div class="flex-1 min-w-0 flex flex-col justify-center h-full">
                                <h2 class="text-sm sm:text-base font-bold text-slate-700 dark:text-slate-200 leading-snug mb-1.5">
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
            @endif
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
                        <img src="{{ the_thumbnail($heroPost, 1200, 675) }}"
                             alt="{{ $heroPost->name }}"
                             loading="eager"
                             fetchpriority="high"
                             decoding="async"
                             width="1200"
                             height="675"
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
                                    <img src="{{ the_thumbnail($post, 240, 160)}}"
                                         alt="{{ $post->name }}"
                                         loading="lazy"
                                         decoding="async"
                                         width="240"
                                         height="160"
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
                            <img src="{{ the_thumbnail($featuredPost, 1200, 675) }}"
                                 alt="{{ $featuredPost->name }}"
                                 loading="eager"
                                 fetchpriority="high"
                                 decoding="async"
                                 width="1200"
                                 height="675"
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
                                        <img src="{{ the_thumbnail($post, 280, 200) }}"
                                             alt="{{ $post->name }}"
                                             loading="lazy"
                                             decoding="async"
                                             width="280"
                                             height="200"
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
                                <img src="{{ the_thumbnail($post, 280, 200) }}"
                                     alt="{{ $post->name }}"
                                     loading="lazy"
                                     decoding="async"
                                     width="280"
                                     height="200"
                                     class="h-20 w-28 rounded object-cover">
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
