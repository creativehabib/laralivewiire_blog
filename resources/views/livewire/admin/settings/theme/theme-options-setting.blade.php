<x-theme.layout :menus="$menus" :active-menu="$activeMenu">
    @php
        $menus = is_array($menus) ? $menus : [];
        $activeMenu = $activeMenu ?? ($menus[0]['id'] ?? 'general');
    @endphp

    @switch($activeMenu)
        @case('header')
            @include('livewire.admin.settings.theme.partials.header')
            @break

        @case('page')
            @include('livewire.admin.settings.theme.partials.page')
            @break

        @case('logo')
            @include('livewire.admin.settings.theme.partials.logo')
            @break

        @case('social_links')
            @include('livewire.admin.settings.theme.partials.social_links')
            @break

        @default
            @include('livewire.admin.settings.theme.partials.general')
    @endswitch
</x-theme.layout>
