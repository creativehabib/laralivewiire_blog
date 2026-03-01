@php
    $adminBarOffset = auth()->check() ? 'top-9' : 'top-0';
    $siteLogoLight = setting('site_logo_light');
    $siteLogoDark = setting('site_logo_dark') ?: $siteLogoLight;
@endphp
<header class="bg-white dark:bg-slate-900/95 shadow-sm sticky {{ $adminBarOffset }} z-50
               border-b border-slate-200/70 dark:border-slate-700/70
               backdrop-blur transition-colors duration-300">
    @php
        use App\Models\Category;

        $navCategories = Category::query()
            ->where('status', 'published')
            ->orderBy('order')
            ->orderBy('created_at')
            ->take(7)
            ->get();
    @endphp
    @php
        $primaryMenu = get_menu_by_location('primary');
        $primaryMenuItems = $primaryMenu?->items ?? collect();
    @endphp
    <div class="container flex items-center justify-between px-4 py-3">
        <button id="mobileMenuButton" class="md:hidden inline-flex items-center justify-center w-10 h-10 border rounded-lg border-slate-300 dark:border-slate-600">
            <span class="sr-only">Toggle navigation</span>
            <div class="space-y-1.5">
                <span class="block w-5 h-0.5 bg-slate-800 dark:bg-slate-100"></span>
                <span class="block w-5 h-0.5 bg-slate-800 dark:bg-slate-100"></span>
                <span class="block w-5 h-0.5 bg-slate-800 dark:bg-slate-100"></span>
            </div>
        </button>

        <a href="{{ route('home') }}" class="flex items-center gap-2 md:mr-auto md:ml-0 mx-auto md:mx-0">
            @if($siteLogoLight)
                <img
                    src="{{ $siteLogoLight }}"
                    alt="{{ config('app.name') }} logo"
                    class="h-10 w-auto max-w-[160px] object-contain dark:hidden"
                />
                @if($siteLogoDark)
                    <img
                        src="{{ $siteLogoDark }}"
                        alt="{{ config('app.name') }} logo"
                        class="hidden h-10 w-auto max-w-[160px] object-contain dark:block"
                    />
                @endif
            @else
                <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center text-white font-bold">NP</div>
            @endif
            <div class="hidden lg:block">
                <div class="text-xl font-bold text-primary-dark dark:text-primary-light">বাংলা নিউজ পোর্টাল</div>
                <div class="text-xs text-slate-500 dark:text-slate-400">বিশ্বস্ত খবর আপনার হাতের মুঠোয়</div>
            </div>
        </a>
        <div class="flex items-center gap-3 md:ml-6">
            <nav class="hidden md:flex items-center gap-6 text-sm font-medium">
                @if($primaryMenuItems->isNotEmpty())
                    <x-frontends.menu-list :items="$primaryMenuItems" variant="desktop" />
                @else
                    <a href="{{ route('home') }}" class="text-primary-dark dark:text-primary-light relative transition-colors duration-150">হোম</a>
                    @foreach($navCategories as $category)
                        <a href="{{ route('categories.show', $category->slug) }}"
                           class="text-slate-700 dark:text-slate-200 hover:text-primary-dark dark:hover:text-primary-light transition-colors duration-150">
                            {{ $category->name }}
                        </a>
                    @endforeach
                @endif
            </nav>
            <livewire:frontend.live-search
                :wire:key="'live-search-desktop'"
                wrapper-class="hidden lg:block w-64 xl:w-72"
                :slide-below="true"
            />

            <button id="themeToggle" aria-label="Toggle Dark Mode" class="inline-flex cursor-pointer items-center justify-center w-9 h-9 rounded-full border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-100 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                <i id="moonIcon" class="fa-solid fa-moon text-sm"></i>
                <i id="sunIcon" class="fa-solid fa-sun text-sm hidden"></i>
            </button>
        </div>
    </div>
    @if(setting('breaking_news_position', 'top') === 'top')
        <x-frontends.breaking-ticker-bar />
    @endif
    <nav id="mobileMenu"
         class="md:hidden bg-white dark:bg-slate-900 border-t border-slate-100 dark:border-slate-700 px-4 pt-2 pb-4 space-y-1 hidden">
        <div class="container px-0">
            <livewire:frontend.live-search
                :wire:key="'live-search-mobile'"
                wrapper-class="w-full mb-3"
                input-class="w-full"
                input-id="frontend-live-search-mobile"
            />

            @if($primaryMenuItems->isNotEmpty())
                <x-frontends.menu-list :items="$primaryMenuItems" variant="mobile" />
            @else
                <a href="{{ route('home') }}" class="block px-2 py-2 rounded-md text-sm font-medium text-primary-dark dark:text-primary-light bg-primary-light/70 dark:bg-slate-800 mt-2">হোম</a>
                @foreach($navCategories as $category)
                    <a href="{{ route('categories.show', $category->slug) }}" class="block px-2 py-2 rounded-md text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800">
                        {{ $category->name }}
                    </a>
                @endforeach
            @endif
        </div>
    </nav>
</header>
