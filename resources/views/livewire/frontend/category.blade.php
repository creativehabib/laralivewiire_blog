<div class="container px-4 py-6 md:py-8 grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 lg:items-start typography" wire:init="loadCategory">
    <section class="lg:col-span-8 space-y-4">
        @unless($ready)
            <flux:skeleton.group animate="shimmer" class="space-y-4">
                <flux:skeleton class="h-5 w-32 rounded" />
                <flux:skeleton class="h-6 w-40 rounded" />
                <flux:skeleton class="h-52 w-full rounded-xl" />
                <div class="grid md:grid-cols-2 gap-4">
                    @for($i = 0; $i < 4; $i++)
                        <flux:skeleton class="h-44 w-full rounded-xl" />
                    @endfor
                </div>
            </flux:skeleton.group>
        @else
            <div class="mb-3">
                <nav class="text-xs text-gray-500 dark:text-slate-400 mb-1">
                    <a href="{{ route('home') }}" class="hover:text-primary-dark dark:hover:text-primary-light" wire:navigate>হোম</a>
                    <span class="mx-1">/</span>
                    <span>ক্যাটাগরি</span>
                    <span class="mx-1">/</span>
                    <span class="text-primary-dark dark:text-primary-light">{{ $category->name }}</span>
                </nav>
            </div>

            <div class="flex items-center justify-between mb-3">
                <h1 class="text-2xl font-semibold text-primary-dark dark:text-primary-light">
                    {{ $category->name }}
                </h1>
                <span class="text-xs text-slate-500 dark:text-slate-400">
                    মোট খবর: {{ $featurePosts->count() + $latestPosts->total() }}
                </span>
            </div>

            @php
                $featuredPost = $featurePosts->first();
                $postCards = $featurePosts->skip(1)->concat($latestPosts->getCollection());
            @endphp

            @if($featuredPost)
                <article class="bg-white dark:bg-slate-800 rounded-xl shadow-sm overflow-hidden mb-4 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
                    <a href="{{ post_permalink($featuredPost) }}" wire:navigate>
                        <img src="{{ $featuredPost->image_url }}" class="w-full h-52 object-cover" alt="{{ $featuredPost->name }}">
                    </a>
                    <div class="p-4 space-y-2">
                        <h2 class="text-xl font-semibold">
                            <a href="{{ post_permalink($featuredPost) }}" class="hover:text-primary-dark dark:hover:text-primary-light leading-snug" wire:navigate>
                                {{ $featuredPost->name }}
                            </a>
                        </h2>
                        @if($featuredPost->excerpt)
                            <p class="text-sm text-gray-600 dark:text-slate-300">
                                {{ $featuredPost->excerpt }}
                            </p>
                        @endif
                        <div class="text-xs text-gray-500 dark:text-slate-400 flex items-center gap-2">
                            <span>{{ $featuredPost->created_at?->diffForHumans() }}</span>
                            @if($featuredPost->author)
                                <span>•</span>
                                <span>{{ $featuredPost->author->name }}</span>
                            @endif
                        </div>
                    </div>
                </article>
            @endif

            <div class="grid md:grid-cols-2 gap-4">
                @foreach($postCards as $post)
                    <article class="bg-white dark:bg-slate-800 rounded-xl shadow-sm overflow-hidden flex flex-col transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
                        <a href="{{ post_permalink($post) }}" wire:navigate>
                            <img src="{{ $post->image_url }}" class="w-full h-40 object-cover" alt="{{ $post->name }}">
                        </a>
                        <div class="p-3 flex flex-col flex-1 space-y-2">
                            <h3 class="font-semibold text-base leading-snug">
                                <a href="{{ post_permalink($post) }}" class="hover:text-primary-dark dark:hover:text-primary-light" wire:navigate>
                                    {{ $post->name }}
                                </a>
                            </h3>
                            @if($post->excerpt)
                                <p class="text-sm text-gray-600 dark:text-slate-300 flex-1">{{ $post->excerpt }}</p>
                            @endif
                            <div class="mt-auto text-xs text-gray-500 dark:text-slate-400">
                                {{ $post->created_at?->diffForHumans() }}
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $latestPosts->onEachSide(1)->links() }}
            </div>
        @endunless
    </section>

    <aside class="lg:col-span-4 lg:sticky lg:top-28 self-start">
        <div class="space-y-6">
            <section class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-4 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
                <h2 class="text-base font-semibold border-b pb-2 mb-3 border-slate-200 dark:border-slate-700">
                    ক্যাটাগরি
                </h2>
                <ul class="text-sm space-y-1">
                    <li><a href="#" class="text-primary-dark dark:text-primary-light font-semibold">{{ $category->name }}</a></li>
                    <li><a href="#" class="hover:text-primary-dark dark:hover:text-primary-light">আন্তর্জাতিক</a></li>
                    <li><a href="#" class="hover:text-primary-dark dark:hover:text-primary-light">খেলা</a></li>
                    <li><a href="#" class="hover:text-primary-dark dark:hover:text-primary-light">বিনোদন</a></li>
                    <li><a href="#" class="hover:text-primary-dark dark:hover:text-primary-light">তথ্যপ্রযুক্তি</a></li>
                    <li><a href="#" class="hover:text-primary-dark dark:hover:text-primary-light">বাণিজ্য</a></li>
                </ul>
            </section>

            <section class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-4 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
                <h2 class="text-base font-semibold border-b pb-2 mb-3 border-slate-200 dark:border-slate-700">
                    সর্বশেষ খবর
                </h2>
                <div class="space-y-3 text-sm">
                    @foreach($latestPosts->take(3) as $latest)
                        <article class="flex gap-3">
                            <img src="{{ $latest->image_url }}" class="w-20 h-14 object-cover rounded-md" alt="{{ $latest->name }}">
                            <div class="flex-1">
                                <a href="{{ post_permalink($latest) }}" class="font-semibold leading-snug hover:text-primary-dark dark:hover:text-primary-light" wire:navigate>
                                    {{ $latest->name }}
                                </a>
                                <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">
                                    {{ $latest->created_at?->diffForHumans() }}
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>

            <section class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-4 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
                <h2 class="text-base font-semibold border-b pb-2 mb-3 border-slate-200 dark:border-slate-700">
                    ট্রেন্ডিং
                </h2>
                <ul class="space-y-2 text-sm">
                    @foreach($featurePosts->take(3) as $trending)
                        <li>
                            <a href="{{ post_permalink($trending) }}" class="hover:text-primary-dark dark:hover:text-primary-light" wire:navigate>
                                ✔ {{ $trending->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </section>

            <section class="bg-primary-light/70 dark:bg-slate-800 rounded-xl p-4 border border-primary/20 dark:border-slate-700 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
                <h2 class="text-base font-semibold mb-1 text-primary-dark dark:text-primary-light">
                    নিউজলেটার সাবস্ক্রাইব করুন
                </h2>
                <p class="text-xs text-slate-700 dark:text-slate-300 mb-3">
                    {{ $category->name }} বিভাগের গুরুত্বপূর্ণ খবর সরাসরি পেতে ইমেইল দিন।
                </p>
                <form class="space-y-2">
                    <input type="email" placeholder="আপনার ইমেইল" class="w-full px-3 py-2 text-sm rounded-md border border-primary/40 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary" />
                    <button type="submit" class="w-full bg-primary-dark text-white text-sm font-semibold py-2 rounded-md hover:bg-primary">
                        সাবস্ক্রাইব
                    </button>
                </form>
            </section>

            <section class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-4 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
                <h2 class="text-base font-semibold border-b pb-2 mb-3 border-slate-200 dark:border-slate-700">
                    আমাদের সাথে থাকুন
                </h2>
                <div class="grid grid-cols-2 gap-2 text-sm">
                    <a href="#" class="bg-blue-600 text-white py-2 rounded-md text-center font-semibold">Facebook</a>
                    <a href="#" class="bg-red-600 text-white py-2 rounded-md text-center font-semibold">YouTube</a>
                    <a href="#" class="bg-sky-500 text-white py-2 rounded-md text-center font-semibold">Twitter</a>
                    <a href="#" class="bg-green-600 text-white py-2 rounded-md text-center font-semibold">WhatsApp</a>
                </div>
            </section>

            <section class="bg-slate-100 dark:bg-slate-800 border border-dashed border-slate-300 dark:border-slate-600 rounded-xl p-4 text-center text-xs text-slate-500 dark:text-slate-400">
                বিজ্ঞাপনের স্থান
            </section>
        </div>
    </aside>
</div>
