<x-theme.layout>
    @php
        $menus = config('theme-options.menus', []);
        $activeMenu = request()->query('as', $menus[0]['id'] ?? 'general');
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

        @case('social')
            @include('livewire.admin.settings.theme.partials.social')
            @break

        @default
            @include('livewire.admin.settings.theme.partials.general')
    @endswitch
</x-theme.layout>
