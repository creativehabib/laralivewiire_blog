<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @php
        $seoData = \App\Support\Seo::fromArray($seo ?? ['title' => $title ?? 'বাংলাদেশী নিউজ পোর্টাল']);
    @endphp
    <x-seo.meta :seo="$seoData" />

    {{-- ১. Google Font Optimization --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        .animate-marquee { display: inline-block; animation: marquee 18s linear infinite; }
        @keyframes marquee { 0% { transform: translateX(0%); } 100% { transform: translateX(-50%); } }
        #videoCarousel::-webkit-scrollbar { height: 6px; }
        #videoCarousel::-webkit-scrollbar-track { background: rgba(148, 163, 184, 0.2); }
        #videoCarousel::-webkit-scrollbar-thumb { background: rgba(148, 163, 184, 0.8); border-radius: 999px; }
        #videoCarousel { scrollbar-width: thin; scrollbar-color: rgba(148,163,184,0.8) transparent; }
    </style>
    @if($headerHtml = setting('custom_header_html'))
        {!! $headerHtml !!}
    @endif
    @if($customCss = setting('custom_css'))
        <style>
            {!! $customCss !!}
        </style>
    @endif
    @if($headerJs = setting('custom_header_js'))
        {!! $headerJs !!}
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
        document.addEventListener('livewire:navigated', () => {
            applyTheme();
        });
    </script>
</head>

<body class="font-sans antialiased bg-slate-100 text-slate-900 dark:bg-slate-950 dark:text-slate-100 transition-colors duration-300 ease-out">
    @if($bodyJs = setting('custom_body_js'))
        {!! $bodyJs !!}
    @endif
    @if($bodyHtml = setting('custom_body_html'))
        {!! $bodyHtml !!}
    @endif
    <x-frontends.top-bar/>
    <x-frontends.navbar />

    <main class="min-h-screen">
        {{ $slot }}
    </main>
    @if($footerHtml = setting('custom_footer_html'))
        {!! $footerHtml !!}
    @endif
    <x-frontends.footer />

    {{-- ৩. অপ্টিমাইজড স্ক্রিপ্টসমূহ (defer ব্যবহার করা হয়েছে) --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/autoloader/prism-autoloader.min.js" defer></script>
    <script src="{{ asset('assets/js/script.js') }}" defer></script>

    @livewireScripts
    @stack('scripts')
    @if($footerJs = setting('custom_footer_js'))
        {!! $footerJs !!}
    @endif
</body>
</html>
