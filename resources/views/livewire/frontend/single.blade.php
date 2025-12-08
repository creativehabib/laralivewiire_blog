<div class="container px-4 py-8" wire:init="loadPost">
    <div class="grid lg:grid-cols-12 gap-8">
        <div class="lg:col-span-8 space-y-6">
            @unless($ready)
                <flux:skeleton.group animate="shimmer" class="space-y-4">
                    <flux:skeleton class="h-10 w-1/3 rounded" />
                    <flux:skeleton class="h-56 w-full rounded-xl" />
                    <flux:skeleton.line />
                    <flux:skeleton.line class="w-3/4" />
                    <flux:skeleton.line class="w-2/3" />
                    <div class="grid sm:grid-cols-2 gap-3 pt-4">
                        <flux:skeleton class="h-32 w-full rounded-xl" />
                        <flux:skeleton class="h-32 w-full rounded-xl" />
                    </div>
                </flux:skeleton.group>
            @else
                <div class="space-y-3">
                    <div class="flex items-center gap-3 text-sm text-slate-600 dark:text-slate-300">
                        @if($post?->primaryCategory())
                            <a href="{{ route('categories.show', $post->primaryCategory()->slug) }}"
                               class="text-primary-dark dark:text-primary-light font-semibold">
                                {{ $post->primaryCategory()->name }}
                            </a>
                        @endif
                        <span>{{ $post?->created_at?->format('d F, Y') }}</span>
                        @if($post?->author)
                            <span>•</span>
                            <a href="{{ route('authors.show', $post->author) }}" class="hover:text-primary-dark dark:hover:text-primary-light">
                                {{ $post->author->name }}
                            </a>
                        @endif
                    </div>
                    <h1 class="text-3xl md:text-4xl font-bold leading-tight">{{ $post?->name }}</h1>
                    @if($post?->excerpt)
                        <p class="text-lg text-slate-700 dark:text-slate-200">{{ $post->excerpt }}</p>
                    @endif
                    <img src="{{ $post?->image_url }}" alt="{{ $post?->name }}" class="w-full rounded-xl shadow" />
                </div>

                <article class="prose max-w-none prose-slate dark:prose-invert prose-headings:mt-6 prose-img:rounded-xl">
                    {!! $post?->content !!}
                </article>

                @if($post && $post->tags->isNotEmpty())
                    <div class="flex flex-wrap gap-2 pt-4">
                        @foreach($post->tags as $tag)
                            <a href="{{ route('tags.show', $tag->slug) }}"
                               class="px-3 py-1 rounded-full bg-primary-light text-primary-dark text-sm font-semibold">
                                #{{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                @endif
            @endunless
        </div>

        <aside class="lg:col-span-4">
            <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm p-4 space-y-4">
                <h2 class="text-lg font-semibold border-b pb-2 border-slate-200 dark:border-slate-700">আরও পড়ুন</h2>

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
                    @forelse($relatedPosts as $related)
                        <article class="flex gap-3 py-2 border-b border-slate-100 dark:border-slate-800 last:border-none">
                            <a href="{{ post_permalink($related) }}">
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
                @endunless
            </div>
        </aside>
    </div>
</div>
