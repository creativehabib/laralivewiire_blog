<div class="antialiased text-slate-900 dark:text-slate-100">
    <style>
        .CodeMirror { height: 500px; border-radius: 0.5rem; border: 1px solid #cbd5e1; font-size: 14px; }
        .dark .CodeMirror { border-color: #334155; }
    </style>

    <div class="max-w-5xl mx-auto">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">

            <div class="px-6 py-5 bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100 flex items-center gap-3">
                        <span class="flex items-center justify-center h-8 w-8 rounded-full bg-pink-100 dark:bg-pink-900/50 text-pink-600 dark:text-pink-400">
                            <i class="fas fa-paint-brush"></i>
                        </span>
                        Custom CSS
                    </h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 ml-11">Override default styles with your own CSS code.</p>
                </div>
            </div>

            <div class="p-6 space-y-8">
                <div x-data="{
                    init() {
                        if (typeof CodeMirror === 'undefined') return;
                        this.initEditor();
                    },
                    initEditor() {
                        const editor = CodeMirror.fromTextArea(this.$refs.cssEditor, {
                            mode: 'css',
                            theme: 'default',
                            lineNumbers: true,
                            matchBrackets: true,
                            indentUnit: 4,
                            indentWithTabs: true
                        });

                        const updateTheme = () => {
                            const isDark = document.documentElement.classList.contains('dark');
                            editor.setOption('theme', isDark ? 'monokai' : 'default');
                        }
                        updateTheme();

                        const observer = new MutationObserver(() => updateTheme());
                        observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });

                        editor.on('change', () => { @this.set('custom_css', editor.getValue()); });
                        setTimeout(() => editor.refresh(), 200);
                    }
                }">
                    <div wire:ignore class="shadow-sm rounded-lg overflow-hidden">
                        <textarea x-ref="cssEditor">{{ $custom_css }}</textarea>
                    </div>
                </div>
            </div>

            <div class="bg-slate-50 dark:bg-slate-900/80 px-6 py-4 border-t border-slate-200 dark:border-slate-700 flex justify-between items-center">
                <p class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-1.5">
                    <i class="fas fa-info-circle"></i> Use <code>!important</code> if styles are not applying.
                </p>
                <button wire:click="save" wire:loading.attr="disabled" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg text-sm font-bold shadow-md transition-all active:scale-95 flex items-center gap-2 disabled:opacity-70">
                    <span wire:loading.remove><i class="fas fa-save"></i> Save CSS</span>
                    <span wire:loading><i class="fas fa-circle-notch fa-spin"></i> Saving...</span>
                </button>
            </div>
        </div>
    </div>
</div>
