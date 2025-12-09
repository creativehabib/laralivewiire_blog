<main class="container px-4 py-8 md:py-10 grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 typography" wire:init="loadPost">
    <article class="lg:col-span-8 bg-white dark:bg-slate-800 rounded-xl shadow-sm p-4 md:p-6
                         transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
        @unless($ready)
            <flux:skeleton.group animate="shimmer" class="space-y-4">
                <flux:skeleton class="h-10 w-1/3 rounded" />
                <flux:skeleton class="h-64 w-full rounded-2xl" />
                <flux:skeleton.line />
                <flux:skeleton.line class="w-3/4" />
                <flux:skeleton.line class="w-2/3" />
            </flux:skeleton.group>
        @else
        <!-- Breadcrumb -->
        <nav class="text-xs text-gray-500 dark:text-slate-400 mb-3 flex items-center gap-1">
            <a href="{{ route('home') }}" class="hover:text-primary-dark dark:hover:text-primary-light">হোম</a>
            <span>/</span>
            @if($post?->primaryCategory())
                <a href="{{ route('categories.show', $post->primaryCategory()->slug) }}" class="hover:text-primary-dark dark:hover:text-primary-light">
                    {{ $post->primaryCategory()->name }}
                </a>
                <span>/</span>
            @endif
            <span class="text-primary-dark dark:text-primary-light">বিস্তারিত খবর</span>
        </nav>

        <h1 class="text-2xl md:text-3xl font-semibold mb-3 leading-snug">
            {{ $post?->name }}
        </h1>

        <!-- Meta -->
        <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500 dark:text-slate-400 mb-3">
            <span>প্রকাশিত: {{ $post?->created_at?->format('d F, Y') }}</span>
            <span>•</span>
            @if($post?->author)<a href="{{ route('authors.show', $post->author) }}" class="hover:text-primary-dark dark:hover:text-primary-light">{{ $post->author->name }}</a>@endif
            <span>•</span>
            @if($post?->primaryCategory())
                <a href="{{ route('categories.show', $post->primaryCategory()->slug) }}" class="bg-primary-light text-primary-dark px-2 py-0.5 rounded-full text-[11px]">
                    {{ $post->primaryCategory()->name }}
                </a>
            @endif
        </div>

        <!-- Feature Image -->
        <img src="{{ $post?->image_url }}" alt="{{ $post?->name }}" class="w-full rounded-lg mb-4 object-cover">

        <!-- Social Share -->
        <div class="flex flex-wrap items-center gap-2 mb-4 text-xs">
            <span class="font-semibold text-gray-700 dark:text-slate-200">শেয়ার করুন:</span>
            <button class="px-3 py-1 rounded-md bg-blue-600 text-white text-xs">Facebook</button>
            <button class="px-3 py-1 rounded-md bg-sky-500 text-white text-xs">Twitter</button>
            <button class="px-3 py-1 rounded-md bg-green-600 text-white text-xs">WhatsApp</button>
        </div>

        <!-- Post Body -->
        <div class="typography text-slate-800 dark:text-slate-100">
            {!! $post?->content !!}
        </div>

        <!-- Tags -->
        @if($post && $post->tags->isNotEmpty())

            <div class="mt-4 flex flex-wrap gap-2 text-xs">
                <div class="flex flex-wrap gap-2">
                    @foreach($post->tags as $tag)
                        <a href="{{ route('tags.show', $tag->slug) }}" class="px-2 py-1 rounded-full bg-primary-light text-primary-dark dark:bg-slate-800 dark:text-slate-100">
                            #{{ $tag->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        @if($previousPost || $nextPost)
            <section class="mt-6">
                <h2 class="text-sm font-semibold border-b pb-2 mb-3 border-slate-200 dark:border-slate-700">
                    আরও পড়ুন
                </h2>
                <div class="grid sm:grid-cols-2 gap-3 text-sm">
                    @if($previousPost)
                        <article class="bg-slate-50 dark:bg-slate-900/60 rounded-lg p-3 space-y-2">
                            <div class="text-[11px] uppercase tracking-wide text-slate-500 dark:text-slate-400">পূর্ববর্তী পোষ্ট</div>
                            <a href="{{ post_permalink($previousPost) }}" class="font-semibold hover:text-primary-dark dark:hover:text-primary-light leading-snug block">
                                {{ $previousPost->name }}
                            </a>
                            <div class="text-xs text-gray-500 dark:text-slate-400">{{ $previousPost->created_at?->diffForHumans() }}</div>
                        </article>
                    @endif

                    @if($nextPost)
                        <article class="bg-slate-50 dark:bg-slate-900/60 rounded-lg p-3 space-y-2">
                            <div class="text-[11px] uppercase tracking-wide text-slate-500 dark:text-slate-400 text-right">পরবর্তী পোষ্ট</div>
                            <a href="{{ post_permalink($nextPost) }}" class="font-semibold hover:text-primary-dark dark:hover:text-primary-light leading-snug block">
                                {{ $nextPost->name }}
                            </a>
                            <div class="text-xs text-gray-500 dark:text-slate-400">{{ $nextPost->created_at?->diffForHumans() }}</div>
                        </article>
                    @endif
                </div>
            </section>
        @endif

        <!-- Author Box -->
        @if($post?->author)
        <section class="mt-6 border-t border-slate-200 dark:border-slate-700 pt-4 flex gap-3 items-start">
            <img src="https://placehold.co/80x80" alt="Author" class="w-14 h-14 rounded-full object-cover">
            <div>
                <h3 class="text-sm font-semibold">
                    <a href="{{ route('authors.show', $post->author) }}" class="hover:text-primary-dark dark:hover:text-primary-light">{{ $post->author->name }}</a>
                </h3>
                <p class="text-xs text-gray-600 dark:text-slate-400 mt-1">
                    ঘটনার ভেতরের খবর তুলে ধরতে আমরা সবসময় মাঠে থাকি। নির্ভুল তথ্য দেওয়ার চেষ্টা আমাদের অব্যাহত…
                </p>
            </div>
        </section>
        @endif

        <!-- Comment Placeholder -->
        <section class="mt-6">
            <h2 class="text-sm font-semibold border-b pb-2 mb-3 border-slate-200 dark:border-slate-700">
                মন্তব্য করুন
            </h2>
            <form class="space-y-2 text-sm">
                <input type="text" placeholder="নাম"
                       class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 rounded-md bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary/60">
                <textarea rows="4" placeholder="আপনার মন্তব্য লিখুন…"
                          class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 rounded-md bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary/60"></textarea>
                <button type="submit"
                        class="px-4 py-2 bg-primary-dark text-white rounded-md text-sm font-semibold hover:bg-primary">
                    মন্তব্য পাঠান
                </button>
            </form>
        </section>
        @endunless
    </article>

    <aside class="lg:col-span-4 space-y-6 lg:sticky lg:top-24 self-start">

        <!-- Latest News -->
        <section class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-4 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
            <h2 class="text-base font-semibold border-b pb-2 mb-3 border-slate-200 dark:border-slate-700">
                আরও পড়ুন
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
                <div class="space-y-3 text-sm">
                    @forelse($relatedPosts as $related)
                        <article class="flex gap-3">
                            <img src="{{ $related->image_url }}"
                                 class="w-20 h-14 object-cover rounded-md" alt="">
                            <div class="space-y-1 overflow-hidden">
                                @if($related->primaryCategory())
                                    <a href="{{ route('categories.show', $related->primaryCategory()->slug) }}" class="text-primary-dark dark:text-primary-light font-semibold">
                                        {{ $related->primaryCategory()->name }}
                                    </a>
                                @endif
                                <div>
                                    <a href="{{ post_permalink($related) }}" class="block truncate font-semibold leading-snug hover:text-primary-dark dark:hover:text-primary-light">
                                        {{ $related->name }}
                                    </a>
                                    <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">
                                        {{ $related->created_at?->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        </article>
                    @empty
                        <p class="text-sm text-slate-600 dark:text-slate-300">আরো কোনো পোস্ট পাওয়া যায়নি।</p>
                    @endforelse
                </div>
            @endunless
        </section>

        <!-- Trending -->
        <section class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-4
                            transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
            <h2 class="text-base font-semibold border-b pb-2 mb-3 border-slate-200 dark:border-slate-700">
                ট্রেন্ডিং
            </h2>
            @unless($ready)
                <flux:skeleton.group animate="shimmer" class="space-y-2">
                    @for($i = 0; $i < 3; $i++)
                        <flux:skeleton.line />
                    @endfor
                </flux:skeleton.group>
            @else
                <ul class="space-y-2 text-sm">
                    @forelse($trendingPosts as $trending)
                        <li>
                            <a href="{{ post_permalink($trending) }}" class="hover:text-primary-dark dark:hover:text-primary-light">
                                ✔ {{ $trending->name }}
                            </a>
                            <div class="text-[11px] text-slate-500 dark:text-slate-400">
                                {{ $trending->created_at?->diffForHumans() }}
                            </div>
                        </li>
                    @empty
                        <li class="text-slate-500 dark:text-slate-300">কোনো ট্রেন্ডিং পোস্ট নেই</li>
                    @endforelse
                </ul>
            @endunless
        </section>

        <!-- Newsletter -->
        <section class="bg-primary-light/70 dark:bg-slate-800 rounded-xl p-4 border border-primary/20 dark:border-slate-700
                            transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
            <h2 class="text-base font-semibold mb-1 text-primary-dark dark:text-primary-light">
                নিউজলেটার সাবস্ক্রাইব করুন
            </h2>
            <p class="text-xs text-slate-700 dark:text-slate-300 mb-3">
                দিনের গুরুত্বপূর্ণ খবর সরাসরি পেতে ইমেইল দিন।
            </p>
            <form class="space-y-2">
                <input type="email" placeholder="আপনার ইমেইল"
                       class="w-full px-3 py-2 text-sm rounded-md border border-primary/40 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary" />
                <button type="submit" class="w-full bg-primary-dark text-white text-sm font-semibold py-2 rounded-md hover:bg-primary">
                    সাবস্ক্রাইব
                </button>
            </form>
        </section>

        <!-- Social -->
        <section class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-4
                            transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
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

        <!-- Ad Placeholder -->
        <section class="bg-slate-100 dark:bg-slate-800 border border-dashed border-slate-300 dark:border-slate-600 rounded-xl p-4 text-center text-xs text-slate-500 dark:text-slate-400">
            বিজ্ঞাপনের স্থান
        </section>
    </aside>
</main>
