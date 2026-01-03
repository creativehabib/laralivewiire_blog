<div class="space-y-4">
    <nav class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-2">
        <span class="uppercase tracking-[0.16em] text-sky-600 dark:text-sky-400">Dashboard</span>
        <span class="mx-1 text-slate-400">/</span>
        <span class="uppercase tracking-[0.16em] text-slate-600 dark:text-slate-300">Edit comment</span>
    </nav>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <div class="flex items-center gap-3 text-slate-800 dark:text-slate-100">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-sky-100 text-sky-600 dark:bg-sky-900/40">
                            <i class="fa-solid fa-comment"></i>
                        </span>
                        <div>
                            <h3 class="text-sm font-semibold">Comment details</h3>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400">Manage commenter details and content.</p>
                        </div>
                    </div>
                </div>

                <form wire:submit.prevent="save">
                    <div class="px-6 py-5 space-y-5">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-200 mb-1">Permalink:</label>
                            @if($permalink)
                                <a href="{{ $permalink }}" target="_blank" class="text-sm text-sky-600 hover:underline break-all">
                                    {{ $permalink }}
                                </a>
                            @else
                                <p class="text-sm text-slate-400">No permalink available.</p>
                            @endif
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-200 mb-1">
                                Name <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="text"
                                wire:model.live="name"
                                class="block w-full rounded-lg border px-3 py-2 text-sm
                                       border-slate-300 bg-slate-50 text-slate-900 placeholder-slate-400
                                       focus:border-sky-500 focus:ring-1 focus:ring-sky-500
                                       dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:placeholder-slate-500"
                                placeholder="Commenter name">
                            @error('name') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-200 mb-1">
                                Email
                            </label>
                            <input
                                type="email"
                                wire:model.live="email"
                                class="block w-full rounded-lg border px-3 py-2 text-sm
                                       border-slate-300 bg-slate-50 text-slate-900 placeholder-slate-400
                                       focus:border-sky-500 focus:ring-1 focus:ring-sky-500
                                       dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:placeholder-slate-500"
                                placeholder="name@example.com">
                            @error('email') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-200 mb-1">
                                URL
                            </label>
                            <input
                                type="url"
                                wire:model.live="website"
                                class="block w-full rounded-lg border px-3 py-2 text-sm
                                       border-slate-300 bg-slate-50 text-slate-900 placeholder-slate-400
                                       focus:border-sky-500 focus:ring-1 focus:ring-sky-500
                                       dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:placeholder-slate-500"
                                placeholder="https://example.com">
                            @error('website') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-200 mb-1">
                                Comment
                            </label>
                            <div wire:ignore>
                                <textarea
                                    id="comment-editor"
                                    class="block w-full rounded-lg border px-3 py-2 text-sm
                                           border-slate-300 bg-slate-50 text-slate-900 placeholder-slate-400
                                           focus:border-sky-500 focus:ring-1 focus:ring-sky-500
                                           dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:placeholder-slate-500"
                                    rows="10">{{ $content }}</textarea>
                            </div>
                            @error('content') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="space-y-4">
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
                <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-sm font-semibold text-slate-800 dark:text-slate-100">Publish</h3>
                </div>

                <div class="px-5 py-4 space-y-2">
                    <div class="flex gap-2">
                        <button type="button"
                                wire:click="save('stay')"
                                wire:loading.attr="disabled"
                                wire:target="save"
                                class="inline-flex flex-1 items-center justify-center gap-2 rounded-lg bg-sky-600 px-3 py-2 text-xs font-semibold text-white hover:bg-sky-500">
                            <span wire:loading.remove wire:target="save"><i class="fa-solid fa-floppy-disk text-xs"></i> Save</span>
                            <span wire:loading.inline wire:target="save" class="inline-flex items-center gap-2">
                                <svg class="h-3 w-3 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                </svg>
                                Saving...
                            </span>
                        </button>

                        <button type="button"
                                wire:click="save('exit')"
                                wire:loading.attr="disabled"
                                wire:target="save"
                                class="inline-flex flex-1 items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50
                                       dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800">
                            <i class="fa-solid fa-right-from-bracket text-xs"></i>
                            Save & Exit
                        </button>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
                <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-sm font-semibold text-slate-800 dark:text-slate-100">Status <span class="text-rose-500">*</span></h3>
                </div>
                <div class="px-5 py-4">
                    <select wire:model.live="status"
                            class="block w-full rounded-lg border px-3 py-2 text-sm
                                   border-slate-300 bg-white text-slate-800 focus:border-sky-500 focus:ring-sky-500
                                   dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100">
                        <option value="approved">Approved</option>
                        <option value="pending">Pending</option>
                        <option value="spam">Spam</option>
                        <option value="trash">Trash</option>
                    </select>
                    @error('status') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
                <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-sm font-semibold text-slate-800 dark:text-slate-100">Submitted on</h3>
                </div>
                <div class="px-5 py-4">
                    <input
                        type="text"
                        value="{{ $submittedOn ?? 'N/A' }}"
                        disabled
                        class="block w-full rounded-lg border px-3 py-2 text-sm
                               border-slate-200 bg-slate-100 text-slate-500
                               dark:border-slate-600 dark:bg-slate-900 dark:text-slate-400">
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function initCommentEditor() {
            const editorId = 'comment-editor';
            const textarea = document.getElementById(editorId);

            if (!textarea || !window.CKEDITOR) {
                return;
            }

            if (CKEDITOR.instances[editorId]) {
                CKEDITOR.instances[editorId].destroy(true);
            }

            if (typeof window.setupCkeditorBase === 'function') {
                window.setupCkeditorBase('{{ setting("hippo_api_key") }}');
            }

            const editor = CKEDITOR.replace(editorId, {
                height: 260,
            });

            editor.setData(textarea.value || '');

            editor.on('change', function () {
                @this.set('content', editor.getData());
            });
        }

        document.addEventListener('livewire:init', initCommentEditor);
        document.addEventListener('livewire:navigated', initCommentEditor);
    </script>
@endpush
