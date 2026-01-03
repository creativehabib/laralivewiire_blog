@php
    $adminTitle = setting('admin_title', config('app.name'));
    $pageTitle = isset($title) ? "{$title} | {$adminTitle}" : $adminTitle;
    $adminFavicon = setting('admin_favicon');
    $adminFaviconType = setting('admin_favicon_type', 'ico');
    $adminFont = setting('admin_primary_font', 'Inter');
    $adminFontMap = [
        'Inter' => [
            'family' => 'Inter',
            'url' => 'https://fonts.bunny.net/css?family=inter:400,500,600,700',
        ],
        'Roboto' => [
            'family' => 'Roboto',
            'url' => 'https://fonts.bunny.net/css?family=roboto:400,500,700',
        ],
        'Poppins' => [
            'family' => 'Poppins',
            'url' => 'https://fonts.bunny.net/css?family=poppins:400,500,600,700',
        ],
        'Open Sans' => [
            'family' => 'Open Sans',
            'url' => 'https://fonts.bunny.net/css?family=open-sans:400,600,700',
        ],
    ];
    $adminFontData = $adminFontMap[$adminFont] ?? $adminFontMap['Inter'];
    $adminPrimaryColor = setting('admin_primary_color', '#2563eb');
    $adminSecondaryColor = setting('admin_secondary_color', '#475569');
    $adminHeadingColor = setting('admin_heading_color', '#0f172a');
    $adminTextColor = setting('admin_text_color', '#334155');
    $adminLinkColor = setting('admin_link_color', '#2563eb');
    $adminLinkHoverColor = setting('admin_link_hover_color', '#1d4ed8');
    $adminPrimaryColorDark = setting('admin_primary_color_dark', $adminPrimaryColor);
    $adminSecondaryColorDark = setting('admin_secondary_color_dark', $adminSecondaryColor);
    $adminHeadingColorDark = setting('admin_heading_color_dark', $adminHeadingColor);
    $adminTextColorDark = setting('admin_text_color_dark', $adminTextColor);
    $adminLinkColorDark = setting('admin_link_color_dark', $adminLinkColor);
    $adminLinkHoverColorDark = setting('admin_link_hover_color_dark', $adminLinkHoverColor);
    $adminLogoHeight = (int) setting('admin_logo_height', 32);
    $adminFaviconMime = [
        'ico' => 'image/x-icon',
        'png' => 'image/png',
        'svg' => 'image/svg+xml',
    ][$adminFaviconType] ?? 'image/x-icon';
@endphp

<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="description" content="{{ $metaDescription ?? __('Streamlined Livewire starter dashboard for creating and managing content efficiently.') }}" />
<meta name="theme-color" content="{{ $adminPrimaryColor }}" />
<meta name="color-scheme" content="dark light" />

<title>{{ $pageTitle }}</title>

@if($adminFavicon)
    <link rel="icon" href="{{ $adminFavicon }}" type="{{ $adminFaviconMime }}">
@else
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
@endif

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="{{ $adminFontData['url'] }}" rel="stylesheet" />

<style>
    :root {
        --font-sans: "{{ $adminFontData['family'] }}", system-ui, -apple-system, BlinkMacSystemFont,
        "Segoe UI", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
        --color-primary: {{ $adminPrimaryColor }};
        --color-primary-dark: {{ $adminLinkHoverColor }};
        --color-secondary: {{ $adminSecondaryColor }};
        --color-accent: {{ $adminPrimaryColor }};
        --admin-heading-color: {{ $adminHeadingColor }};
        --admin-text-color: {{ $adminTextColor }};
        --admin-link-color: {{ $adminLinkColor }};
        --admin-link-hover-color: {{ $adminLinkHoverColor }};
        --admin-logo-height: {{ $adminLogoHeight }}px;
    }

    .admin-appearance.dark {
        --color-primary: {{ $adminPrimaryColorDark }};
        --color-primary-dark: {{ $adminLinkHoverColorDark }};
        --color-secondary: {{ $adminSecondaryColorDark }};
        --color-accent: {{ $adminPrimaryColorDark }};
        --admin-heading-color: {{ $adminHeadingColorDark }};
        --admin-text-color: {{ $adminTextColorDark }};
        --admin-link-color: {{ $adminLinkColorDark }};
        --admin-link-hover-color: {{ $adminLinkHoverColorDark }};
    }

    .admin-appearance body {
        color: var(--admin-text-color);
    }

    .admin-appearance a {
        color: var(--admin-link-color);
    }

    .admin-appearance a:hover {
        color: var(--admin-link-hover-color);
    }

    .admin-appearance h1,
    .admin-appearance h2,
    .admin-appearance h3,
    .admin-appearance h4,
    .admin-appearance h5,
    .admin-appearance h6 {
        color: var(--admin-heading-color);
    }

    .admin-appearance .flux-sidebar-brand img {
        height: var(--admin-logo-height);
        width: auto;
    }
</style>

@if($adminCustomCss = setting('admin_custom_css'))
    <style>{!! $adminCustomCss !!}</style>
@endif

@if($adminHeaderJs = setting('admin_header_js'))
    {!! $adminHeaderJs !!}
@endif

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
@stack('styles')
