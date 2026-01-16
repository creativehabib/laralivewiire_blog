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
            <a href="{{ route('home') }}" class="hover:text-primary-dark dark:hover:text-primary-light" wire:navigate>হোম</a>
            <span>/</span>
            @if($post?->primaryCategory())
                <a href="{{ route('categories.show', $post->primaryCategory()->slug) }}" class="hover:text-primary-dark dark:hover:text-primary-light" wire:navigate>
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
            {!! the_author($post, 'hover:text-primary-dark dark:hover:text-primary-light') !!}
            <span>•</span>
            @if($post?->primaryCategory())
                <a href="{{ route('categories.show', $post->primaryCategory()->slug) }}" class="bg-primary-light text-primary-dark px-2 py-0.5 rounded-full text-[11px]" wire:navigate>
                    {{ $post->primaryCategory()->name }}
                </a>
            @endif
        </div>

        <!-- Feature Image -->
        <img src="{{ the_thumbnail($post) }}" alt="{{ $post?->name }}" class="max-h-80 w-full rounded-lg mb-4 object-cover">

        <!-- Social Share -->
        <div class="flex flex-wrap items-center gap-2 mb-4 text-xs">
            <span class="font-semibold text-gray-700 dark:text-slate-200">শেয়ার করুন:</span>
            <button class="px-3 py-1 rounded-md bg-blue-600 text-white text-xs">Facebook</button>
            <button class="px-3 py-1 rounded-md bg-sky-500 text-white text-xs">Twitter</button>
            <button class="px-3 py-1 rounded-md bg-green-600 text-white text-xs">WhatsApp</button>
        </div>

        <!-- Post Body -->
        <div class="typography prose-article text-slate-800 dark:text-slate-100 ck-content">
            {!! $post?->content !!}
        </div>

        <!-- Tags -->
        @if($post && $post->tags->isNotEmpty())

            <div class="mt-4 flex flex-wrap gap-2 text-xs">
                <div class="flex flex-wrap gap-2">
                    @foreach($post->tags as $tag)
                        <a href="{{ tag_permalink($tag) }}" class="px-2 py-1 rounded-full bg-primary-light text-primary-dark dark:bg-slate-800 dark:text-slate-100" wire:navigate>
                            #{{ $tag->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        @if($previousPost || $nextPost)
            <section class="mt-12">
                <div class="grid md:grid-cols-2 gap-8">
                    @php
                        $cards = [
                            ['post' => $previousPost, 'label' => 'আগের পোস্ট', 'icon' => '←', 'align' => 'flex-row'],
                            ['post' => $nextPost, 'label' => 'পরের পোস্ট', 'icon' => '→', 'align' => 'flex-row-reverse text-right']
                        ];
                    @endphp

                    @foreach($cards as $card)
                        @if($card['post'])
                            <a href="{{ post_permalink($card['post']) }}" wire:navigate
                               class="group relative flex {{ $card['align'] }} items-stretch bg-white dark:bg-slate-800/50 backdrop-blur-sm rounded-3xl border border-slate-100 dark:border-slate-700/50 p-2 transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_20px_40px_-15px_rgba(0,0,0,0.1)] dark:hover:shadow-[0_20px_40px_-15px_rgba(0,0,0,0.5)]">

                                {{-- Large Image Section --}}
                                <div class="w-1/3 min-w-[100px] rounded-2xl overflow-hidden relative">
                                    <div class="absolute inset-0 bg-slate-200 dark:bg-slate-700 animate-pulse group-hover:hidden"></div>
                                    <img src="{{ the_thumbnail($card['post']) }}" alt="{{ $card['post']->name }}"
                                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                </div>

                                {{-- Content Section --}}
                                <div class="flex-1 flex flex-col justify-center px-5 py-2">
                                    <span class="text-xs font-semibold text-primary-600 dark:text-primary-400 mb-2 opacity-80 group-hover:opacity-100">
                                         @if($card['align'] == 'flex-row')
                                            {{ $card['icon'] }} {{ $card['label'] }}
                                        @else
                                            {{ $card['label'] }} {{ $card['icon'] }}
                                        @endif
                                    </span>
                                    <h4 class="text-lg font-bold text-slate-800 dark:text-white leading-tight mb-2 line-clamp-2">
                                        {{ $card['post']->name }}
                                    </h4>
                                    <span class="text-[11px] text-slate-400 font-medium">
                                        {{ the_date($card['post'], 'M d, Y') }} • {{ $card['post']->read_time ?? '5 min' }} read
                                    </span>
                                </div>
                            </a>
                        @else
                            <div class="hidden md:block"></div>
                        @endif
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Author Box -->
        @if($post?->author)
        <section class="mt-6 border-t border-slate-200 dark:border-slate-700 pt-4 flex gap-3 items-start">
            <img src="https://placehold.co/80x80" alt="Author" class="w-14 h-14 rounded-full object-cover">
            <div>
                <h3 class="text-sm font-semibold">
                    {!! the_author($post, 'hover:text-primary-dark dark:hover:text-primary-light') !!}
                </h3>
                <p class="text-xs text-gray-600 dark:text-slate-400 mt-1">
                    ঘটনার ভেতরের খবর তুলে ধরতে আমরা সবসময় মাঠে থাকি। নির্ভুল তথ্য দেওয়ার চেষ্টা আমাদের অব্যাহত…
                </p>
            </div>
        </section>
        @endif

        <!-- Comments -->
        <section class="mt-6">
            <h2 class="text-sm font-semibold border-b pb-2 mb-3 border-slate-200 dark:border-slate-700">
                মন্তব্য করুন
            </h2>
            <x-comments.section :commentable="$post" :canonical-url="post_permalink($post)" />
        </section>
        @endunless
    </article>

    <aside class="lg:col-span-4 space-y-6 lg:sticky lg:top-24 self-start">

        <!-- Latest News -->
        <section class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 p-5 transition-all duration-300 hover:shadow-md">

            {{-- Header --}}
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-base font-bold text-slate-800 dark:text-slate-100 flex items-center gap-2">
            <span class="p-1.5 rounded-md bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" /></svg>
            </span>
                    রিকমেন্ডেড
                </h2>
            </div>

            @unless($ready)
                {{-- Skeleton Loader --}}
                <div class="space-y-4 animate-pulse">
                    <div class="h-40 bg-slate-100 dark:bg-slate-700 rounded-xl w-full"></div>
                    <div class="space-y-3 pt-2">
                        @for($i=0; $i<3; $i++)
                            <div class="flex gap-3">
                                <div class="w-14 h-14 bg-slate-100 dark:bg-slate-700 rounded-lg shrink-0"></div>
                                <div class="flex-1 space-y-2 py-1">
                                    <div class="h-2.5 bg-slate-100 dark:bg-slate-700 rounded w-3/4"></div>
                                    <div class="h-2 bg-slate-100 dark:bg-slate-700 rounded w-1/2"></div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            @else
                <div class="flex flex-col gap-1">
                    @foreach($relatedPosts as $index => $related)
                        @if($index === 0)
                            {{-- === Featured Item (First Post) === --}}
                            <div class="mb-4">
                                <a href="{{ post_permalink($related) }}" wire:navigate class="group block relative rounded-xl overflow-hidden mb-3">
                                    {{-- Glass Badge --}}
                                    <div class="absolute top-3 left-3 z-10 px-2.5 py-1 rounded-lg bg-white/90 dark:bg-black/60 backdrop-blur-md border border-white/20 shadow-sm">
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-800 dark:text-slate-100 flex items-center gap-1">
                                            <svg class="w-3 h-3 text-orange-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"></path></svg>
                                            টপ পিক
                                        </span>
                                    </div>

                                    {{-- Image --}}
                                    <div class="w-full h-44 bg-slate-100 dark:bg-slate-800 relative overflow-hidden">
                                        <img src="{{ the_thumbnail($related) }}"
                                             loading="lazy"
                                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                             alt="{{ $related->name }}">
                                        {{-- Gradient Overlay --}}
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-60"></div>
                                    </div>

                                    {{-- Title (Overlay Style Optional, sticking to bottom for readability) --}}
                                </a>

                                <a href="{{ post_permalink($related) }}" wire:navigate class="block group">
                                    <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100 leading-tight group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                                        {{ $related->name }}
                                    </h3>
                                    <div class="mt-2 flex items-center gap-3 text-xs text-slate-500 dark:text-slate-400 font-medium">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                            {{ the_date($related, 'd M, Y') }}
                                        </span>
                                        @if(isset($related->read_time))
                                            <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                            <span>{{ $related->read_time }} মি. পাঠ</span>
                                        @endif
                                    </div>
                                </a>
                            </div>
                        @else
                            {{-- === List Items (Other Posts) === --}}
                            <a href="{{ post_permalink($related) }}" wire:navigate
                               class="group flex gap-3.5 items-center p-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/40 transition-all duration-200">

                                {{-- Thumbnail --}}
                                <div class="shrink-0 w-20 h-14 rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-800 relative shadow-sm border border-slate-100 dark:border-slate-700/50">
                                    <img src="{{ the_thumbnail($related) }}"
                                         loading="lazy"
                                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                         alt="{{ $related->name }}">
                                </div>

                                {{-- Content --}}
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-200 leading-snug line-clamp-2 group-hover:text-primary-600 dark:group-hover:text-primary-400 mb-1.5">
                                        {{ $related->name }}
                                    </h4>
                                    <div class="text-[11px] text-slate-400 font-medium flex items-center gap-2">
                                        <span>{{ the_date($related, 'diff') }}</span>
                                        {{-- Optional View Count --}}
                                        @if(rand(0,1))
                                            <span class="w-0.5 h-0.5 bg-slate-300 rounded-full"></span>
                                            <span>{{ the_view_count($related, 'ভিউ') }} </span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @endif
                    @endforeach

                    @if($relatedPosts->isEmpty())
                        <div class="text-center py-6 text-slate-500 dark:text-slate-400 text-sm">
                            কোনো রিকমেন্ডেড পোস্ট নেই
                        </div>
                    @endif
                </div>
            @endunless
        </section>

        <!-- Trending -->
        <section class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/50 overflow-hidden">
            {{-- Header --}}
            <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-800/50 flex items-center gap-2">
                <div class="p-1.5 bg-orange-100 dark:bg-orange-900/30 rounded-lg text-orange-500">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-flame"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.1.243-2.188.7-3.125a2 2 0 0 0-.2 2.625Z"/></svg>
                </div>
                <h2 class="text-base font-bold text-slate-800 dark:text-slate-100">
                    জনপ্রিয় এখন
                </h2>
            </div>

            <div class="p-2">
                @unless($ready)
                    {{-- Pure Tailwind Skeleton Loader --}}
                    <div class="p-2 space-y-4 animate-pulse">
                        @for($i = 0; $i < 4; $i++)
                            <div class="flex gap-3 items-center">
                                <div class="w-8 h-8 bg-slate-200 dark:bg-slate-700 rounded-lg shrink-0"></div>
                                <div class="flex-1 space-y-2">
                                    <div class="h-3 bg-slate-200 dark:bg-slate-700 rounded w-3/4"></div>
                                    <div class="h-2 bg-slate-200 dark:bg-slate-700 rounded w-1/2"></div>
                                </div>
                            </div>
                        @endfor
                    </div>
                @else
                    {{-- 'list-none' ক্লাসটি এখানে যুক্ত করা হয়েছে --}}
                    <ul class="space-y-1 list-none">
                        @forelse($trendingPosts as $trending)
                            <li>
                                <a href="{{ post_permalink($trending) }}" wire:navigate class="group flex items-start gap-3 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors duration-200">
                                    {{-- Ranking Number --}}
                                    <span class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-lg font-bold text-sm
                                {{ $loop->iteration == 1 ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' :
                                  ($loop->iteration == 2 ? 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300' :
                                  ($loop->iteration == 3 ? 'bg-orange-50 text-orange-600 dark:bg-orange-900/20 dark:text-orange-400' :
                                  'bg-slate-50 text-slate-400 dark:bg-slate-800 dark:text-slate-500')) }}">
                                {{ $loop->iteration }}
                            </span>

                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-200 group-hover:text-primary-600 dark:group-hover:text-primary-400 leading-snug line-clamp-2">
                                            {{ $trending->name }}
                                        </h3>
                                        <div class="mt-1 flex items-center gap-2 text-[10px] text-slate-400 font-medium">
                                            <span>{{ $trending->created_at?->diffForHumans() }}</span>
                                            @if(isset($trending->views))
                                                <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                                <span>{{ $trending->views }} ভিউ</span>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @empty
                            <li class="p-4 text-center text-sm text-slate-500">কোনো ট্রেন্ডিং পোস্ট নেই</li>
                        @endforelse
                    </ul>
                @endunless
            </div>
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
