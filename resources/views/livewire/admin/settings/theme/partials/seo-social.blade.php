<form wire:submit.prevent="saveSeo" class="space-y-8">
    <div class="space-y-4">
        @include('livewire.admin.settings.theme.partials.social_links', ['showSaveButton' => true])
    </div>
</form>
