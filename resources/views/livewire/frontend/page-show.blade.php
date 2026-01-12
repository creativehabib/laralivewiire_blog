<div class="container px-4 py-10 md:py-12 max-w-4xl mx-auto typography">
    <article class="space-y-4">
        <header class="space-y-1.5">
            <p class="text-xs uppercase tracking-[0.2em] font-medium text-slate-500 dark:text-slate-400">Page</p>
            <h1 class="text-3xl md:text-4xl font-bold tracking-tight text-slate-900 dark:text-slate-100 leading-tight">{{ $page->name }}</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                Updated {{ optional($page->updated_at ?? $page->created_at)->diffForHumans() }}
            </p>
        </header>

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
                                                    <div class="grid gap-4 md:grid-cols-[1.3fr_1fr]">
                                                        @if ($featuredPost)
                                                            <article class="space-y-2">
                                                                <a href="{{ post_permalink($featuredPost) }}" class="block" wire:navigate>
                                                                    <img src="{{ $featuredPost->image_url }}" alt="{{ $featuredPost->name }}" class="w-full h-56 md:h-72 rounded-lg object-cover">
                                                                </a>
                                                                <div class="space-y-1.5">
                                                                    <h2 class="text-xl md:text-2xl font-semibold leading-snug line-clamp-1">
                                                                        <a href="{{ post_permalink($featuredPost) }}" class="text-slate-700 hover:text-sky-600 dark:text-slate-100  transition-colors block" wire:navigate>
                                                                            {{ $titleLength > 0 ? \Illuminate\Support\Str::limit($featuredPost->name, $titleLength) : $featuredPost->name }}
                                                                        </a>
                                                                    </h2>
                                                                    @if ($postMeta)
                                                                        <p class="text-[11px] text-slate-400 font-medium">{{ $featuredPost->created_at?->diffForHumans() }}</p>
                                                                    @endif
                                                                    @if ($showExcerpt)
                                                                        <p class="text-xs text-slate-500 leading-relaxed line-clamp-2 dark:text-slate-300">
                                                                            {{ $excerptLength > 0 ? \Illuminate\Support\Str::limit($featuredPost->excerpt, $excerptLength) : $featuredPost->excerpt }}
                                                                        </p>
                                                                    @endif
                                                                    @if ($readMoreButton)
                                                                        <a href="{{ post_permalink($featuredPost) }}" class="text-xs font-semibold text-sky-600 hover:text-sky-500 inline-block transition-colors" wire:navigate>Read more</a>
                                                                    @endif
                                                                </div>
                                                            </article>
                                                        @endif
                                                        <div class="space-y-4">
                                                            @foreach ($sidebarPosts as $index => $post)
                                                                @php
                                                                    $shouldHideThumb = $hideSmallThumbnails && $index >= 0;
                                                                @endphp
                                                                <article class="flex gap-3">
                                                                    @unless ($shouldHideThumb)
                                                                        <div class="relative flex-shrink-0">
                                                                            <a href="{{ post_permalink($post) }}" wire:navigate class="block">
                                                                                <img src="{{ $post->image_url }}" alt="{{ $post->name }}" class="h-20 w-28 rounded object-cover">
                                                                            </a>
                                                                            @if ($mediaIcon)
                                                                                <span class="absolute bottom-1 right-1 rounded bg-black/70 px-1 text-[10px] text-white">▶</span>
                                                                            @endif
                                                                        </div>
                                                                    @endunless
                                                                    <div class="space-y-1 min-w-0 flex-1">
                                                                        <h2 class="text-sm font-semibold leading-snug line-clamp-1">
                                                                            <a href="{{ post_permalink($post) }}" wire:navigate class="text-slate-700 hover:text-sky-600 dark:text-slate-200 leading-snug line-clamp-3 transition-colors block">
                                                                                {{ $titleLength > 0 ? \Illuminate\Support\Str::limit($post->name, $titleLength) : $post->name }}
                                                                            </a>
                                                                        </h2>
                                                                        @if ($postMeta)
                                                                            <p class="text-[11px] text-slate-400 font-medium">{{ $post->created_at?->diffForHumans() }}</p>
                                                                        @endif
                                                                    </div>
                                                                </article>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    @break
                                                @case('stacked')
                                                    <div class="space-y-3">
                                                        @foreach ($postItems as $index => $post)
                                                            @php
                                                                $shouldHideThumb = ($hideFirstThumbnail && $index === 0) || ($hideSmallThumbnails && $index > 0);
                                                            @endphp
                                                            <article class="flex gap-3 rounded-lg border border-slate-200 bg-white/60 p-3 shadow-sm dark:border-slate-700 dark:bg-slate-900/40">
                                                                @unless ($shouldHideThumb)
                                                                    <div class="relative flex-shrink-0">
                                                                        <a href="{{ post_permalink($post) }}" wire:navigate class="block">
                                                                            <img src="{{ $post->image_url }}" alt="{{ $post->name }}" class="h-20 w-28 rounded object-cover">
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
                                                                        <p class="text-[11px] text-slate-400 font-medium">{{ $post->created_at?->diffForHumans() }}</p>
                                                                    @endif
                                                                    @if ($showExcerpt)
                                                                        <p class="text-xs text-slate-500 leading-relaxed line-clamp-2 dark:text-slate-300">
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
                                                    @break
                                                @case('featured-list')
                                                    @php
                                                        $featuredPost = $postItems->first();
                                                        $listPosts = $postItems->slice(1);
                                                    @endphp
                                                    <div class="space-y-4">
                                                        @if ($featuredPost)
                                                            <article class="grid gap-3 md:grid-cols-[200px_1fr]">
                                                                <a href="{{ post_permalink($featuredPost) }}" wire:navigate class="block">
                                                                    <img src="{{ $featuredPost->image_url }}" alt="{{ $featuredPost->name }}" class="h-28 w-full rounded-lg object-cover">
                                                                </a>
                                                                <div class="space-y-1.5">
                                                                    <a href="{{ post_permalink($featuredPost) }}" wire:navigate class="text-sm font-bold text-slate-700 hover:text-sky-600 dark:text-slate-100 leading-snug line-clamp-2 transition-colors block">
                                                                        {{ $titleLength > 0 ? \Illuminate\Support\Str::limit($featuredPost->name, $titleLength) : $featuredPost->name }}
                                                                    </a>
                                                                    @if ($postMeta)
                                                                        <p class="text-[11px] text-slate-400 font-medium">{{ $featuredPost->created_at?->diffForHumans() }}</p>
                                                                    @endif
                                                                    @if ($showExcerpt)
                                                                        <p class="text-xs text-slate-600 leading-relaxed line-clamp-2 dark:text-slate-300">
                                                                            {{ $excerptLength > 0 ? \Illuminate\Support\Str::limit($featuredPost->excerpt, $excerptLength) : $featuredPost->excerpt }}
                                                                        </p>
                                                                    @endif
                                                                </div>
                                                            </article>
                                                        @endif
                                                        <div class="grid gap-3 sm:grid-cols-2">
                                                            @foreach ($listPosts as $index => $post)
                                                                @php
                                                                    $shouldHideThumb = $hideSmallThumbnails && $index >= 0;
                                                                @endphp
                                                                <article class="flex gap-3">
                                                                    @unless ($shouldHideThumb)
                                                                        <div class="relative flex-shrink-0">
                                                                            <a href="{{ post_permalink($post) }}" wire:navigate class="block">
                                                                                <img src="{{ $post->image_url }}" alt="{{ $post->name }}" class="h-14 w-20 rounded object-cover">
                                                                            </a>
                                                                            @if ($mediaIcon)
                                                                                <span class="absolute bottom-1 right-1 rounded bg-black/70 px-1 text-[10px] text-white">▶</span>
                                                                            @endif
                                                                        </div>
                                                                    @endunless
                                                                    <div class="space-y-1 min-w-0 flex-1">
                                                                        <a href="{{ post_permalink($post) }}" wire:navigate class="text-xs font-bold text-slate-700 hover:text-sky-600 dark:text-slate-200 leading-snug line-clamp-2 transition-colors block">
                                                                            {{ $titleLength > 0 ? \Illuminate\Support\Str::limit($post->name, $titleLength) : $post->name }}
                                                                        </a>
                                                                        @if ($postMeta)
                                                                            <p class="text-[11px] text-slate-400 font-medium">{{ $post->created_at?->diffForHumans() }}</p>
                                                                        @endif
                                                                    </div>
                                                                </article>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    @break
                                                @case('hero-list')
                                                    @php
                                                        $heroPost = $postItems->first();
                                                        $listPosts = $postItems->slice(1);
                                                    @endphp
                                                    <div class="space-y-4">
                                                        @if ($heroPost)
                                                            <article class="relative overflow-hidden rounded-xl">
                                                                <a href="{{ post_permalink($heroPost) }}" wire:navigate class="block">
                                                                    <img src="{{ $heroPost->image_url }}" alt="{{ $heroPost->name }}" class="h-56 w-full object-cover">
                                                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/70 to-transparent"></div>
                                                                </a>
                                                                <div class="absolute bottom-3 left-3 right-3 space-y-1.5 text-white">
                                                                    <h2 class="text-white text-lg md:text-xl font-semibold leading-snug">
                                                                        <a href="{{ post_permalink($heroPost) }}" wire:navigate class="block hover:text-white/90 transition-colors">
                                                                            {{ $titleLength > 0 ? \Illuminate\Support\Str::limit($heroPost->name, $titleLength) : $heroPost->name }}
                                                                        </a>
                                                                    </h2>
                                                                    @if ($postMeta)
                                                                        <p class="text-[11px] text-white/80 font-medium">{{ $heroPost->created_at?->diffForHumans() }}</p>
                                                                    @endif
                                                                </div>
                                                            </article>
                                                        @endif
                                                        <div class="grid gap-3 sm:grid-cols-2">
                                                            @foreach ($listPosts as $index => $post)
                                                                @php
                                                                    $shouldHideThumb = ($hideSmallThumbnails && $index >= 0);
                                                                @endphp
                                                                <article class="flex gap-3">
                                                                    @unless ($shouldHideThumb)
                                                                        <div class="relative flex-shrink-0">
                                                                            <a href="{{ post_permalink($post) }}" wire:navigate class="block">
                                                                                <img src="{{ $post->image_url }}" alt="{{ $post->name }}" class="h-20 w-28 rounded object-cover">
                                                                            </a>
                                                                            @if ($mediaIcon)
                                                                                <span class="absolute bottom-1 right-1 rounded bg-black/70 px-1 text-[10px] text-white">▶</span>
                                                                            @endif
                                                                        </div>
                                                                    @endunless
                                                                    <div class="space-y-1 min-w-0 flex-1">
                                                                        <h2 class="font-semibold text-sm mb-1 leading-snug">
                                                                            <a href="{{ post_permalink($post) }}" wire:navigate class="text-slate-700 hover:text-sky-600 dark:text-slate-200 leading-snug line-clamp-2 transition-colors block">
                                                                                {{ $titleLength > 0 ? \Illuminate\Support\Str::limit($post->name, $titleLength) : $post->name }}
                                                                            </a>
                                                                        </h2>
                                                                        @if ($postMeta)
                                                                            <p class="text-[11px] text-slate-400 font-medium">{{ $post->created_at?->diffForHumans() }}</p>
                                                                        @endif
                                                                    </div>
                                                                </article>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    @break
                                                @case('half-width')
                                                    @php
                                                        $featuredPost = $postItems->first();
                                                        $listPosts = $postItems->slice(1);
                                                    @endphp
                                                    <div class="space-y-3">
                                                        @if ($featuredPost)
                                                            <article class="space-y-2">
                                                                @unless ($hideFirstThumbnail)
                                                                    <a href="{{ post_permalink($featuredPost) }}" wire:navigate class="hover:text-primary-dark dark:hover:text-primary-light">
                                                                        <img src="{{ $featuredPost->image_url }}" alt="{{ $featuredPost->name }}" class="w-full h-56 rounded-lg object-cover">
                                                                    </a>
                                                                @endunless
                                                                    <div class="space-y-1.5">
                                                                        <h2 class="text-lg md:text-xl mt-3 font-semibold leading-snug">
                                                                            <a href="{{ post_permalink($featuredPost) }}" wire:navigate class="text-slate-700 hover:text-sky-600 dark:text-slate-200 leading-snug line-clamp-2 transition-colors block">
                                                                                {{ $titleLength > 0 ? \Illuminate\Support\Str::limit($featuredPost->name, $titleLength) : $featuredPost->name }}
                                                                            </a>
                                                                        </h2>
                                                                        @if ($postMeta)
                                                                            <p class="text-[11px] text-slate-400 font-medium">{{ $featuredPost->created_at?->diffForHumans() }}</p>
                                                                        @endif
                                                                        @if ($showExcerpt)
                                                                            <p class="text-xs text-slate-500 leading-relaxed line-clamp-2 dark:text-slate-300">
                                                                                {{ $excerptLength > 0 ? \Illuminate\Support\Str::limit($featuredPost->excerpt, $excerptLength) : $featuredPost->excerpt }}
                                                                            </p>
                                                                        @endif
                                                                    </div>
                                                            </article>
                                                        @endif
                                                        @if ($listPosts->isNotEmpty())
                                                            <div class="space-y-2 divide-y divide-slate-200 dark:divide-slate-700">
                                                                @foreach ($listPosts as $post)
                                                                    <div class="">
                                                                        <article class="flex gap-3 py-2 hover:bg-slate-50 dark:hover:bg-slate-700/70">
                                                                            @unless ($hideSmallThumbnails)
                                                                                <div class="flex-shrink-0">
                                                                                    <a href="{{ post_permalink($post) }}" wire:navigate class="block">
                                                                                        <img src="{{ $post->image_url }}" alt="{{ $post->name }}" class="h-20 w-28 rounded object-cover">
                                                                                    </a>
                                                                                </div>
                                                                            @endunless
                                                                            <div class="min-w-0 space-y-1">
                                                                                <h3 class="text-sm font-semibold leading-snug">
                                                                                <a href="{{ post_permalink($post) }}" wire:navigate class="text-slate-700 hover:text-sky-600 dark:text-slate-200 leading-snug line-clamp-2 transition-colors block">
                                                                                    {{ $titleLength > 0 ? \Illuminate\Support\Str::limit($post->name, $titleLength) : $post->name }}
                                                                                </a>
                                                                                </h3>
                                                                                @if ($postMeta)
                                                                                    <p class="text-[11px] text-slate-400 font-medium">{{ $post->created_at?->diffForHumans() }}</p>
                                                                                @endif
                                                                            </div>
                                                                        </article>
                                                                    </div>
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
                                                                            <img src="{{ $post->image_url }}" alt="{{ $post->name }}" class="h-20 w-28 rounded object-cover">
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
                                                                        <p class="text-[11px] text-slate-400 font-medium">{{ $post->created_at?->diffForHumans() }}</p>
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
                                <aside class="rounded-lg border border-dashed border-slate-200 bg-slate-50 p-3 text-xs text-slate-500 dark:border-slate-700 dark:bg-slate-800">
                                    <p class="font-semibold text-slate-600 dark:text-slate-200 mb-1">Sidebar</p>
                                    <p class="leading-relaxed">Manage Widgets</p>
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

    <section class="mt-8">
        <h2 class="text-sm font-bold border-b pb-2 mb-3 border-slate-200 dark:border-slate-700 tracking-tight">
            মন্তব্য করুন
        </h2>
        <x-comments.section :commentable="$page" :canonical-url="request()->url()" />
    </section>
</div>
