<div class="antialiased text-slate-900 dark:text-slate-100">
    <style>
        .CodeMirror { height: 300px; border-radius: 0.5rem; border: 1px solid #cbd5e1; font-size: 14px; }
        .dark .CodeMirror { border-color: #334155; }
    </style>

    <div class="max-w-5xl mx-auto">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">

            <div class="px-6 py-5 bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100 flex items-center gap-3">
                        <span class="flex items-center justify-center h-8 w-8 rounded-full bg-amber-100 dark:bg-amber-900/50 text-amber-600 dark:text-amber-400">
                            <i class="fas fa-scroll"></i>
                        </span>
                        Custom JavaScript
                    </h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 ml-11">Inject custom scripts into Header, Body, or Footer.</p>
                </div>
            </div>

            <div class="p-6 space-y-8" x-data="{
                init() {
                    if (typeof CodeMirror === 'undefined') return;
                    this.initEditor('headerJs', 'custom_header_js');
                    this.initEditor('bodyJs', 'custom_body_js');
                    this.initEditor('footerJs', 'custom_footer_js');
                },
                initEditor(ref, model) {
                    const editor = CodeMirror.fromTextArea(this.$refs[ref], {
                        mode: 'javascript', theme: 'default', lineNumbers: true, matchBrackets: true, indentUnit: 4
                    });
                    const updateTheme = () => {
                        const isDark = document.documentElement.classList.contains('dark');
                        editor.setOption('theme', isDark ? 'monokai' : 'default');
                    }
                    updateTheme();
                    new MutationObserver(updateTheme).observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
                    editor.on('change', () => { @this.set(model, editor.getValue()); });
                    setTimeout(() => editor.refresh(), 200);
                }
            }">
                <div wire:ignore>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-2 flex items-center gap-2">
                        <i class="fab fa-js text-amber-500"></i> Header JS <span class="text-xs font-normal text-slate-400">(Inside &lt;head&gt;)</span>
                    </label>
                    <textarea x-ref="headerJs">{{ $custom_header_js }}</textarea>
                </div>

                <div wire:ignore>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-2 flex items-center gap-2">
                        <i class="fab fa-js text-amber-500"></i> Body JS <span class="text-xs font-normal text-slate-400">(Start of &lt;body&gt;)</span>
                    </label>
                    <textarea x-ref="bodyJs">{{ $custom_body_js }}</textarea>
                </div>

                <div wire:ignore>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-2 flex items-center gap-2">
                        <i class="fab fa-js text-amber-500"></i> Footer JS <span class="text-xs font-normal text-slate-400">(End of &lt;body&gt;)</span>
                    </label>
                    <textarea x-ref="footerJs">{{ $custom_footer_js }}</textarea>
                </div>
            </div>

            <div class="bg-slate-50 dark:bg-slate-900/80 px-6 py-4 border-t border-slate-200 dark:border-slate-700 flex justify-between items-center">
                <p class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-1.5">
                    <i class="fas fa-exclamation-triangle text-amber-500"></i> Wrap code with <code>&lt;script&gt;</code> tags.
                </p>
                <button wire:click="save" wire:loading.attr="disabled" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg text-sm font-bold shadow-md transition-all active:scale-95 flex items-center gap-2 disabled:opacity-70">
                    <span wire:loading.remove><i class="fas fa-save"></i> Save Scripts</span>
                    <span wire:loading><i class="fas fa-circle-notch fa-spin"></i> Saving...</span>
                </button>
            </div>
        </div>
    </div>
</div>
