<div
    class="fixed inset-0 z-50 hidden items-end justify-center bg-slate-900/50 sm:items-center p-4 backdrop-blur-sm"
    data-delete-confirm-modal
    aria-hidden="true"
>
    <div
        class="relative w-full max-w-lg overflow-hidden rounded-xl bg-white shadow-2xl ring-1 ring-slate-200 dark:bg-slate-900 dark:ring-slate-700"
        role="dialog"
        aria-modal="true"
        aria-labelledby="delete-confirm-title"
        aria-describedby="delete-confirm-message"
    >
        <div class="space-y-6 p-6">
            <div class="space-y-2 text-center">
                <flux:heading size="lg" id="delete-confirm-title" data-confirm-title>
                    Confirm delete
                </flux:heading>

                <flux:subheading id="delete-confirm-message" data-confirm-message>
                    Do you really want to delete this record?
                </flux:subheading>
            </div>

            <div class="flex flex-col justify-center gap-3 sm:flex-row sm:gap-2">
                <flux:button variant="danger" class="w-full sm:w-auto" type="button" data-confirm-accept>
                    Delete
                </flux:button>

                <flux:button variant="filled" class="w-full sm:w-auto" type="button" data-confirm-cancel>
                    Cancel
                </flux:button>
            </div>
        </div>
    </div>
</div>
