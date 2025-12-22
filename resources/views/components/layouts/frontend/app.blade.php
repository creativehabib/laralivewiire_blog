<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="referrer" content="origin-when-cross-origin">

    @php
        $seoData = \App\Support\Seo::fromArray($seo ?? ['title' => $title ?? 'ржмрж╛ржВрж▓рж╛ржжрзЗрж╢рзА ржирж┐ржЙржЬ ржкрзЛрж░рзНржЯрж╛рж▓']);
    @endphp
    <x-seo.meta :seo="$seoData" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        .animate-marquee { display: inline-block; animation: marquee 18s linear infinite; }
        @keyframes marquee { 0% { transform: translateX(0%); } 100% { transform: translateX(-50%); } }
    </style>

    @if($headerHtml = setting('custom_header_html')) {!! $headerHtml !!} @endif
    @if($customCss = setting('custom_css')) <style>{!! $customCss !!}</style> @endif
    @if($headerJs = setting('custom_header_js')) {!! $headerJs !!} @endif

    {{-- Adsense --}}
    @if(setting('adsense_mode') === 'auto' && !empty(setting('adsense_auto_code')))
        {!! setting('adsense_auto_code') !!}
    @elseif(setting('adsense_mode') === 'unit' && !empty(setting('adsense_unit_client_id')))
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client={{ setting('adsense_unit_client_id') }}" crossorigin="anonymous"></script>
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('styles')

    <script>
        function applyTheme() {
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
        applyTheme();
        document.addEventListener('livewire:navigated', () => applyTheme());
    </script>
</head>

<body class="font-sans antialiased bg-slate-100 text-slate-900 dark:bg-slate-950 dark:text-slate-100 transition-colors duration-300 ease-out">

    @if($bodyJs = setting('custom_body_js')) {!! $bodyJs !!} @endif
    @if($bodyHtml = setting('custom_body_html')) {!! $bodyHtml !!} @endif

    <x-frontends.top-bar/>
    <x-frontends.navbar />

    <main class="min-h-screen">
        {{ $slot }}
    </main>

    @if($footerHtml = setting('custom_footer_html')) {!! $footerHtml !!} @endif
    <x-frontends.footer />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/autoloader/prism-autoloader.min.js" defer></script>
    <script src="{{ asset('assets/js/script.js') }}" defer></script>

    @livewireScripts
    @stack('scripts')

    @if($footerJs = setting('custom_footer_js')) {!! $footerJs !!} @endif
</body>
</html>
