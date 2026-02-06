<div
    class="fixed inset-0 z-50 hidden items-end justify-center bg-slate-900/50 sm:items-center p-4 backdrop-blur-sm"
    data-delete-confirm-modal
    aria-hidden="true"
>
    <div
        class="relative w-full max-w-md overflow-hidden rounded-xl bg-white shadow-2xl ring-1 ring-slate-200 dark:bg-slate-900 dark:ring-slate-700"
        role="dialog"
        aria-modal="true"
        aria-labelledby="delete-confirm-title"
        aria-describedby="delete-confirm-message"
    >
        <div class="p-6 sm:p-8">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-rose-50 text-rose-600 dark:bg-rose-500/10 dark:text-rose-400">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-7 w-7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
            </div>

            <div class="mt-5 text-center">
                <h2 id="delete-confirm-title" class="text-lg font-semibold text-slate-900 dark:text-slate-100" data-confirm-title>
                    Confirm delete
                </h2>
                <p id="delete-confirm-message" class="mt-2 text-sm leading-6 text-slate-500 dark:text-slate-400" data-confirm-message>
                    Do you really want to delete this record?
                </p>
            </div>
        </div>

        <div class="flex flex-col gap-3 border-t border-slate-100 bg-slate-50/70 px-6 py-4 dark:border-slate-700 dark:bg-slate-800/60 sm:flex-row sm:justify-end">
            <button
                type="button"
                class="inline-flex w-full items-center justify-center rounded-lg bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-700 focus:ring-2 focus:ring-rose-500 focus:ring-offset-2 sm:w-auto"
                data-confirm-accept
            >
                Delete
            </button>

            <button
                type="button"
                class="inline-flex w-full items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 focus:ring-2 focus:ring-slate-200 focus:ring-offset-2 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700 sm:w-auto"
                data-confirm-cancel
            >
                Cancel
            </button>
        </div>
    </div>
</div>
