<x-layouts.frontend.app :title="$title" :seo="$seo">
    @livewire($livewireComponent, $livewireParams, key($livewireComponent . '-' . ($livewireParams[array_key_first($livewireParams)]->id ?? '')))
</x-layouts.frontend.app>
