<x-theme.layout :menus="$menus" :active-menu="$activeMenu">
    @php
        $menus = is_array($menus) ? $menus : [];
        $activeMenu = $activeMenu ?? ($menus[0]['id'] ?? 'general');
    @endphp

    @switch($activeMenu)
        @case('header')
            @include('livewire.admin.settings.theme.partials.header-navigation')
            @break

        @case('layout')
            @include('livewire.admin.settings.theme.partials.layout-design')
            @break

        @case('homepage')
            @include('livewire.admin.settings.theme.partials.homepage-settings')
            @break

        @case('post')
            @include('livewire.admin.settings.theme.partials.post-details')
            @break

        @case('ads')
            @include('livewire.admin.settings.theme.partials.ad-management')
            @break

        @case('seo')
            @include('livewire.admin.settings.theme.partials.seo-social')
            @break

        @case('footer')
            @include('livewire.admin.settings.theme.partials.footer-settings')
            @break

        @default
            @include('livewire.admin.settings.theme.partials.general-settings')
    @endswitch
</x-theme.layout>
