<div class="antialiased text-slate-900 dark:text-slate-100">
    <style>
        .CodeMirror { height: 300px; border-radius: 0.5rem; border: 1px solid #cbd5e1; font-size: 14px; }
        .dark .CodeMirror { border-color: #334155; }
    </style>

    <div>
        {{-- ১. x-data মেইন কার্ডে নিয়ে আসা হয়েছে (Scope Fix) --}}
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden"
             x-data="{
                isDirty: false, // শুরুতে বাটন ইনএক্টিভ
                init() {
                    if (typeof CodeMirror === 'undefined') return;

                    this.initEditor('headerJs', 'custom_header_js');
                    this.initEditor('bodyJs', 'custom_body_js');
                    this.initEditor('footerJs', 'custom_footer_js');

                    // সেভ হওয়ার পর বাটন রিসেট
                    Livewire.on('media-toast', () => {
                        this.isDirty = false;
                    });
                },
                initEditor(ref, model) {
                    if (!this.$refs[ref]) return;

                    const editor = CodeMirror.fromTextArea(this.$refs[ref], {
                        mode: 'javascript', theme: 'default', lineNumbers: true, matchBrackets: true, indentUnit: 4
                    });

                    const updateTheme = () => {
                        const isDark = document.documentElement.classList.contains('dark');
                        editor.setOption('theme', isDark ? 'monokai' : 'default');
                    }
                    updateTheme();

                    new MutationObserver(updateTheme).observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });

                    // ২. Logic Fix: টাইপ করার পরই কেবল isDirty সত্য হবে
                    editor.on('change', () => {
                        @this.set(model, editor.getValue(), false); // ডাটা আপডেট হবে, রিলোড হবে না
                        this.isDirty = true; // বাটন একটিভ হবে
                    });

                    setTimeout(() => editor.refresh(), 200);
                }
            }"
        >

            {{-- Header --}}
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

            {{-- Editors Body --}}
            <div class="p-6 space-y-8">
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

            {{-- Footer Action --}}
            <div class="bg-slate-50 dark:bg-slate-900/80 px-6 py-4 border-t border-slate-200 dark:border-slate-700 flex justify-between items-center sticky bottom-0 z-10">
                <p class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-1.5">
                    <i class="fas fa-exclamation-triangle text-amber-500"></i> Wrap code with <code>&lt;script&gt;</code> tags.
                </p>

                {{-- ৩. বাটন ডাইনামিক করা হয়েছে --}}
                <button
                    wire:click="save"
                    wire:loading.attr="disabled"
                    :disabled="!isDirty"
                    :class="isDirty
                        ? 'bg-indigo-600 hover:bg-indigo-700 text-white shadow-md cursor-pointer'
                        : 'bg-slate-300 dark:bg-slate-700 text-slate-500 cursor-not-allowed shadow-none'"
                    class="px-6 py-2.5 rounded-lg text-sm font-bold transition-all active:scale-95 flex items-center gap-2 disabled:opacity-70"
                >
                    <span wire:loading><i class="fas fa-circle-notch fa-spin"></i> Saving...</span>

                    <span wire:loading.remove>
                        <i class="fas" :class="isDirty ? 'fa-save' : 'fa-check'"></i>
                        <span x-text="isDirty ? 'Save Scripts' : 'Saved'"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
