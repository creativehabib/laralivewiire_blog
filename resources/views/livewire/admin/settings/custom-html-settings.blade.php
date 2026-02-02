<div class="antialiased text-slate-900 dark:text-slate-100">
    <style>
        .CodeMirror { height: 300px; border-radius: 0.5rem; border: 1px solid #cbd5e1; font-size: 14px; }
        .dark .CodeMirror { border-color: #334155; }
    </style>

    <div>
        {{-- ১. x-data কে মেইন কার্ডে নিয়ে আসা হয়েছে যাতে বাটন এবং এডিটর একই স্কোপে থাকে --}}
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden"
             x-data="{
                isDirty: false, // শুরুতে বাটন ইনএক্টিভ থাকবে
                init() {
                    if (typeof CodeMirror === 'undefined') return;

                    // এডিটর লোড করা
                    this.initEditor('headerHtml', 'custom_header_html');
                    this.initEditor('bodyHtml', 'custom_body_html');
                    this.initEditor('footerHtml', 'custom_footer_html');

                    // সেভ হওয়ার পর বাটন রিসেট করা
                    Livewire.on('media-toast', () => {
                        this.isDirty = false;
                    });
                },
                initEditor(ref, model) {
                    // Ref চেক করা (এরর এড়াতে)
                    if (!this.$refs[ref]) return;

                    const editor = CodeMirror.fromTextArea(this.$refs[ref], {
                        mode: 'htmlmixed', theme: 'default', lineNumbers: true, matchBrackets: true, indentUnit: 4
                    });

                    const updateTheme = () => {
                        const isDark = document.documentElement.classList.contains('dark');
                        editor.setOption('theme', isDark ? 'monokai' : 'default');
                    }
                    updateTheme();

                    new MutationObserver(updateTheme).observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });

                    // ২. লজিক ফিক্স: টাইপ করার পরই কেবল isDirty সত্য হবে
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
                        <span class="flex items-center justify-center h-8 w-8 rounded-full bg-orange-100 dark:bg-orange-900/50 text-orange-600 dark:text-orange-400">
                            <i class="fab fa-html5"></i>
                        </span>
                        Custom HTML
                    </h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 ml-11">Add verification meta tags, tracking pixels, or custom widgets.</p>
                </div>
            </div>

            {{-- Editors Body --}}
            <div class="p-6 space-y-8">
                <div wire:ignore>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-2 flex items-center gap-2">
                        <i class="fas fa-code text-orange-500"></i> Header HTML <span class="text-xs font-normal text-slate-400">(Meta tags, CSS links)</span>
                    </label>
                    <textarea x-ref="headerHtml">{{ $custom_header_html }}</textarea>
                </div>

                <div wire:ignore>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-2 flex items-center gap-2">
                        <i class="fas fa-code text-orange-500"></i> Body HTML <span class="text-xs font-normal text-slate-400">(Top of body)</span>
                    </label>
                    <textarea x-ref="bodyHtml">{{ $custom_body_html }}</textarea>
                </div>

                <div wire:ignore>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-2 flex items-center gap-2">
                        <i class="fas fa-code text-orange-500"></i> Footer HTML <span class="text-xs font-normal text-slate-400">(Before closing body)</span>
                    </label>
                    <textarea x-ref="footerHtml">{{ $custom_footer_html }}</textarea>
                </div>
            </div>

            {{-- Footer Action --}}
            <div class="bg-slate-50 dark:bg-slate-900/80 px-6 py-4 border-t border-slate-200 dark:border-slate-700 flex justify-between items-center sticky bottom-0 z-10">
                <p class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-1.5">
                    <i class="fas fa-info-circle"></i> Don't forget to close your tags properly.
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
                        <span x-text="isDirty ? 'Save HTML' : 'Saved'"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
