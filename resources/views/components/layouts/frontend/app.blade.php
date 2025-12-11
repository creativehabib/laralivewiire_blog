<!DOCTYPE html>
<html lang="bn">
    <head>
        <meta charset="UTF-8" />
        <title>{{ $title ?? 'বাংলাদেশী নিউজ পোর্টাল' }}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        {{-- Google Font --}}
        <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
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

            // পেজ লোড হওয়ার সাথে সাথে রান হবে
            applyTheme();

            // Livewire নেভিগেশন (এক পেজ থেকে অন্য পেজে গেলে) রান হবে
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

        @livewireScripts
        <script src="{{ asset('assets/js/script.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-php.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-javascript.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-css.min.js"></script>
        @stack('scripts')
    </body>
</html>
