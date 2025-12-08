<div class="container px-4 py-8 md:py-10" wire:init="loadAuthor">
    <main class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 lg:items-start">

        <div class="lg:col-span-8 space-y-6">
            <section class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-4 md:p-6 flex flex-col md:flex-row gap-4 items-start transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
                @if($author->avatar)
                    <img src="{{ asset('storage/' . $author->avatar) }}" alt="{{ $author->name }}" class="w-24 h-24 rounded-full object-cover">
                @else
                    <div class="w-24 h-24 rounded-full bg-primary/10 text-primary-dark dark:text-primary-light flex items-center justify-center text-3xl font-semibold">
                        {{ strtoupper(mb_substr($author->name, 0, 1)) }}
                    </div>
                @endif
                <div class="flex-1 space-y-2">
                    <div>
                        <h1 class="text-xl md:text-2xl font-semibold mb-1">{{ $author->name }}</h1>
                        <p class="text-xs text-gray-500 dark:text-slate-400 mb-1">
                            যোগ দিয়েছেন: {{ $author->created_at?->translatedFormat('F Y') ?? 'অজানা' }} • মোট লেখা: {{ $totalPostCount }}+
                        </p>
                    </div>
                    <p class="text-sm text-gray-700 dark:text-slate-200">
                        {{ $author->bio ?? 'জাতীয়, অর্থনীতি, রাজনীতি ও উপকূলীয় এলাকার বিশেষ রিপোর্টিংয়ে যার রয়েছে দীর্ঘ অভিজ্ঞতা। মাঠ থেকে সংগ্রহ করা নির্ভুল সংবাদ পৌঁছে দেন পাঠকের কাছে।' }}
                    </p>
                    <div class="flex flex-wrap gap-2 text-xs">
                        @if($author->website)
                            <a href="{{ $author->website }}" class="px-3 py-1 rounded-full bg-primary-light text-primary-dark" target="_blank" rel="noreferrer">ওয়েবসাইট</a>
                        @endif
                        @if($author->email)
                            <a href="mailto:{{ $author->email }}" class="px-3 py-1 rounded-full bg-primary-light text-primary-dark">ইমেইল</a>
                        @endif
                        @unless($author->website || $author->email)
                            <span class="px-3 py-1 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-200">যোগাযোগ তথ্য নেই</span>
                        @endunless
                    </div>
                </div>
            </section>

            <section class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-4 md:p-6 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-lg font-semibold">এই লেখকের সাম্প্রতিক প্রকাশনা</h2>
                    <span class="text-xs text-gray-500 dark:text-slate-400">{{ $ready ? 'পৃষ্ঠা ' . $posts->currentPage() : 'লোড হচ্ছে…' }}</span>
                </div>

                @unless($ready)
                    <flux:skeleton.group animate="shimmer" class="space-y-3">
                        @for($i = 0; $i < 3; $i++)
                            <article class="flex flex-col md:flex-row gap-3">
                                <flux:skeleton class="w-full md:w-40 h-32 rounded-lg" />
                                <div class="flex-1 space-y-2">
                                    <flux:skeleton.line />
                                    <flux:skeleton.line />
                                    <flux:skeleton.line class="w-3/4" />
                                </div>
                            </article>
                        @endfor
                    </flux:skeleton.group>
                @else
                    <div class="space-y-4">
                        @forelse($posts as $post)
                            <article class="flex flex-col md:flex-row gap-3 border-b border-slate-200 dark:border-slate-700 pb-3 last:border-b-0 last:pb-0">
                                <a href="{{ post_permalink($post) }}" class="shrink-0">
                                    <img src="{{ $post->image_url }}" alt="{{ $post->name }}" class="w-full md:w-40 h-32 object-cover rounded-lg">
                                </a>
                                <div class="flex-1">
                                    <a href="{{ post_permalink($post) }}" class="text-base font-semibold hover:text-primary-dark dark:hover:text-primary-light">
                                        {{ $post->name }}
                                    </a>
                                    <p class="text-xs text-gray-500 dark:text-slate-400 mt-1">
                                        {{ $post->primaryCategory()?->name ?? 'সংবাদ' }} • {{ $post->created_at?->diffForHumans() }}
                                    </p>
                                    <p class="text-sm text-gray-700 dark:text-slate-200 mt-2">
                                        {{ $post->excerpt ?? 'বিস্তারিত পড়তে ক্লিক করুন…' }}
                                    </p>
                                </div>
                            </article>
                        @empty
                            <p class="text-sm text-gray-600 dark:text-slate-300">এই লেখকের কোনো প্রকাশনা পাওয়া যায়নি।</p>
                        @endforelse
                    </div>

                    <div class="flex items-center justify-center gap-2 mt-4 text-xs text-slate-600 dark:text-slate-300">
                        <span class="px-3 py-1 border rounded-md bg-white dark:bg-slate-900">মোট পোস্ট: {{ $totalPostCount }}</span>
                        <span class="px-3 py-1 border rounded-md bg-white dark:bg-slate-900">
                            @if($ready && $posts->count())
                                দেখানো হয়েছে: {{ $posts->firstItem() }}-{{ $posts->lastItem() }}
                            @else
                                দেখানো হয়েছে: 0
                            @endif
                        </span>
                    </div>

                    @if($ready && $posts->hasPages())
                        <div class="mt-6">
                            {{ $posts->onEachSide(1)->links() }}
                        </div>
                    @endif
                @endunless
            </section>
        </div>

        <aside class="lg:col-span-4 lg:sticky lg:top-28 self-start">
            <div class="space-y-6">
                <section class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-4 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
                    <h2 class="text-base font-semibold border-b pb-2 mb-3 border-slate-200 dark:border-slate-700">ট্রেন্ডিং</h2>
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
                                    <div class="text-[11px] text-slate-500 dark:text-slate-400">{{ $trending->created_at?->diffForHumans() }}</div>
                                </li>
                            @empty
                                <li class="text-slate-500 dark:text-slate-300">কোনো ট্রেন্ডিং পোস্ট নেই</li>
                            @endforelse
                        </ul>
                    @endunless
                </section>

                <section class="bg-primary-light/70 dark:bg-slate-800 rounded-xl p-4 border border-primary/20 dark:border-slate-700 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
                    <h2 class="text-base font-semibold mb-1 text-primary-dark dark:text-primary-light">নিউজলেটার সাবস্ক্রাইব করুন</h2>
                    <p class="text-xs text-slate-700 dark:text-slate-300 mb-3">দিনের গুরুত্বপূর্ণ খবর সরাসরি পেতে ইমেইল দিন।</p>
                    <form class="space-y-2">
                        <input type="email" placeholder="আপনার ইমেইল" class="w-full px-3 py-2 text-sm rounded-md border border-primary/40 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary" />
                        <button type="submit" class="w-full bg-primary-dark text-white text-sm font-semibold py-2 rounded-md hover:bg-primary">সাবস্ক্রাইব</button>
                    </form>
                </section>

                <section class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-4 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
                    <h2 class="text-base font-semibold border-b pb-2 mb-3 border-slate-200 dark:border-slate-700">আমাদের সাথে থাকুন</h2>
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

                <section class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-4 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
                    <h2 class="text-base font-semibold border-b pb-2 mb-3 border-slate-200 dark:border-slate-700">আরও খবর</h2>
                    @unless($ready)
                        <flux:skeleton.group animate="shimmer" class="space-y-3">
                            @for($i = 0; $i < 2; $i++)
                                <div class="flex gap-3">
                                    <flux:skeleton class="w-20 h-14 rounded-md" />
                                    <div class="flex-1 space-y-2">
                                        <flux:skeleton.line />
                                        <flux:skeleton.line class="w-1/2" />
                                    </div>
                                </div>
                            @endfor
                        </flux:skeleton.group>
                    @else
                        <div class="space-y-3 text-sm">
                            @forelse($moreNews as $news)
                                <article class="flex gap-3">
                                    <a href="{{ post_permalink($news) }}" class="shrink-0">
                                        <img src="{{ $news->image_url }}" class="w-20 h-14 object-cover rounded-md" alt="{{ $news->name }}">
                                    </a>
                                    <div class="flex-1">
                                        <a href="{{ post_permalink($news) }}" class="font-semibold leading-snug hover:text-primary-dark dark:hover:text-primary-light">
                                            {{ $news->name }}
                                        </a>
                                        <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">{{ $news->created_at?->diffForHumans() }}</div>
                                    </div>
                                </article>
                            @empty
                                <p class="text-sm text-slate-500 dark:text-slate-300">কোনো খবর পাওয়া যায়নি।</p>
                            @endforelse
                        </div>
                    @endunless
                </section>
            </div>
        </aside>
    </main>
</div>
