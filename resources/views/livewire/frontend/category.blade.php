<div class="container px-4 py-8" wire:init="loadCategory">
    <div class="flex items-center justify-between mb-6">
        <div>
            <p class="text-sm text-slate-500 dark:text-slate-300">ক্যাটাগরি</p>
            <h1 class="text-3xl font-bold">{{ $category->name }}</h1>
        </div>
        @if($category->description)
            <p class="max-w-2xl text-sm text-slate-600 dark:text-slate-300">{{ $category->description }}</p>
        @endif
    </div>

    @unless($ready)
        <flux:skeleton.group animate="shimmer" class="space-y-6">
            <div class="grid md:grid-cols-2 gap-4">
                @for($i = 0; $i < 2; $i++)
                    <flux:skeleton class="h-40 w-full rounded-xl" />
                @endfor
            </div>
            <div class="grid md:grid-cols-3 gap-4">
                @for($i = 0; $i < 6; $i++)
                    <flux:skeleton class="h-44 w-full rounded-xl" />
                @endfor
            </div>
        </flux:skeleton.group>
    @else
        <div class="grid md:grid-cols-2 gap-4 mb-6">
            @foreach($featurePosts as $post)
                <article class="bg-white dark:bg-slate-900 rounded-xl shadow-sm overflow-hidden flex flex-col">
                    <a href="{{ post_permalink($post) }}">
                        <img src="{{ $post->image_url }}" class="w-full h-48 object-cover" alt="{{ $post->name }}">
                    </a>
                    <div class="p-4 space-y-2 flex-1">
                        <h2 class="text-xl font-semibold leading-snug">
                            <a href="{{ post_permalink($post) }}" class="hover:text-primary-dark dark:hover:text-primary-light">{{ $post->name }}</a>
                        </h2>
                        @if($post->excerpt)
                            <p class="text-sm text-slate-600 dark:text-slate-300">{{ $post->excerpt }}</p>
                        @endif
                        <div class="mt-auto text-xs text-slate-500 dark:text-slate-400 flex items-center gap-2">
                            <span>{{ $post->created_at?->diffForHumans() }}</span>
                            @if($post->author)
                                <span>•</span><span>{{ $post->author->name }}</span>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="grid md:grid-cols-3 gap-4">
            @foreach($latestPosts as $post)
                <article class="bg-white dark:bg-slate-900 rounded-xl shadow-sm overflow-hidden flex flex-col">
                    <a href="{{ post_permalink($post) }}">
                        <img src="{{ $post->image_url }}" class="w-full h-36 object-cover" alt="{{ $post->name }}">
                    </a>
                    <div class="p-3 flex flex-col flex-1">
                        <h3 class="font-semibold text-base mb-1 leading-snug">
                            <a href="{{ post_permalink($post) }}" class="hover:text-primary-dark dark:hover:text-primary-light">{{ $post->name }}</a>
                        </h3>
                        <div class="mt-auto text-xs text-slate-500 dark:text-slate-400">{{ $post->created_at?->diffForHumans() }}</div>
                    </div>
                </article>
            @endforeach
        </div>
    @endunless
</div>
