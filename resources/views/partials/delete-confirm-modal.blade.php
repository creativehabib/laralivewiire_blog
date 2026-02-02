<div
    class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 px-4 py-6 backdrop-blur-sm"
    data-delete-confirm-modal
    aria-hidden="true"
>
    <div
        class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl ring-1 ring-slate-200 dark:bg-slate-900 dark:ring-slate-700"
        role="dialog"
        aria-modal="true"
        aria-labelledby="delete-confirm-title"
        aria-describedby="delete-confirm-message"
    >
        <div class="flex items-start justify-between gap-4">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full border border-rose-500/60 text-rose-500">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 9v4"></path>
                    <path d="M12 17h.01"></path>
                    <path d="M10.29 3.86l-8.02 13.89A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3.25l-8.02-13.89a2 2 0 0 0-3.46 0z"></path>
                </svg>
            </div>

            <button
                type="button"
                class="rounded-full p-1 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-slate-800"
                data-confirm-cancel
                aria-label="Close"
            >
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 6L6 18"></path>
                    <path d="M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="mt-4 text-center">
            <h2 id="delete-confirm-title" class="text-lg font-semibold text-slate-900 dark:text-slate-100" data-confirm-title>
                Confirm delete
            </h2>
            <p id="delete-confirm-message" class="mt-2 text-sm text-slate-500 dark:text-slate-400" data-confirm-message>
                Do you really want to delete this record?
            </p>
        </div>

        <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-center">
            <button
                type="button"
                class="inline-flex w-full items-center justify-center rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-500 sm:w-40"
                data-confirm-accept
            >
                Delete
            </button>
            <button
                type="button"
                class="inline-flex w-full items-center justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800 sm:w-40"
                data-confirm-cancel
            >
                Close
            </button>
        </div>
    </div>
</div>
