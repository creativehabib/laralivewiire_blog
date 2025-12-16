<!DOCTYPE html>
<html lang="bn">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        @php
            $seoData = \App\Support\Seo::fromArray($seo ?? ['title' => $title ?? 'বাংলাদেশী নিউজ পোর্টাল']);
        @endphp
        <x-seo.meta :seo="$seoData" />
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        {{-- Google Font --}}
        <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
        <style>
            /* Marquee animation */
            .animate-marquee {
                display: inline-block;
                animation: marquee 18s linear infinite;
            }

            @keyframes marquee {
                0%   { transform: translateX(0%); }
                100% { transform: translateX(-50%); }
            }

            /* Scrollbar style for video carousel (optional) */
            #videoCarousel::-webkit-scrollbar {
                height: 6px;
            }

            #videoCarousel::-webkit-scrollbar-track {
                background: rgba(148, 163, 184, 0.2); /* slate-400/20 */
            }

            #videoCarousel::-webkit-scrollbar-thumb {
                background: rgba(148, 163, 184, 0.8); /* slate-400 */
                border-radius: 999px;
            }

            #videoCarousel {
                scrollbar-width: thin;
                scrollbar-color: rgba(148,163,184,0.8) transparent;
            }
        </style>
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

    <body class="font-sans antialiased
               bg-slate-100 text-slate-900
               dark:bg-slate-950 dark:text-slate-100
               selection:bg-primary/20 selection:text-primary-dark
               transition-colors duration-300 ease-out">

        <x-frontends.top-bar/>

        <x-frontends.navbar />

        <main class="min-h-screen">
            {{ $slot }}
        </main>

        <x-frontends.footer />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-markup.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-markup-templating.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-php.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-javascript.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-css.min.js"></script>
        <script src="{{ asset('assets/js/script.js') }}"></script>
        @livewireScripts
        @stack('scripts')
    </body>
</html>
