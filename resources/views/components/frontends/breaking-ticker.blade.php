@props(['breakingTicker'])

@php
    $siteLogoLight = setting('site_logo_light');
    $siteLogoDark = setting('site_logo_dark') ?: $siteLogoLight;
    $isBottomTicker = setting('breaking_news_position', 'top') === 'bottom';
    $breakingTickerContainerClass = $isBottomTicker
        ? 'flex items-center gap-4 px-4 py-2'
        : 'container flex items-center gap-4 px-4 py-2';
@endphp

<div class="bg-white dark:bg-secondary dark:text-white text-sm border-t dark:border-t-darkbg-soft">
    <div class="{{ $breakingTickerContainerClass }}">
        <span class="bg-accent text-white px-2 py-1 text-xs font-semibold rounded">ব্রেকিং নিউজ</span>
        <div class="marquee-wrapper overflow-hidden flex-1">
            <div class="whitespace-nowrap animate-marquee">
                @forelse($breakingTicker as $breaking)
                    <a href="{{ post_permalink($breaking) }}" class="group mr-8 inline-flex items-center gap-2 hover:text-primary-dark dark:hover:text-primary-light">
                        @if($siteLogoLight)
                            <img src="{{ $siteLogoLight }}" alt="{{ config('app.name') }} logo" class="h-3 w-3 object-contain dark:hidden" />
                            @if($siteLogoDark)
                                <img src="{{ $siteLogoDark }}" alt="{{ config('app.name') }} logo" class="hidden h-3 w-3 object-contain dark:inline-block" />
                            @endif
                        @else
                            <x-app-logo-icon class="size-3 text-primary-dark dark:text-primary-light" />
                        @endif
                        <span class="transition-transform duration-200 group-hover:translate-x-1">{{ $breaking->name }}</span>
                    </a>
                @empty
                    <span class="mr-8">বর্তমানে কোনো সংবাদ পাওয়া যায়নি।</span>
                @endforelse
            </div>
        </div>
    </div>
</div>
