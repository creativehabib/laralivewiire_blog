<div class="container px-4 py-8" wire:init="loadPost">
    <div class="grid lg:grid-cols-12 gap-8">
        <div class="lg:col-span-8 space-y-6">
            @unless($ready)
                <flux:skeleton.group animate="shimmer" class="space-y-4">
                    <flux:skeleton class="h-10 w-1/3 rounded" />
                    <flux:skeleton class="h-64 w-full rounded-2xl" />
                    <flux:skeleton.line />
                    <flux:skeleton.line class="w-3/4" />
                    <flux:skeleton.line class="w-2/3" />
                </flux:skeleton.group>
            @else
                <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-lg border border-slate-200/70 dark:border-slate-800/70 overflow-hidden">
                    <div class="relative">
                        <img src="{{ $post?->image_url }}" alt="{{ $post?->name }}" class="w-full h-80 object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-6 flex flex-wrap items-center gap-3 text-sm text-white">
                            @if($post?->primaryCategory())
                                <a href="{{ route('categories.show', $post->primaryCategory()->slug) }}" class="bg-primary-dark text-white px-3 py-1 rounded-full font-semibold">
                                    {{ $post->primaryCategory()->name }}
                                </a>
                            @endif
                            <span class="flex items-center gap-2 bg-black/30 px-3 py-1 rounded-full">
                                <i class="fa-regular fa-clock"></i>
                                {{ $post?->created_at?->format('d F, Y') }}
                            </span>
                            @if($post?->author)
                                <a href="{{ route('authors.show', $post->author) }}" class="flex items-center gap-2 bg-black/30 px-3 py-1 rounded-full hover:text-primary-light">
                                    <span class="inline-flex h-8 w-8 rounded-full bg-white/20 items-center justify-center font-semibold">{{ strtoupper(mb_substr($post->author->name, 0, 1)) }}</span>
                                    <span>{{ $post->author->name }}</span>
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="p-6 space-y-4">
                        <h1 class="text-3xl md:text-4xl font-bold leading-tight text-slate-900 dark:text-white">{{ $post?->name }}</h1>
                        @if($post?->excerpt)
                            <p class="text-lg text-slate-700 dark:text-slate-200">{{ $post->excerpt }}</p>
                        @endif

                        <article class="prose max-w-none prose-slate dark:prose-invert prose-headings:mt-6 prose-img:rounded-xl">
                            {!! $post?->content !!}
                        </article>

                        @if($post && $post->tags->isNotEmpty())
                            <div class="pt-4 border-t border-slate-200 dark:border-slate-800">
                                <div class="flex flex-wrap gap-2">
                                    @foreach($post->tags as $tag)
                                        <a href="{{ route('tags.show', $tag->slug) }}" class="px-3 py-1 rounded-full bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-100 text-sm font-semibold hover:bg-primary-light hover:text-primary-dark">
                                            #{{ $tag->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if($post?->author)
                            <div class="mt-6 p-4 rounded-xl bg-slate-50 dark:bg-slate-800/70 border border-slate-200 dark:border-slate-700 flex items-center gap-4">
                                <div class="h-14 w-14 rounded-full bg-primary-light text-primary-dark flex items-center justify-center text-xl font-bold">
                                    {{ strtoupper(mb_substr($post->author->name, 0, 1)) }}
                                </div>
                                <div class="space-y-1">
                                    <p class="text-sm text-slate-500 dark:text-slate-300">লেখক</p>
                                    <h3 class="text-lg font-semibold">{{ $post->author->name }}</h3>
                                    @if($post->author->email)
                                        <p class="text-sm text-slate-600 dark:text-slate-400">{{ $post->author->email }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endunless
        </div>

        <aside class="lg:col-span-4 space-y-4">
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-lg border border-slate-200/70 dark:border-slate-800/70 p-5">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white flex items-center gap-2 mb-4">
                    <span class="h-8 w-8 rounded-full bg-primary-light text-primary-dark flex items-center justify-center">☆</span>
                    আরও পড়ুন
                </h2>

                @unless($ready)
                    <flux:skeleton.group animate="shimmer" class="space-y-3">
                        @for($i = 0; $i < 4; $i++)
                            <div class="flex gap-3">
                                <flux:skeleton class="w-20 h-16 rounded-lg" />
                                <div class="flex-1 space-y-2">
                                    <flux:skeleton.line />
                                    <flux:skeleton.line class="w-2/3" />
                                </div>
                            </div>
                        @endfor
                    </flux:skeleton.group>
                @else
                    <div class="space-y-4">
                        @forelse($relatedPosts as $related)
                            <article class="flex gap-3 pb-4 border-b border-slate-100 dark:border-slate-800 last:border-none last:pb-0">
                                <a href="{{ post_permalink($related) }}" class="shrink-0">
                                    <img src="{{ $related->image_url }}" class="w-24 h-20 object-cover rounded-lg" alt="{{ $related->name }}">
                                </a>
                                <div class="text-sm space-y-1">
                                    @if($related->primaryCategory())
                                        <a href="{{ route('categories.show', $related->primaryCategory()->slug) }}" class="text-primary-dark dark:text-primary-light font-semibold">
                                            {{ $related->primaryCategory()->name }}
                                        </a>
                                    @endif
                                    <h3 class="font-semibold leading-snug">
                                        <a href="{{ post_permalink($related) }}" class="hover:text-primary-dark dark:hover:text-primary-light">{{ $related->name }}</a>
                                    </h3>
                                    <div class="text-xs text-slate-500 dark:text-slate-400">{{ $related->created_at?->diffForHumans() }}</div>
                                </div>
                            </article>
                        @empty
                            <p class="text-sm text-slate-600 dark:text-slate-300">আরো কোনো পোস্ট পাওয়া যায়নি।</p>
                        @endforelse
                    </div>
                @endunless
            </div>
        </aside>
    </div>
</div>
