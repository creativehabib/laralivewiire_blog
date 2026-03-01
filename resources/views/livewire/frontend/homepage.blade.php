<div class="container px-4 py-8 md:py-10 grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 lg:items-start typography" wire:init="loadHomepage">
    <div class="lg:col-span-8 space-y-8" wire:loading>
        <flux:skeleton.group animate="shimmer" class="grid md:grid-cols-3 gap-4">
            <div class="md:col-span-2 space-y-3">
                <flux:skeleton class="h-72 w-full rounded-xl" />
                <flux:skeleton.line />
                <flux:skeleton.line class="w-1/2" />
                <flux:skeleton.line class="w-2/3" />
            </div>
            <div class="space-y-3">
                @for($i = 0; $i < 4; $i++)
                    <div class="flex gap-3">
                        <flux:skeleton class="w-28 h-24 rounded-lg" />
                        <div class="flex-1 space-y-2">
                            <flux:skeleton.line class="w-3/4" />
                            <flux:skeleton.line />
                        </div>
                    </div>
                @endfor
            </div>
        </flux:skeleton.group>

        <flux:skeleton.group animate="shimmer" class="space-y-6">
            <div class="flex items-center justify-between">
                <flux:skeleton.line class="w-32" />
                <flux:skeleton.line class="w-16" />
            </div>
            <div class="grid sm:grid-cols-2 gap-4">
                <flux:skeleton class="h-52 w-full rounded-xl" />
                <div class="space-y-3">
                    @for($i = 0; $i < 3; $i++)
                        <div class="flex gap-3">
                            <flux:skeleton class="w-24 h-20 rounded-lg" />
                            <div class="flex-1 space-y-2">
                                <flux:skeleton.line />
                                <flux:skeleton.line class="w-2/3" />
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </flux:skeleton.group>

        <flux:skeleton.group animate="shimmer" class="space-y-4">
            <div class="flex items-center justify-between">
                <flux:skeleton.line class="w-28" />
                <flux:skeleton.line class="w-20" />
            </div>
            <div class="grid md:grid-cols-3 gap-4">
                @for($i = 0; $i < 6; $i++)
                    <div class="space-y-2">
                        <flux:skeleton class="h-32 w-full rounded-xl" />
                        <flux:skeleton.line />
                        <flux:skeleton.line class="w-1/2" />
                    </div>
                @endfor
            </div>
        </flux:skeleton.group>
    </div>

    <div class="lg:col-span-8 space-y-8" wire:loading.remove>
        <section>
            <div class="grid md:grid-cols-3 gap-4">
                @if($featuredPost)
                    <article class="md:col-span-2 bg-white dark:bg-slate-800 rounded-xl shadow-sm overflow-hidden">
                        <a href="{{ post_permalink($featuredPost) }}" class="flex-shrink-0" wire:navigate>
                            <img src="{{ the_thumbnail($featuredPost, 1200, 675) }}" alt="{{ $featuredPost->name }}" width="1200" height="675" fetchpriority="high" class="w-full h-56 md:h-72 object-cover">
                        </a>
                        <div class="p-4 space-y-2">
                            @if($featuredPost->primaryCategory())
                                <a href="{{ route('categories.show', $featuredPost->primaryCategory()->slug) }}" class="inline-block text-xs font-semibold uppercase text-primary-dark dark:text-primary-light" wire:navigate>
                                    {{ $featuredPost->primaryCategory()->name }}
                                </a>
                            @endif
                            <h2 class="text-xl md:text-2xl font-semibold leading-snug line-clamp-1">
                                <a href="{{ post_permalink($featuredPost) }}" class="hover:text-primary-dark dark:hover:text-primary-light" wire:navigate>{{ $featuredPost->name }}</a>
                            </h2>
                            @if($featuredPost->excerpt)
                                <p class="text-sm text-slate-600 dark:text-slate-300">{{ $featuredPost->excerpt }}</p>
                            @endif
                            <div class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-2">
                                <span>{{ $featuredPost->created_at?->diffForHumans() }}</span>
                                @if($featuredPost->author?->name)
                                    <span>•</span><span>{{ $featuredPost->author->name }}</span>
                                @endif
                                <span>•</span>
                                <span><i class="fa-regular fa-eye"></i> {{ number_format($featuredPost->views ?? 0) }} ভিউ</span>
                            </div>
                        </div>
                    </article>
                @endif
                    <div class="space-y-3">
                        @forelse($headlinePosts as $post)
                            <article class="bg-white items-center dark:bg-slate-800 rounded-xl shadow-sm overflow-hidden flex gap-3">
                                <a href="{{ post_permalink($post) }}" class="flex-shrink-0" wire:navigate>
                                    <img src="{{ the_thumbnail($post, 280, 240) }}" loading="lazy" width="280" height="240"
                                         class="w-28 h-24 object-cover group-hover:opacity-85 transition duration-300" alt="{{ $post->name }}">
                                </a>
                                <div class="p-2 pr-3">
                                    @if($post->primaryCategory())
                                        <a href="{{ route('categories.show', $post->primaryCategory()->slug) }}" class="text-xs text-primary-dark dark:text-primary-light font-semibold block" wire:navigate>{{ $post->primaryCategory()->name }}</a>
                                    @endif
                                    <h3 class="text-sm font-semibold leading-snug line-clamp-1">
                                        <a href="{{ post_permalink($post) }}" class="hover:text-primary-dark dark:hover:text-primary-light line-clamp-3" wire:navigate>{{ $post->name }}</a>
                                    </h3>
                                    <div class="text-[11px] text-slate-500 dark:text-slate-400 flex items-center gap-2 mt-1">
                                        <span>{{ $post->created_at?->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </article>
                        @empty
                            <p class="text-sm text-slate-600 dark:text-slate-300">এই মুহূর্তে কোনো শিরোনাম খুঁজে পাওয়া যায়নি।</p>
                        @endforelse
                    </div>
            </div>
        </section>

        @if($primaryCategory && $primaryCategory->posts->isNotEmpty())
            @php
                $featuredCategoryPost = $primaryCategory->posts->first();
            @endphp
            <section>
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-lg font-semibold border-b-2 border-primary-dark inline-block pb-1">{{ $primaryCategory->name }}</h2>
                    <a href="{{ route('categories.show', $primaryCategory->slug) }}" class="text-xs text-primary-dark dark:text-primary-light hover:underline" wire:navigate>আরও দেখুন</a>
                </div>
                <div class="grid sm:grid-cols-2 gap-4">
                    @if($featuredCategoryPost)
                        <article class="bg-white dark:bg-slate-800 rounded-xl shadow-sm overflow-hidden">
                            <a href="{{ post_permalink($featuredCategoryPost) }}" class="flex-shrink-0" wire:navigate>
                                <img src="{{ the_thumbnail($featuredCategoryPost, 1200, 675) }}" loading="lazy" width="1200" height="675" class="w-full h-56 object-cover" alt="{{ $featuredCategoryPost->name }}">
                            </a>
                            <div class="p-4">
                                <h3 class="text-base font-semibold mb-1">
                                    <a href="{{ post_permalink($featuredCategoryPost) }}" class="hover:text-primary-dark dark:hover:text-primary-light line-clamp-2" wire:navigate>{{ $featuredCategoryPost->name }}</a>
                                </h3>
                                @if($featuredCategoryPost->excerpt)
                                    <p class="text-sm text-slate-600 dark:text-slate-300 line-clamp-4">{{ $featuredCategoryPost->excerpt }}</p>
                                @endif
                                <div class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-2">
                                    <span>{{ $featuredCategoryPost->created_at?->diffForHumans() }}</span>
                                    @if($featuredCategoryPost->author?->name)
                                        <span>•</span><span>{{ $featuredCategoryPost->author->name }}</span>
                                    @endif
                                    <span>•</span>
                                    <span><i class="fa-regular fa-eye"></i> {{ number_format($featuredCategoryPost->views ?? 0) }} ভিউ</span>
                                </div>
                            </div>
                        </article>
                    @endif
                    <div class="space-y-3">
                        @foreach($primaryCategory->posts->skip(1) as $post)
                            <article class="flex gap-3 bg-white dark:bg-slate-800 rounded-xl shadow-sm overflow-hidden">
                                <a href="{{ post_permalink($post) }}" class="flex-shrink-0" wire:navigate>
                                    <img src="{{ the_thumbnail($post, 320, 240) }}" loading="lazy" width="320" height="240" class="w-32 h-24 object-cover" alt="{{ $post->name }}">
                                </a>
                                <div class="p-2 text-sm">
                                    <h4 class="font-semibold leading-snug line-clamp-2">
                                        <a href="{{ post_permalink($post) }}" class="hover:text-primary-dark dark:hover:text-primary-light" wire:navigate>{{ $post->name }}</a>
                                    </h4>
                                    <div class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-2">
                                        <span>{{ $post->created_at?->diffForHumans() }}</span>
                                        <span>•</span>
                                        <span><i class="fa-regular fa-eye"></i> {{ number_format($post->views ?? 0) }} ভিউ</span>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        <section>
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-lg font-semibold border-b-2 border-primary-dark inline-block pb-1">সর্বশেষ খবর</h2>
                <a href="#" class="text-xs text-primary-dark dark:text-primary-light hover:underline">সব খবর দেখুন</a>
            </div>
            <div class="grid md:grid-cols-3 gap-4">
                @forelse($latestPosts->take(6) as $post)
                    <article class="bg-white dark:bg-slate-800 rounded-xl shadow-sm overflow-hidden flex flex-col">
                        <a href="{{ post_permalink($post) }}" wire:navigate>
                            <img src="{{ the_thumbnail($post, 800, 320) }}" loading="lazy" width="800" height="320" class="w-full h-32 object-cover" alt="{{ $post->name }}">
                        </a>
                        <div class="p-3 flex flex-col flex-1">
                            <h3 class="font-semibold text-sm mb-1 leading-snug">
                                <a href="{{ post_permalink($post) }}" class="hover:text-primary-dark dark:hover:text-primary-light" wire:navigate>{{ $post->name }}</a>
                            </h3>
                            <div class="mt-auto text-xs text-slate-500 dark:text-slate-400 flex items-center gap-2">
                                <span>{{ $post->created_at?->diffForHumans() }}</span>
                                <span>•</span>
                                <span><i class="fa-regular fa-eye"></i> {{ number_format($post->views ?? 0) }} ভিউ</span>
                            </div>
                        </div>
                    </article>
                @empty
                    <p class="text-sm text-slate-600 dark:text-slate-300">নতুন কোনো পোস্ট পাওয়া যায়নি।</p>
                @endforelse
            </div>
        </section>

        @if($videoPosts->isNotEmpty())
            <section>
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-lg font-semibold border-b-2 border-primary-dark inline-block pb-1">ভিডিও খবর</h2>
                    <a href="#" class="text-xs text-primary-dark dark:text-primary-light hover:underline">সব ভিডিও দেখুন</a>
                </div>
                <div class="relative">
                    <button
                        id="videoCarouselPrev"
                        type="button"
                        class="flex items-center justify-center absolute left-1 sm:left-0 top-1/2 -translate-y-1/2 z-10
                            w-8 h-8 rounded-full bg-white dark:bg-slate-800 shadow border border-slate-200
                            dark:border-slate-600 text-slate-700 dark:text-slate-100 hover:bg-slate-100
                            dark:hover:bg-slate-700 disabled:opacity-40 disabled:cursor-not-allowed">
                        <i class="fa-solid fa-chevron-left text-xs"></i>
                    </button>
                    <button
                        id="videoCarouselNext"
                        type="button"
                        class="flex items-center justify-center absolute right-1 sm:right-0 top-1/2 -translate-y-1/2 z-10
                            w-8 h-8 rounded-full bg-white dark:bg-slate-800 shadow border border-slate-200
                            dark:border-slate-600 text-slate-700 dark:text-slate-100 hover:bg-slate-100
                            dark:hover:bg-slate-700 disabled:opacity-40 disabled:cursor-not-allowed">
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </button>

                    <div
                        id="videoCarousel"
                        class="flex gap-4 overflow-x-auto scroll-smooth pb-2
                            snap-x snap-mandatory
                            scrollbar-thin scrollbar-thumb-slate-300 dark:scrollbar-thumb-slate-600">

                        @foreach($videoPosts as $video)
                            <article
                                class="relative bg-white dark:bg-slate-800 rounded-xl shadow-sm overflow-hidden group
                                    min-w-[85%] sm:min-w-[48%] md:min-w-[32%] snap-start">
                                <div class="relative">
                                    <img src="{{ the_thumbnail($video, 960, 360) }}" loading="lazy" width="960" height="360"
                                         alt="{{ $video->name }}"
                                         class="w-full h-36 object-cover group-hover:scale-105 transition-transform duration-300">
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <div class="w-12 h-12 bg-black/60 rounded-full flex items-center justify-center">
                                            <span class="inline-block ml-1 w-0 h-0 border-t-8 border-b-8 border-l-[14px] border-t-transparent border-b-transparent border-l-white"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-3">
                                    <div class="text-[11px] text-slate-500 dark:text-slate-400 flex items-center gap-2 mb-1">
                                        <span>প্রকাশিত: {{ $video->created_at?->diffForHumans() }}</span>
                                        <span>•</span>
                                        <span><i class="fa-regular fa-eye"></i> {{ number_format($video->views ?? 0) }} ভিউ</span>
                                    </div>
                                    <h3 class="text-sm font-semibold leading-snug">
                                        <a href="{{ post_permalink($video) }}" class="hover:text-primary-dark dark:hover:text-primary-light" wire:navigate>{{ $video->name }}</a>
                                    </h3>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        @php
            $categoryColumns = collect([$primaryCategory, $secondaryCategory])->filter()->unique('id');
        @endphp

        @if($categoryColumns->isNotEmpty())
            <div class="grid md:grid-cols-2 gap-6">
                @foreach($categoryColumns as $category)
                    @php($columnFeatured = $category->posts->first())
                    <div class="space-y-4 bg-white dark:bg-slate-800 rounded-xl shadow-sm p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h2 class="text-lg font-semibold border-b-2 border-primary-dark inline-block pb-1">{{ $category->name }}</h2>
                            <a href="{{ route('categories.show', $category->slug) }}" class="text-xs text-primary-dark dark:text-primary-light hover:underline" wire:navigate>সব দেখুন</a>
                        </div>
                        @if($columnFeatured)
                            <article class="relative">
                                <img src="{{ the_thumbnail($columnFeatured, 1200, 675) }}" loading="lazy" width="1200" height="675"
                                     class="w-full h-56 md:h-56 object-cover" alt="{{ $columnFeatured->name }}">

                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
                                    <h2 class="text-white text-lg md:text-xl font-semibold leading-snug">
                                        <a href="{{ post_permalink($columnFeatured) }}" class="hover:text-primary-light" wire:navigate>{{ $columnFeatured->name }}</a>
                                    </h2>
                                    <div class="text-[11px] text-slate-100/90 flex items-center gap-2 mt-1">
                                        <span>{{ $columnFeatured->created_at?->diffForHumans() }}</span>
                                        <span>•</span>
                                        <span><i class="fa-regular fa-eye"></i> {{ number_format($columnFeatured->views ?? 0) }} ভিউ</span>
                                    </div>
                                </div>
                            </article>
                        @endif

                        <div class="divide-y divide-slate-200 dark:divide-slate-700">
                            @foreach($category->posts->skip(1) as $post)
                                <article class="flex gap-3 py-3 hover:bg-slate-50 dark:hover:bg-slate-700/70">
                                    <a href="{{ post_permalink($post) }}" wire:navigate>
                                        <img src="{{ the_thumbnail($post, 240, 160) }}" loading="lazy" width="240" height="160" class="w-24 h-16 object-cover rounded-md" alt="{{ $post->name }}">
                                    </a>
                                    <div class="flex-1">
                                        <h3 class="text-sm font-semibold leading-snug">
                                            <a href="{{ post_permalink($post) }}" class="hover:text-primary-dark dark:hover:text-primary-light" wire:navigate>{{ $post->name }}</a>
                                        </h3>
                                        <div class="text-[11px] text-slate-500 dark:text-slate-400 flex items-center gap-2 mt-1">
                                            <span>{{ $post->created_at?->diffForHumans() }}</span>
                                            <span>•</span>
                                            <span><i class="fa-regular fa-eye"></i> {{ number_format($post->views ?? 0) }} ভিউ</span>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <aside class="lg:col-span-4 space-y-6" wire:loading>
        <flux:skeleton.group animate="shimmer" class="space-y-4">
            <flux:skeleton.line class="h-5 w-24" />
            <div class="space-y-2">
                @for($i = 0; $i < 5; $i++)
                    <flux:skeleton.line />
                @endfor
            </div>
        </flux:skeleton.group>

        <flux:skeleton.group animate="shimmer" class="space-y-3">
            <flux:skeleton.line class="h-5 w-32" />
            <flux:skeleton class="h-20 w-full rounded-lg" />
            <flux:skeleton.line class="w-1/2" />
            <flux:skeleton.line class="w-3/4" />
        </flux:skeleton.group>

        <flux:skeleton.group animate="shimmer" class="space-y-3">
            <flux:skeleton.line class="h-5 w-28" />
            <div class="grid grid-cols-2 gap-2">
                @for($i = 0; $i < 4; $i++)
                    <flux:skeleton class="h-10 rounded-md" />
                @endfor
            </div>
        </flux:skeleton.group>

        <flux:skeleton.group animate="shimmer" class="space-y-3">
            <flux:skeleton.line class="h-5 w-24" />
            <flux:skeleton class="h-12 w-full rounded-xl" />
        </flux:skeleton.group>
    </aside>

    <aside class="lg:col-span-4 lg:sticky lg:top-24 self-start" wire:loading.remove>
        <div class="space-y-6">

            <section class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-4">
                <h2 class="text-base font-semibold border-b pb-2 mb-3 border-slate-200 dark:border-slate-700">ট্রেন্ডিং</h2>
                <ul class="space-y-2 text-sm">
                    @forelse($breakingNews as $post)
                        <li>
                            <a href="{{ post_permalink($post) }}"
                               class="group inline-flex items-center gap-2 transition-transform duration-200 hover:translate-x-1 hover:text-primary-dark dark:hover:text-primary-light"
                               wire:navigate>
                                <x-app-logo-icon class="size-3.5 text-primary-dark dark:text-primary-light" />
                                <span>{{ $post->name }}</span>
                            </a>
                        </li>
                    @empty
                        <li class="text-slate-500">কোনো ব্রেকিং নিউজ নেই</li>
                    @endforelse
                </ul>
            </section>

            <section class="bg-primary-light/70 dark:bg-slate-800 rounded-xl p-4 border border-primary/20 dark:border-slate-700">
                <h2 class="text-base font-semibold mb-1 text-primary-dark dark:text-primary-light">নিউজলেটার সাবস্ক্রাইব করুন</h2>
                <p class="text-xs text-slate-700 dark:text-slate-300 mb-3">দিনের গুরুত্বপূর্ণ খবর সরাসরি পেতে ইমেইল দিন।</p>
                <form class="space-y-2">
                    <input type="email" placeholder="আপনার ইমেইল" class="w-full px-3 py-2 text-sm rounded-md border border-primary/40 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary" />
                    <button type="submit" class="w-full bg-primary-dark text-white text-sm font-semibold py-2 rounded-md hover:bg-primary">সাবস্ক্রাইব</button>
                </form>
            </section>

            <section class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-4">
                <h2 class="text-base font-semibold border-b pb-2 mb-3 border-slate-200 dark:border-slate-700">আমাদের সাথে থাকুন</h2>
                <div class="grid grid-cols-2 gap-2 text-sm">
                    <a href="#" class="bg-blue-600 text-white py-2 rounded-md text-center font-semibold">Facebook</a>
                    <a href="#" class="bg-red-600 text-white py-2 rounded-md text-center font-semibold">YouTube</a>
                    <a href="#" class="bg-sky-700 text-white py-2 rounded-md text-center font-semibold">Twitter</a>
                    <a href="#" class="bg-green-800 text-white py-2 rounded-md text-center font-semibold">WhatsApp</a>
                </div>
            </section>

            <x-frontends.ramadan-times-widget />

            <section class="bg-slate-100 dark:bg-slate-800 border border-dashed border-slate-300 dark:border-slate-600 rounded-xl p-4 text-center text-xs font-medium text-slate-700 dark:text-slate-300">
                বিজ্ঞাপনের স্থান
            </section>
            <section class="bg-white dark:bg-slate-800 rounded-xl shadow-sm overflow-hidden transition-all duration-200 hover:shadow-md">
                <div class="relative">
                    <div id="sidebarFeaturedCarousel" class="relative">
                        @forelse($headlinePosts->take(3) as $featured)
                            <article data-slide class="{{ $loop->first ? 'block' : 'hidden' }}">
                                <div class="relative">
                                    <img src="{{ the_thumbnail($featured, 1200, 720) }}" loading="lazy" width="1200" height="720"
                                         alt="{{ $featured->name }}"
                                         class="w-full h-60 object-cover">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>
                                    <div class="absolute inset-x-0 bottom-0 p-4">
                                        @if($featured->primaryCategory())
                                            <span class="inline-block text-xs font-semibold text-primary-light mb-1">{{ $featured->primaryCategory()->name }}</span>
                                        @endif
                                        <h2 class="text-base font-semibold leading-snug mb-2 text-white">
                                            <a href="{{ post_permalink($featured) }}" class="hover:text-primary-light" wire:navigate>{{ $featured->name }}</a>
                                        </h2>
                                        @if($featured->excerpt)
                                            <p class="text-[11px] text-slate-100/90">{{ $featured->excerpt }}</p>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        @empty
                            <div class="p-4 text-sm text-slate-500">কোনো ফিচার্ড পোস্ট নেই</div>
                        @endforelse
                    </div>

                    <button
                        id="sidebarFeaturedPrev"
                        aria-label="Previous Slide"
                        type="button"
                        class="absolute left-2 top-1/2 -translate-y-1/2 z-10
                            w-8 h-8 rounded-full bg-white/95 dark:bg-slate-900/95 shadow
                            border border-slate-200 dark:border-slate-700
                            text-slate-700 dark:text-slate-100 flex items-center justify-center
                            hover:bg-slate-100 dark:hover:bg-slate-700
                            disabled:opacity-40 disabled:cursor-not-allowed transition-colors">
                        <i class="fa-solid fa-chevron-left text-xs"></i>
                    </button>

                    <button
                        id="sidebarFeaturedNext"
                        aria-label="Next Slide"
                        type="button"
                        class="absolute right-2 top-1/2 -translate-y-1/2 z-10
                            w-8 h-8 rounded-full bg-white/95 dark:bg-slate-900/95 shadow
                            border border-slate-200 dark:border-slate-700
                            text-slate-700 dark:text-slate-100 flex items-center justify-center
                            hover:bg-slate-100 dark:hover:bg-slate-700
                            disabled:opacity-40 disabled:cursor-not-allowed transition-colors">
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </button>
                </div>
            </section>

            <x-frontends.news-tabs-widget
                :latest-posts="$sidebarLatest"
                :popular-posts="$popularPosts"
            />
        </div>
    </aside>
</div>
