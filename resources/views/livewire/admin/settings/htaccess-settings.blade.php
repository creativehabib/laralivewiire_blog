<div class="antialiased text-slate-900 dark:text-slate-100">
    <style>
        /* এডিটর ডিজাইন ফিক্স */
        .CodeMirror {
            height: 450px;
            border-bottom-left-radius: 0.5rem;
            border-bottom-right-radius: 0.5rem;
            border: 1px solid #cbd5e1;
            border-top: 0;
            font-size: 14px;
            z-index: 1; /* ক্লিক এনশিওর করার জন্য */
        }
        .dark .CodeMirror { border-color: #334155; }

        /* কার্সার ফিক্স */
        .CodeMirror-cursor { border-left: 2px solid #000 !important; }
        .dark .CodeMirror-cursor { border-left: 2px solid #fff !important; }
    </style>

    <div class="max-w-6xl mx-auto">

        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden"
             x-data="{
                activeTab: 'root', // পরিবর্তন ১: ডিফল্ট ট্যাব 'root' করা হয়েছে
                isDirty: false,
                editors: {},

                init() {
                    if (typeof CodeMirror === 'undefined') return;

                    // এডিটর ইনিশিওলাইজ
                    this.initEditor('rootEditor', 'rootContent', 'root');
                    this.initEditor('publicEditor', 'publicContent', 'public');

                    // ১. ইনিশিয়াল লোড ফিক্স
                    setTimeout(() => { this.refreshAll(); }, 200);

                    // ২. ট্যাব পাল্টালে রিফ্রেশ
                    this.$watch('activeTab', (value) => {
                        this.$nextTick(() => {
                            if(this.editors[value]) {
                                this.editors[value].refresh();
                                this.editors[value].focus();
                            }
                        });
                    });

                    // সার্ভার থেকে রিস্টোর হলে
                    window.addEventListener('htaccess-restored', event => {
                         const type = event.detail.type;
                         if(this.editors[type]) {
                             this.editors[type].setValue(event.detail.content);
                             setTimeout(() => this.editors[type].refresh(), 50);
                             this.isDirty = true;
                         }
                    });

                    Livewire.on('media-toast', () => { this.isDirty = false; });
                },

                // সব এডিটর রিফ্রেশ করার ফাংশন
                refreshAll() {
                    if(this.editors['public']) this.editors['public'].refresh();
                    if(this.editors['root']) this.editors['root'].refresh();
                },

                initEditor(ref, model, key) {
                    if (!this.$refs[ref]) return;

                    const editor = CodeMirror.fromTextArea(this.$refs[ref], {
                        mode: 'shell',
                        theme: 'default',
                        lineNumbers: true,
                        matchBrackets: true,
                        indentUnit: 4,
                        viewportMargin: Infinity
                    });

                    this.editors[key] = editor;

                    // থিম হ্যান্ডলিং
                    const updateTheme = () => {
                        const isDark = document.documentElement.classList.contains('dark');
                        editor.setOption('theme', isDark ? 'monokai' : 'default');
                    }
                    updateTheme();
                    new MutationObserver(updateTheme).observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });

                    // ডাটা বাইন্ডিং
                    editor.on('change', () => {
                        @this.set(model, editor.getValue(), false);
                        this.isDirty = true;
                    });

                    // ক্লিক করলে রিফ্রেশ হবে
                    editor.on('mousedown', () => { editor.refresh(); });
                }
            }"
        >

            {{-- Header Area --}}
            <div class="px-6 py-5 bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100 flex items-center gap-3">
                        <span class="flex items-center justify-center h-8 w-8 rounded-full bg-rose-100 dark:bg-rose-900/50 text-rose-600 dark:text-rose-400">
                            <i class="fas fa-server"></i>
                        </span>
                        .htaccess Editor
                    </h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 ml-11">Manage server configuration files safely.</p>
                </div>

                {{-- Restore Buttons (অর্ডার চেঞ্জ করা হয়েছে) --}}
                <div>
                    {{-- Root Restore Button --}}
                    <button x-show="activeTab === 'root'" wire:click="restoreDefaults('root')" wire:confirm="Are you sure?" class="text-xs font-bold text-rose-600 dark:text-rose-400 cursor-pointer hover:bg-rose-50 dark:hover:bg-rose-900/30 px-3 py-1.5 rounded-lg border border-rose-200 dark:border-rose-800 transition-colors flex items-center gap-2">
                        <i class="fas fa-undo-alt"></i> Restore Default (Root)
                    </button>

                    {{-- Public Restore Button --}}
                    <button x-show="activeTab === 'public'" style="display: none;" wire:click="restoreDefaults('public')" wire:confirm="Are you sure?" class="text-xs font-bold text-rose-600 cursor-pointer dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-900/30 px-3 py-1.5 rounded-lg border border-rose-200 dark:border-rose-800 transition-colors flex items-center gap-2">
                        <i class="fas fa-undo-alt"></i> Restore Default (Public)
                    </button>
                </div>
            </div>

            {{-- Warning Banner --}}
            <div class="px-6 pt-6">
                <div class="bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 rounded-lg p-3 flex items-start gap-3">
                    <i class="fas fa-exclamation-triangle text-rose-500 mt-0.5"></i>
                    <div class="text-xs text-rose-700 dark:text-rose-300 leading-relaxed">
                        <strong>Warning:</strong> Incorrect configurations here can break your website immediately (500 Internal Server Error).
                        If that happens, revert changes via FTP/File Manager.
                    </div>
                </div>
            </div>

            {{-- Tabs Navigation --}}
            <div class="mt-6 px-6 border-b border-slate-200 dark:border-slate-700">
                <div class="flex space-x-1 -mb-px">
                    {{-- 1. Root Tab (Moved to First Position) --}}
                    <button
                        @click="activeTab = 'root'"
                        :class="activeTab === 'root'
                            ? 'border-rose-500 text-rose-600 dark:text-rose-400 bg-white dark:bg-slate-800 cursor-pointer'
                            : 'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/50 cursor-pointer'"
                        class="flex items-center gap-2 px-6 py-3 text-sm font-bold border-t border-l border-r rounded-t-lg transition-all"
                    >
                        <i class="fas fa-folder-open"></i> Root (.htaccess)
                        <span class="ml-1 px-1.5 py-0.5 rounded-full bg-rose-100 dark:bg-rose-900/50 text-[10px] text-rose-600 dark:text-rose-300">Redirect</span>
                    </button>

                    {{-- 2. Public Tab (Moved to Second Position) --}}
                    <button
                        @click="activeTab = 'public'"
                        :class="activeTab === 'public'
                            ? 'border-rose-500 text-rose-600 dark:text-rose-400 bg-white dark:bg-slate-800 cursor-pointer'
                            : 'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/50 cursor-pointer'"
                        class="flex items-center gap-2 px-6 py-3 text-sm font-bold border-t border-l border-r rounded-t-lg transition-all"
                    >
                        <i class="fas fa-globe"></i> Public (.htaccess)
                        <span class="ml-1 px-1.5 py-0.5 rounded-full bg-indigo-100 dark:bg-indigo-900/50 text-[10px] text-indigo-600 dark:text-indigo-300">Main App</span>
                    </button>
                </div>
            </div>

            {{-- Editors Area --}}
            <div class="p-6">

                {{-- Root Editor (First) --}}
                <div x-show="activeTab === 'root'" x-cloak>
                    <div wire:ignore wire:key="root-editor-wrap" class="shadow-sm rounded-lg">
                        <textarea x-ref="rootEditor">{{ $rootContent }}</textarea>
                    </div>
                    <p class="mt-2 text-xs text-slate-400">Editing <code>root/.htaccess</code> (Base path redirect)</p>
                </div>

                {{-- Public Editor (Second) --}}
                <div x-show="activeTab === 'public'" x-cloak>
                    <div wire:ignore wire:key="public-editor-wrap" class="shadow-sm rounded-lg">
                        <textarea x-ref="publicEditor">{{ $publicContent }}</textarea>
                    </div>
                    <p class="mt-2 text-xs text-slate-400">Editing <code>public/.htaccess</code> (Main entry point)</p>
                </div>

            </div>

            {{-- Footer Action --}}
            <div class="bg-slate-50 dark:bg-slate-900/80 px-6 py-4 border-t border-slate-200 dark:border-slate-700 flex justify-between items-center sticky bottom-0 z-10">
                <div class="text-xs text-slate-500">
                    <span x-show="isDirty" class="text-rose-500 font-bold animate-pulse">
                        <i class="fas fa-circle text-[8px] mr-1"></i> Unsaved changes
                    </span>
                    <span x-show="!isDirty" class="text-emerald-500 font-bold">
                        <i class="fas fa-check-circle mr-1"></i> Synced
                    </span>
                </div>

                <button
                    wire:click="save"
                    wire:loading.attr="disabled"
                    :disabled="!isDirty"
                    :class="isDirty
                        ? 'bg-rose-600 hover:bg-rose-700 text-white shadow-md cursor-pointer'
                        : 'bg-slate-300 dark:bg-slate-700 text-slate-500 cursor-not-allowed shadow-none'"
                    class="px-6 py-2.5 rounded-lg text-sm font-bold transition-all active:scale-95 flex items-center gap-2 disabled:opacity-70"
                >
                    <span wire:loading><i class="fas fa-circle-notch fa-spin"></i> Saving...</span>

                    <span wire:loading.remove>
                        <i class="fas" :class="isDirty ? 'fa-save' : 'fa-check'"></i>
                        <span x-text="isDirty ? 'Save All Changes' : 'Saved'"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
