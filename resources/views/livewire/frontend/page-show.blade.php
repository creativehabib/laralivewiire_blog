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

        <div class="prose prose-slate max-w-none dark:prose-invert">
            {!! $page->content !!}
        </div>
    </article>

    <section class="mt-8">
        <h2 class="text-sm font-semibold border-b pb-2 mb-3 border-slate-200 dark:border-slate-700">
            মন্তব্য করুন
        </h2>

        <x-comments.section :commentable="$page" :canonical-url="request()->url()" />
    </section>
</div>
