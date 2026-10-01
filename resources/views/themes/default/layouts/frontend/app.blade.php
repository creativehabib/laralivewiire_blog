<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $breakingNewsEnabled = filter_var(setting('breaking_news_enabled', true), FILTER_VALIDATE_BOOLEAN);
    @endphp
    @include('partials.frontend-head')
</head>

<body class="font-sans antialiased bg-slate-100 text-slate-900 dark:bg-slate-950 dark:text-slate-100 transition-colors duration-300 ease-out {{ $breakingNewsEnabled && setting('breaking_news_position', 'top') === 'bottom' ? 'pb-10' : '' }}" style="font-size: var(--body-font-size, 16px);">

    @if($bodyJs = setting('custom_body_js')) {!! $bodyJs !!} @endif
    @if($bodyHtml = setting('custom_body_html')) {!! $bodyHtml !!} @endif
    @if(setting('admin_show_admin_bar'))
    <x-frontends.admin-bar />
    @endif
    <x-frontends.top-bar/>
    <x-frontends.navbar />

    <main class="min-h-screen">
        {{ $slot }}
    </main>

    @if($breakingNewsEnabled && setting('breaking_news_position', 'top') === 'bottom')
        <div class="fixed bottom-0 inset-x-0 z-50">
            <x-frontends.breaking-ticker-bar />
        </div>
    @endif

    @if($footerHtml = setting('custom_footer_html')) {!! $footerHtml !!} @endif
    <x-frontends.footer />

    @if($breakingNewsEnabled && setting('breaking_news_position', 'top') === 'bottom')
        <div class="fixed bottom-0 inset-x-0 z-50">
            <x-frontends.breaking-ticker-bar />
        </div>
    @endif

    @php
        $scrollTop = setting('scroll_to_top', []);
        if (!is_array($scrollTop)) {
            $scrollTop = [];
        }

        $scrollTopEnabled = filter_var($scrollTop['enabled'] ?? setting('scroll_to_top_enabled', true), FILTER_VALIDATE_BOOLEAN);
        $scrollTopSide = ($scrollTop['side'] ?? setting('scroll_to_top_side', 'right')) === 'left' ? 'left' : 'right';
        $scrollTopShape = ($scrollTop['shape'] ?? setting('scroll_to_top_shape', 'circle')) === 'rectangle' ? 'rounded-lg' : 'rounded-full';
        $scrollTopSpeed = max(100, (int) ($scrollTop['speed'] ?? setting('scroll_to_top_speed', 500)));
        $scrollTopColor = (string) ($scrollTop['color'] ?? setting('scroll_to_top_color', '#2563eb'));
        $scrollTopIconColor = (string) ($scrollTop['icon_color'] ?? setting('scroll_to_top_icon_color', '#ffffff'));
    @endphp

    @if($scrollTopEnabled)
        <button
            id="scrollToTopBtn"
            type="button"
            aria-label="Scroll to top"
            data-scroll-speed="{{ $scrollTopSpeed }}"
            class="fixed bottom-6 {{ $scrollTopSide === 'left' ? 'left-6' : 'right-6' }} z-[70] hidden h-11 w-11 {{ $scrollTopShape }} shadow-lg transition-all duration-300 hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            style="background-color: {{ $scrollTopColor }}; color: {{ $scrollTopIconColor }};"
        >
            <i class="fas fa-arrow-up text-sm"></i>
        </button>
    @endif

    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/autoloader/prism-autoloader.min.js" defer></script>
    <script src="{{ asset('assets/js/script.js') }}" defer></script>
    @fluxScripts

    @livewireScripts
    @stack('scripts')

    @if($footerJs = setting('custom_footer_js')) {!! $footerJs !!} @endif
</body>
</html>
