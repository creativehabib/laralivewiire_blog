<div class="container px-4 py-10 md:py-12 max-w-4xl mx-auto typography">
    <article class="space-y-4">
        <header class="space-y-1">
            <p class="text-xs uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">Page</p>
            <h1 class="text-3xl font-semibold text-slate-900 dark:text-slate-100">{{ $page->name }}</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">
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
                <div class="text-center text-xs text-slate-500" wire:loading>
                    Loading...
                </div>
                @foreach ($builderSections as $section)
                    @php
                        $sidebar = $section['sidebar'] ?? 'none';
                        $blocks = $section['blocks'] ?? [];
                        $gridClass = match ($sidebar) {
                            'left' => 'md:grid-cols-[220px_1fr]',
                            'right' => 'md:grid-cols-[1fr_220px]',
                            default => 'md:grid-cols-1',
                        };
                    @endphp
                    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                        <div class="grid gap-4 {{ $gridClass }}">
                            @if ($sidebar === 'left')
                                <aside class="rounded-lg border border-dashed border-slate-200 bg-slate-50 p-3 text-xs text-slate-500 dark:border-slate-700 dark:bg-slate-800">
                                    <p class="font-semibold text-slate-600 dark:text-slate-200">Sidebar</p>
                                    <p>Manage Widgets</p>
                                </aside>
                            @endif

                            <div class="space-y-3">
                                @forelse ($blocks as $block)
                                    @php
                                        $settings = $block['settings'] ?? [];
                                        $blockTitle = data_get($settings, 'title') ?: ($block['name'] ?? 'Block');
                                        $tagsValue = data_get($settings, 'tags');
                                        $posts = $block['posts'] ?? [];
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
                                    <div class="{{ $contentOnly ? 'text-sm text-slate-700 dark:text-slate-200' : 'space-y-3 rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 shadow-sm dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200' }} {{ $darkMode ? 'bg-slate-900 text-slate-100' : '' }}"
                                         style="{{ $blockStyles }}">
                                        <div class="flex items-center justify-between gap-2" style="color: var(--block-primary, inherit);">
                                            <div>
                                                <p class="font-semibold">{{ $titleLength > 0 ? \Illuminate\Support\Str::limit($blockTitle, $titleLength) : $blockTitle }}</p>
                                                @if (!empty($tagsValue))
                                                    <p class="text-xs text-slate-500">Tags: {{ $tagsValue }}</p>
                                                @endif
                                            </div>
                                            @if (!empty($settings['url']) && $moreButton)
                                                <a href="{{ $settings['url'] }}" class="text-xs font-semibold text-sky-600 hover:text-sky-500">View all</a>
                                            @endif
                                        </div>
                                        @if (count($posts))
                                            <div class="grid gap-3 sm:grid-cols-2">
                                                @foreach ($posts as $index => $post)
                                                    @php
                                                        $shouldHideThumb = ($hideFirstThumbnail && $index === 0) || ($hideSmallThumbnails && $index > 0);
                                                    @endphp
                                                    <article class="flex gap-3">
                                                        @unless ($shouldHideThumb)
                                                            <div class="relative">
                                                                <a href="{{ post_permalink($post) }}" class="block">
                                                                    <img src="{{ $post->image_url }}" alt="{{ $post->name }}" class="h-16 w-24 rounded object-cover">
                                                                </a>
                                                                @if ($mediaIcon)
                                                                    <span class="absolute bottom-1 right-1 rounded bg-black/70 px-1 text-[10px] text-white">▶</span>
                                                                @endif
                                                            </div>
                                                        @endunless
                                                        <div class="space-y-1">
                                                            <a href="{{ post_permalink($post) }}" class="text-xs font-semibold text-slate-700 hover:text-sky-600 dark:text-slate-200">
                                                                {{ $titleLength > 0 ? \Illuminate\Support\Str::limit($post->name, $titleLength) : $post->name }}
                                                            </a>
                                                            @if ($postMeta)
                                                                <p class="text-[11px] text-slate-400">{{ $post->created_at?->format('M d, Y') }}</p>
                                                            @endif
                                                            @if ($showExcerpt)
                                                                <p class="text-xs text-slate-500">
                                                                    {{ $excerptLength > 0 ? \Illuminate\Support\Str::limit($post->excerpt, $excerptLength) : $post->excerpt }}
                                                                </p>
                                                            @endif
                                                            @if ($readMoreButton)
                                                                <a href="{{ post_permalink($post) }}" class="text-xs font-semibold text-sky-600 hover:text-sky-500">Read more</a>
                                                            @endif
                                                        </div>
                                                    </article>
                                                @endforeach
                                            </div>
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
                                                                class="rounded border border-slate-200 px-3 py-1 text-slate-600 disabled:opacity-40"
                                                                wire:click="previousPage('{{ $pageName }}')"
                                                                @disabled($posts->onFirstPage())>
                                                            Previous
                                                        </button>
                                                        <button type="button"
                                                                class="rounded border border-slate-200 px-3 py-1 text-slate-600 disabled:opacity-40"
                                                                wire:click="nextPage('{{ $pageName }}')"
                                                                @disabled(! $posts->hasMorePages())>
                                                            Next
                                                        </button>
                                                    </div>
                                                @elseif (in_array($paginationMode, ['ajax-show-more', 'ajax-load-more'], true))
                                                    <div class="pt-3 text-center">
                                                        <button type="button"
                                                                class="rounded border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-600 disabled:opacity-40"
                                                                wire:click="nextPage('{{ $pageName }}')"
                                                                @disabled(! $posts->hasMorePages())>
                                                            {{ $paginationMode === 'ajax-show-more' ? 'Show More' : 'Load More' }}
                                                        </button>
                                                    </div>
                                                @endif
                                            @endif
                                        @else
                                            <p class="text-xs text-slate-500">No posts matched this block settings.</p>
                                        @endif
                                    </div>
                                @empty
                                    <div class="rounded-lg border border-dashed border-slate-200 p-4 text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400">
                                        No blocks added yet.
                                    </div>
                                @endforelse
                            </div>

                            @if ($sidebar === 'right')
                                <aside class="rounded-lg border border-dashed border-slate-200 bg-slate-50 p-3 text-xs text-slate-500 dark:border-slate-700 dark:bg-slate-800">
                                    <p class="font-semibold text-slate-600 dark:text-slate-200">Sidebar</p>
                                    <p>Manage Widgets</p>
                                </aside>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="prose prose-slate max-w-none dark:prose-invert">
                {!! $page->content !!}
            </div>
        @endif
    </article>

    <section class="mt-8">
        <h2 class="text-sm font-semibold border-b pb-2 mb-3 border-slate-200 dark:border-slate-700">
            মন্তব্য করুন
        </h2>
        <x-comments.section :commentable="$page" :canonical-url="request()->url()" />
    </section>
</div>
