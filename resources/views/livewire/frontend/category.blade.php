<div class="container px-4 py-6 md:py-8 grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 lg:items-start typography" wire:init="loadCategory">
    <section class="lg:col-span-8 space-y-8">
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
            {{-- Header Section --}}
            <div class="border-b border-gray-200 dark:border-slate-700 pb-4">
                <nav class="flex items-center gap-2 text-xs text-gray-500 dark:text-slate-400 mb-2">
                    <a href="{{ route('home') }}" class="hover:text-primary transition-colors" wire:navigate>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                            <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3a1 1 0 00-1-1H9a1 1 0 00-1 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-6H3a1 1 0 01-.707-1.707l7-7z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <span class="text-gray-300">/</span>
                    <span class="font-medium text-primary-dark dark:text-primary-light">{{ $category->name }}</span>
                </nav>
                <div class="flex items-end justify-between">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                        {{ $category->name }}
                    </h1>
                    <span class="text-xs font-medium bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-slate-300 px-2 py-1 rounded-full">
                        {{ $featurePosts->count() + $latestPosts->total() }} টি পোস্ট
                    </span>
                </div>
            </div>

            @php
                // লজিক: ফিচারড এবং লেটেস্ট পোস্ট একত্রিত করে স্লাইস করা হচ্ছে ডিজাইনের জন্য
                $allPosts = $featurePosts->concat($latestPosts->getCollection());
                $heroPost = $allPosts->first();
                $sidePosts = $allPosts->slice(1, 2); // ২য় এবং ৩য় পোস্ট
                $gridPosts = $allPosts->slice(3);    // বাকি সব পোস্ট
            @endphp

            @if($allPosts->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                    {{-- Main Hero Post (Left Side - Big) --}}
                    @if($heroPost)
                        <div class="md:col-span-7 lg:col-span-8 group relative rounded-2xl overflow-hidden shadow-sm h-[400px]">
                            <a href="{{ post_permalink($heroPost) }}" wire:navigate class="block h-full w-full">
                                <img src="{{ $heroPost->image_url }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-105" alt="{{ $heroPost->name }}">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent"></div>
                                <div class="absolute bottom-0 left-0 p-6 w-full">
                                    <span class="inline-block px-2 py-1 bg-primary text-white text-[10px] font-bold uppercase tracking-wider rounded mb-2">
                                        হাইলাইটেড
                                    </span>
                                    <h2 class="text-2xl md:text-3xl font-bold text-white leading-tight mb-2 group-hover:underline decoration-primary decoration-2 underline-offset-4">
                                        {{ $heroPost->name }}
                                    </h2>
                                    @if($heroPost->excerpt)
                                        <p class="text-gray-200 text-sm line-clamp-2 hidden md:block">
                                            {{Str::limit($heroPost->excerpt, 100)}}
                                        </p>
                                    @endif
                                    <div class="flex items-center gap-2 text-xs text-gray-300 mt-3">
                                        <span>{{ $heroPost->created_at?->diffForHumans() }}</span>
                                        @if($heroPost->author)
                                            <span>•</span>
                                            <span>{{ $heroPost->author->name }}</span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif

                    {{-- Side Posts (Right Side - 2 Small stacked) --}}
                    <div class="md:col-span-5 lg:col-span-4 flex flex-col gap-6 h-[400px]">
                        @foreach($sidePosts as $sidePost)
                            <article class="relative flex-1 rounded-xl overflow-hidden shadow-sm group">
                                <a href="{{ post_permalink($sidePost) }}" wire:navigate class="block h-full">
                                    <img src="{{ $sidePost->image_url }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-110" alt="{{ $sidePost->name }}">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                                    <div class="absolute bottom-0 left-0 p-4">
                                        <h3 class="text-md font-bold text-white leading-snug group-hover:text-primary-light transition-colors">
                                            {{ $sidePost->name }}
                                        </h3>
                                        <div class="text-[10px] text-gray-300 mt-1">
                                            {{ $sidePost->created_at?->diffForHumans() }}
                                        </div>
                                    </div>
                                </a>
                            </article>
                        @endforeach
                    </div>
                </div>

                {{-- Horizontal Divider --}}
                <div class="flex items-center gap-4 py-2">
                    <span class="h-px flex-1 bg-gray-200 dark:bg-slate-700"></span>
                    <span class="text-sm font-semibold text-gray-400 uppercase tracking-widest">আরও খবর</span>
                    <span class="h-px flex-1 bg-gray-200 dark:bg-slate-700"></span>
                </div>

                {{-- Standard Grid Layout for Remaining Posts --}}
                <div class="grid md:grid-cols-2 gap-6">
                    @foreach($gridPosts as $post)
                        <article class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden flex flex-col transition-all duration-200 hover:shadow-lg hover:-translate-y-1 group">
                            <a href="{{ post_permalink($post) }}" wire:navigate class="relative overflow-hidden h-48">
                                <img src="{{ $post->image_url }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-105" alt="{{ $post->name }}">
                            </a>
                            <div class="p-4 flex flex-col flex-1">
                                <h3 class="font-bold text-lg leading-snug mb-2 text-gray-800 dark:text-gray-100">
                                    <a href="{{ post_permalink($post) }}" class="group-hover:text-primary transition-colors" wire:navigate>
                                        {{ $post->name }}
                                    </a>
                                </h3>
                                @if($post->excerpt)
                                    <p class="text-sm text-gray-600 dark:text-slate-400 line-clamp-2 mb-3 flex-1">
                                        {{ $post->excerpt }}
                                    </p>
                                @endif
                                <div class="mt-auto flex items-center justify-between pt-3 border-t border-gray-100 dark:border-slate-700">
                                    <div class="text-xs text-gray-500 dark:text-slate-500">
                                        {{ $post->created_at?->diffForHumans() }}
                                    </div>
                                    <a href="{{ post_permalink($post) }}" wire:navigate class="text-xs font-semibold text-primary hover:text-primary-dark">
                                        পড়ুন &rarr;
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="text-center py-10 bg-gray-50 dark:bg-slate-800 rounded-xl">
                    <p class="text-gray-500 dark:text-slate-400">এই ক্যাটাগরিতে এখনো কোনো পোস্ট নেই।</p>
                </div>
            @endif

            <div class="mt-8 pt-4 border-t border-gray-100 dark:border-slate-700">
                {{ $latestPosts->onEachSide(1)->links() }}
            </div>
        @endunless
    </section>

    {{-- Sidebar (Unchanged) --}}
    <aside class="lg:col-span-4 lg:sticky lg:top-28 self-start">
        <div class="space-y-6">
            <section class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-4 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
                <h2 class="text-base font-semibold border-b pb-2 mb-3 border-slate-200 dark:border-slate-700">
                    ক্যাটাগরি
                </h2>
                {!! get_the_category_list(
                     null,
                     ', ',
                     true,
                     'text-sm text-primary-dark dark:text-slate-50 hover:text-primary-dark dark:hover:text-primary-light transition duration-300',
                     true,
                     true,
                     'space-y-1',
                     ''
                 )
             !!}
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
