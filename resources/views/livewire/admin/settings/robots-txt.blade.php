<div class="antialiased text-slate-900 dark:text-slate-100">
    <style>
        .CodeMirror {
            height: 400px;
            border-radius: 0.5rem;
            border: 1px solid #cbd5e1; /* slate-300 */
            font-size: 14px;
        }
        .dark .CodeMirror {
            border-color: #334155; /* slate-700 */
        }
    </style>

    <div class="max-w-5xl mx-auto">
        {{-- ১. x-data কে মেইন কার্ডে নিয়ে আসা হয়েছে (Scope Fix) --}}
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden"
             x-data="{
                isDirty: false, // বাটন স্ট্যাটাস ট্র্যাক করার জন্য
                init() {
                    // চেক করা CodeMirror লোড হয়েছে কিনা
                    if (typeof CodeMirror === 'undefined') {
                        console.error('CodeMirror not loaded via app.js');
                        return;
                    }
                    this.initEditor();

                    // সেভ হওয়ার পর বাটন রিসেট (Saved) করার লিসেনার
                    Livewire.on('media-toast', () => {
                        this.isDirty = false;
                    });
                },
                initEditor() {
                    // Ref চেক করা
                    if (!this.$refs.robotsEditor) return;

                    const editor = CodeMirror.fromTextArea(this.$refs.robotsEditor, {
                        mode: 'javascript', // Robots.txt এর জন্য প্লেইন টেক্সট বা JS মোড চলে
                        theme: 'default',
                        lineNumbers: true,
                        matchBrackets: true,
                        indentUnit: 4,
                        indentWithTabs: true
                    });

                    // Theme Handling (Dark/Light)
                    const updateTheme = () => {
                        const isDark = document.documentElement.classList.contains('dark');
                        editor.setOption('theme', isDark ? 'monokai' : 'default');
                    }
                    updateTheme();

                    // Observer for Theme Change
                    const observer = new MutationObserver(() => updateTheme());
                    observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });

                    // ২. Livewire Update (Editor -> PHP)
                    editor.on('change', () => {
                        // 'false' প্যারামিটার দেওয়া হয়েছে যাতে টাইপ করার সময় রিকোয়েস্ট না যায়
                        @this.set('robotsContent', editor.getValue(), false);
                        this.isDirty = true; // বাটন একটিভ হবে
                    });

                    // ৩. File Upload Update (PHP -> Editor)
                    window.addEventListener('robots-content-updated', event => {
                        editor.setValue(event.detail.content);
                        this.isDirty = true; // ফাইল আপলোড করলে বাটন একটিভ হবে যাতে ইউজার সেভ করতে পারে
                    });

                    // Fix generic display issues
                    setTimeout(() => editor.refresh(), 200);
                }
            }"
        >

            {{-- Header --}}
            <div class="px-6 py-5 bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100 flex items-center gap-3">
                        <span class="flex items-center justify-center h-8 w-8 rounded-full bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400">
                            <i class="fas fa-robot"></i>
                        </span>
                        robots.txt Editor
                    </h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 ml-11">Control crawler access and specify sitemap location for search engines.</p>
                </div>
                <a href="{{ $robotsUrl }}" target="_blank" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1.5 bg-indigo-50 dark:bg-indigo-900/30 px-3 py-1.5 rounded-full transition-colors hover:bg-indigo-100 dark:hover:bg-indigo-900/50">
                    <i class="fas fa-external-link-alt"></i> View live file
                </a>
            </div>

            <div class="p-6 space-y-6">

                {{-- Editor Container --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-2">Robots.txt Content</label>

                    <div wire:ignore>
                        <textarea x-ref="robotsEditor" placeholder="User-agent: *&#10;Allow: /&#10;Sitemap: {{ $robotsUrl }}">{{ $robotsContent }}</textarea>
                    </div>

                    @error('robotsContent')
                    <p class="mt-2 text-xs text-rose-500">{{ $message }}</p>
                    @enderror

                    <p class="mt-3 text-xs text-slate-500 dark:text-slate-400">
                        After saving, check your robots.txt here:
                        <a href="{{ $robotsUrl }}" target="_blank" class="font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">{{ $robotsUrl }}</a>
                    </p>
                </div>

                {{-- File Upload Section --}}
                <div class="border border-dashed border-slate-300 dark:border-slate-700 rounded-lg p-4 bg-slate-50 dark:bg-slate-900/30">
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-2">Upload robots.txt file</label>
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                        <input type="file" wire:model="uploadedFile" accept=".txt" class="block text-sm text-slate-600 dark:text-slate-300
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-full file:border-0
                            file:text-xs file:font-semibold
                            file:bg-indigo-50 file:text-indigo-700
                            hover:file:bg-indigo-100
                            dark:file:bg-slate-700 dark:file:text-slate-200 cursor-pointer
                        ">
                        @if ($uploadedFile)
                            <span class="inline-flex items-center px-2 py-1 text-[11px] rounded bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300">
                                <i class="fas fa-check mr-1"></i> {{ $uploadedFile->getClientOriginalName() }}
                            </span>
                        @endif
                    </div>

                    {{-- Uploading Indicator --}}
                    <div wire:loading wire:target="uploadedFile" class="mt-2 text-xs text-indigo-600 font-bold flex items-center">
                        <i class="fas fa-spinner fa-spin mr-2"></i> Processing file...
                    </div>

                    @error('uploadedFile')
                    <p class="mt-2 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Uploading a file will overwrite the text content above automatically.</p>
                </div>
            </div>

            {{-- Footer Action --}}
            <div class="bg-slate-50 dark:bg-slate-900/80 px-6 py-4 border-t border-slate-200 dark:border-slate-700 flex justify-end sticky bottom-0 z-10">
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
                        <span x-text="isDirty ? 'Save Changes' : 'Saved'"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
