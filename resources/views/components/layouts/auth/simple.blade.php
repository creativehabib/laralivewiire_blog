@php
    $adminLocale = setting('admin_language', app()->getLocale());
    $adminDirection = setting('admin_language_direction', 'ltr');
    $adminTitle = setting('admin_title', config('app.name'));
    $adminLogo = setting('admin_logo');
    $adminLoginBackground = setting('admin_login_background');
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', $adminLocale) }}" dir="{{ $adminDirection }}" class="dark admin-appearance">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white antialiased dark:bg-linear-to-b dark:from-slate-950 dark:to-slate-900" @if($adminLoginBackground) style="background-image: url('{{ $adminLoginBackground }}'); background-size: cover; background-position: center;" @endif>
        <div class="bg-background flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
            <div class="flex w-full max-w-sm flex-col gap-2">
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 font-medium" wire:navigate>
                    <span class="flex h-9 w-9 mb-1 items-center justify-center rounded-md">
                        @if($adminLogo)
                            <img src="{{ $adminLogo }}" alt="{{ $adminTitle }}" class="h-9 w-auto" />
                        @else
                            <x-app-logo-icon class="size-9 fill-current text-black dark:text-white" />
                        @endif
                    </span>
                    <span class="sr-only">{{ $adminTitle }}</span>
                </a>
                <div class="flex flex-col gap-6">
                    {{ $slot }}
                </div>
            </div>
        </div>

        @if($adminBodyJs = setting('admin_body_js'))
            {!! $adminBodyJs !!}
        @endif
        @fluxScripts

        @if($adminFooterJs = setting('admin_footer_js'))
            {!! $adminFooterJs !!}
        @endif
    </body>
</html>
