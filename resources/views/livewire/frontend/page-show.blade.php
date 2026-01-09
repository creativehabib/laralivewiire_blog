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
            $builderEnabled = ($builderState['enabled'] ?? false) && !empty($builderState['sections']);
            $builderSections = $builderState['sections'] ?? [];
        @endphp

        @if ($builderEnabled)
            <div class="space-y-6">
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
                                    <div class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 shadow-sm dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200">
                                        <p class="font-semibold">{{ $block['name'] ?? 'Block' }}</p>
                                        @if (!empty($block['layout']))
                                            <p class="text-xs text-slate-500">Layout: {{ $block['layout'] }}</p>
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
