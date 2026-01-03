<div wire:poll.15s="checkForUpdates">
    <flux:sidebar.item
        icon="chat-bubble-left-ellipsis"
        :href="route('admin.comments.moderation')"
        :current="request()->routeIs('admin.comments.*')"
        tooltip="{{ __('Comments') }}"
        wire:navigate
    >
        <span class="flex items-center justify-between gap-2 w-full">
            <span>{{ __('Comments') }}</span>
            @if($pendingCount > 0)
                <span class="inline-flex items-center rounded-full bg-blue-500 px-2 py-0.5 text-[10px] font-semibold text-white">
                    {{ $pendingCount }}
                </span>
            @endif
        </span>
    </flux:sidebar.item>
</div>
