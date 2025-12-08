<div class="container px-4 py-8" wire:init="loadCategory">
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-lg border border-slate-200/70 dark:border-slate-800/70 overflow-hidden mb-8">
        <div class="relative bg-gradient-to-r from-primary-dark to-secondary-dark text-white p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="text-sm uppercase tracking-wide text-white/80">ক্যাটাগরি</p>
                <h1 class="text-3xl font-bold">{{ $category->name }}</h1>
                @if($category->description)
                    <p class="mt-2 max-w-2xl text-white/90">{{ $category->description }}</p>
                @endif
            </div>
            <div class="flex items-center gap-3">
                <span class="px-4 py-2 rounded-full bg-white/10 border border-white/20">ফিচার পোস্ট: {{ $featurePosts->count() }}</span>
                <span class="px-4 py-2 rounded-full bg-white/10 border border-white/20">নতুন পোস্ট: {{ $latestPosts->count() }}</span>
            </div>
        </div>
    </div>

    @unless($ready)
        <flux:skeleton.group animate="shimmer" class="space-y-6">
            <div class="grid md:grid-cols-2 gap-4">
                @for($i = 0; $i < 2; $i++)
                    <flux:skeleton class="h-44 w-full rounded-xl" />
                @endfor
            </div>
            <div class="grid md:grid-cols-3 gap-4">
                @for($i = 0; $i < 6; $i++)
                    <flux:skeleton class="h-44 w-full rounded-xl" />
                @endfor
            </div>
        </flux:skeleton.group>
    @else
        <div class="grid md:grid-cols-2 gap-6 mb-8">
            @foreach($featurePosts as $post)
                <article class="bg-white dark:bg-slate-900 rounded-xl shadow-md border border-slate-200/70 dark:border-slate-800/70 overflow-hidden flex flex-col">
                    <a href="{{ post_permalink($post) }}" class="relative">
                        <img src="{{ $post->image_url }}" class="w-full h-56 object-cover" alt="{{ $post->name }}">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                        <div class="absolute bottom-3 left-3 flex items-center gap-2 text-white text-xs bg-black/40 px-3 py-1 rounded-full">
                            <i class="fa-regular fa-clock"></i> {{ $post->created_at?->diffForHumans() }}
                        </div>
                    </a>
                    <div class="p-5 space-y-3 flex-1">
                        <div class="flex items-center gap-2 text-xs text-primary-dark dark:text-primary-light font-semibold">
                            @if($post->primaryCategory())
                                <a href="{{ route('categories.show', $post->primaryCategory()->slug) }}">{{ $post->primaryCategory()->name }}</a>
                            @endif
                        </div>
                        <h2 class="text-xl font-bold leading-snug">
                            <a href="{{ post_permalink($post) }}" class="hover:text-primary-dark dark:hover:text-primary-light">{{ $post->name }}</a>
                        </h2>
                        @if($post->excerpt)
                            <p class="text-sm text-slate-600 dark:text-slate-300">{{ $post->excerpt }}</p>
                        @endif
                        <div class="mt-auto text-sm text-slate-500 dark:text-slate-400 flex items-center gap-2">
                            @if($post->author)
                                <span class="inline-flex h-8 w-8 rounded-full bg-primary-light text-primary-dark items-center justify-center font-semibold">{{ strtoupper(mb_substr($post->author->name, 0, 1)) }}</span>
                                <span>{{ $post->author->name }}</span>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-lg border border-slate-200/70 dark:border-slate-800/70 p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold text-slate-900 dark:text-white">সাম্প্রতিক পোস্ট</h3>
                <span class="text-sm text-slate-500 dark:text-slate-400">{{ $latestPosts->total() }} টি পোস্ট</span>
            </div>
            <div class="grid md:grid-cols-3 gap-4">
                @foreach($latestPosts as $post)
                    <article class="rounded-xl overflow-hidden border border-slate-200/70 dark:border-slate-800/70 bg-slate-50 dark:bg-slate-800/60 flex flex-col">
                        <a href="{{ post_permalink($post) }}">
                            <img src="{{ $post->image_url }}" class="w-full h-36 object-cover" alt="{{ $post->name }}">
                        </a>
                        <div class="p-3 flex flex-col flex-1 space-y-2">
                            <h3 class="font-semibold text-base leading-snug">
                                <a href="{{ post_permalink($post) }}" class="hover:text-primary-dark dark:hover:text-primary-light">{{ $post->name }}</a>
                            </h3>
                            <div class="mt-auto text-xs text-slate-500 dark:text-slate-400 flex items-center gap-2">
                                <i class="fa-regular fa-clock"></i> {{ $post->created_at?->diffForHumans() }}
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $latestPosts->onEachSide(1)->links() }}
            </div>
        </div>
    @endunless
</div>
