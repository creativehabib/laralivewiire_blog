<div class="container px-4 py-8" wire:init="loadTag">
    <div class="flex items-center justify-between mb-6">
        <div>
            <p class="text-sm text-slate-500 dark:text-slate-300">ট্যাগ</p>
            <h1 class="text-3xl font-bold">{{ $tag?->name ?? 'লোড হচ্ছে...' }}</h1>
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
                <article class="bg-white dark:bg-slate-900 rounded-xl shadow-sm overflow-hidden flex flex-col">
                    <a href="{{ post_permalink($post) }}">
                        <img src="{{ $post->image_url }}" class="w-full h-36 object-cover" alt="{{ $post->name }}">
                    </a>
                    <div class="p-3 flex flex-col flex-1">
                        <div class="text-xs text-primary-dark dark:text-primary-light font-semibold mb-1">
                            @if($post->primaryCategory())
                                <a href="{{ route('categories.show', $post->primaryCategory()->slug) }}">{{ $post->primaryCategory()->name }}</a>
                            @endif
                        </div>
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
