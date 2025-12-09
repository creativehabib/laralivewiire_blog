<div class="container px-4 py-8 typography" wire:init="loadTag">
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-lg border border-slate-200/70 dark:border-slate-800/70 overflow-hidden mb-8">
        <div class="bg-gradient-to-r from-primary-dark to-secondary-dark text-white px-6 py-5 flex items-center justify-between">
            <div>
                <p class="text-sm uppercase tracking-wide text-white/80">ট্যাগ</p>
                <h1 class="text-3xl font-bold">{{ $tag?->name ?? 'লোড হচ্ছে...' }}</h1>
            </div>
            <span class="px-4 py-2 rounded-full bg-white/10 border border-white/20 text-sm">{{ $posts->count() }} টি পোস্ট</span>
        </div>
    </div>

    @unless($ready)
        <flux:skeleton.group animate="shimmer" class="space-y-6">
            <div class="grid md:grid-cols-3 gap-4">
                @for($i = 0; $i < 9; $i++)
                    <flux:skeleton class="h-44 w-full rounded-xl" />
                @endfor
            </div>
        </flux:skeleton.group>
    @else
        <div class="grid md:grid-cols-3 gap-4">
            @foreach($posts as $post)
                <article class="bg-white dark:bg-slate-900 rounded-xl shadow-md border border-slate-200/70 dark:border-slate-800/70 overflow-hidden flex flex-col">
                    <a href="{{ post_permalink($post) }}">
                        <img src="{{ $post->image_url }}" class="w-full h-40 object-cover" alt="{{ $post->name }}">
                    </a>
                    <div class="p-4 flex flex-col flex-1 space-y-2">
                        <div class="text-xs text-primary-dark dark:text-primary-light font-semibold mb-1">
                            @if($post->primaryCategory())
                                <a href="{{ route('categories.show', $post->primaryCategory()->slug) }}">{{ $post->primaryCategory()->name }}</a>
                            @endif
                        </div>
                        <h3 class="font-semibold text-lg leading-snug">
                            <a href="{{ post_permalink($post) }}" class="hover:text-primary-dark dark:hover:text-primary-light">{{ $post->name }}</a>
                        </h3>
                        <div class="mt-auto text-xs text-slate-500 dark:text-slate-400 flex items-center gap-2">
                            <i class="fa-regular fa-clock"></i> {{ $post->created_at?->diffForHumans() }}
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @endunless
</div>
