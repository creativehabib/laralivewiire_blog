<div class="antialiased text-slate-900 dark:text-slate-100">
    <style>
        /* CodeMirror Styling */
        .CodeMirror { height: 300px; border-radius: 0.5rem; border: 1px solid #cbd5e1; font-size: 14px; }
        .dark .CodeMirror { border-color: #334155; }
    </style>

    <div class="max-w-5xl mx-auto">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden"
             x-data="{
                mode: @entangle('mode'),
                isDirty: false, // Save button state track করার জন্য
                editorInstance: null,

                init() {
                    // পেজ লোড হলে যদি Auto mode থাকে তবে এডিটর লোড করো
                    if (this.mode === 'auto') {
                        this.$nextTick(() => this.initOrRefreshEditor());
                    }

                    // Mode পরিবর্তন হলে
                    this.$watch('mode', value => {
                        this.isDirty = true; // Mode চেঞ্জ হলে Save বাটন একটিভ হবে
                        if (value === 'auto') {
                            this.$nextTick(() => { this.initOrRefreshEditor(); });
                        }
                    });

                    // সার্ভার থেকে সেভ হওয়ার পর বাটন রিসেট
                    Livewire.on('media-toast', () => {
                        this.isDirty = false;
                    });
                },

                initOrRefreshEditor() {
                    if (typeof CodeMirror === 'undefined') return;

                    // যদি এডিটর আগে থেকেই থাকে, তবে রিফ্রেশ করো
                    if (this.editorInstance) {
                        this.editorInstance.refresh();
                        return;
                    }

                    if (!this.$refs.snippetEditor) return;

                    const editor = CodeMirror.fromTextArea(this.$refs.snippetEditor, {
                        mode: 'htmlmixed', theme: 'default', lineNumbers: true, matchBrackets: true, indentUnit: 4
                    });

                    this.editorInstance = editor;

                    // Theme update logic
                    const updateTheme = () => {
                        const isDark = document.documentElement.classList.contains('dark');
                        editor.setOption('theme', isDark ? 'monokai' : 'default');
                    }
                    updateTheme();
                    new MutationObserver(updateTheme).observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });

                    // Editor change event
                    editor.on('change', () => {
                        // ডাটা আপডেট করো কিন্তু রি-রেন্ডার করো না (false)
                        @this.set('auto_ads_code', editor.getValue(), false);
                        this.isDirty = true; // বাটন একটিভ করো
                    });

                    setTimeout(() => editor.refresh(), 50);
                }
             }"
        >

            {{-- Header --}}
            <div class="px-6 py-5 bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
                <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100 flex items-center gap-2">
                    <span class="flex items-center justify-center h-8 w-8 rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400">
                        <i class="fab fa-google"></i>
                    </span>
                    Google AdSense Mode
                </h2>

                {{-- Mode Selection Radio Buttons --}}
                <div class="mt-4 flex flex-wrap gap-6">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="radio" value="disabled" x-model="mode" @change="isDirty = true" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300 group-hover:text-blue-600 transition-colors">Disabled</span>
                    </label>

                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="radio" value="auto" x-model="mode" @change="isDirty = true" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300 group-hover:text-blue-600 transition-colors">Auto Ads</span>
                    </label>

                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="radio" value="unit" x-model="mode" @change="isDirty = true" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300 group-hover:text-blue-600 transition-colors">Unit Ads</span>
                    </label>
                </div>
            </div>

            <div class="p-6 space-y-6">

                {{-- Info Box --}}
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-info-circle text-blue-500 mt-1"></i>
                        <div class="text-sm text-blue-800 dark:text-blue-200">
                            <strong class="block mb-1">Mode Options:</strong>
                            <ul class="list-disc ml-4 space-y-1 text-xs sm:text-sm text-blue-700 dark:text-blue-300">
                                <li><strong>Disabled:</strong> Ads are turned off.</li>
                                <li><strong>Auto Ads:</strong> Google automatically places ads. Paste the snippet below.</li>
                                <li><strong>Unit Ads:</strong> Manual placement using Client ID & Slot IDs.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- 1. Auto Ads Section --}}
                <div x-show="mode === 'auto'" x-cloak class="space-y-4">
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-200">
                        Google AdSense Auto Ads Snippet
                    </label>

                    {{-- wire:ignore prevents Livewire from re-rendering this block --}}
                    <div wire:ignore class="rounded-lg overflow-hidden shadow-sm">
                        <textarea x-ref="snippetEditor">{{ $auto_ads_code }}</textarea>
                    </div>

                    <p class="text-xs text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-900/50 p-3 rounded border border-slate-200 dark:border-slate-700">
                        Example: <code>&lt;script async src="..." crossorigin="anonymous"&gt;&lt;/script&gt;</code>
                    </p>
                    @error('auto_ads_code') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                </div>

                {{-- 2. Unit Ads Section --}}
                <div x-show="mode === 'unit'" x-cloak class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-2">
                            Google AdSense Unit Ads Client ID
                        </label>
                        {{-- @input event added to trigger dirty state --}}
                        <input type="text"
                               wire:model="unit_ads_client_id"
                               @input="isDirty = true"
                               placeholder="ca-pub-1234567890123456"
                               class="block w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-300 pl-3 text-sm py-2 border focus:ring-indigo-500">

                        <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                            Find this in AdSense: Ads → Unit Ads → Get code → <code>data-ad-client</code>.
                        </p>
                        @error('unit_ads_client_id') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                    </div>

                    {{-- Example Code Block (Non-editable) --}}
                    <div class="bg-slate-900 text-slate-300 p-4 rounded-lg text-xs font-mono border border-slate-700">
                        <div class="mb-2 font-bold text-slate-500 uppercase tracking-wider">Snippet Example:</div>
                        <pre class="overflow-x-auto">
&lt;script async src="https://pagead2.googlesyndication.com..."
    crossorigin="anonymous"&gt;&lt;/script&gt;
&lt;ins class="adsbygoogle"
    style="display:block"
    data-ad-client="<span class="text-green-400 font-bold">ca-pub-123456789</span>"
    data-ad-slot="123456789"
    data-ad-format="auto"
    data-full-width-responsive="true"&gt;&lt;/ins&gt;
&lt;script&gt;
    (adsbygoogle = window.adsbygoogle || []).push({});
&lt;/script&gt;
</pre>
                    </div>
                </div>

                <hr class="border-slate-200 dark:border-slate-700">

                {{-- 3. ads.txt Section --}}
                <div class="space-y-4">
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-file-alt text-blue-500 mt-1"></i>
                            <div class="text-sm text-blue-800 dark:text-blue-200">
                                <strong class="block mb-1">What is ads.txt?</strong>
                                <p class="text-xs sm:text-sm text-blue-700 dark:text-blue-300 leading-relaxed">
                                    Authorized Digital Sellers (ads.txt) is an IAB Tech Lab initiative that helps ensure your digital ad inventory is only sold through sellers you've authorized.
                                </p>
                                <div class="mt-3 bg-white dark:bg-slate-900 p-2 rounded border border-blue-100 dark:border-blue-800 font-mono text-xs text-slate-600 dark:text-slate-400">
                                    google.com, pub-0000000000000000, DIRECT, f08c47fec0942fa0
                                </div>
                                <p class="mt-2 text-xs">Once uploaded, your ads.txt will be accessible at: <a href="{{ url('ads.txt') }}" target="_blank" class="font-bold underline">{{ url('ads.txt') }}</a></p>
                            </div>
                        </div>
                    </div>

                    <div class="border border-dashed border-slate-300 dark:border-slate-700 rounded-lg p-4 bg-slate-50 dark:bg-slate-900/30">
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-2">Google AdSense ads.txt File</label>
                        <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                            <input type="file" wire:model="uploadedFile" accept=".txt" class="block text-sm text-slate-600 dark:text-slate-300
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-xs file:font-semibold
                                file:bg-blue-50 file:text-blue-700
                                hover:file:bg-blue-100
                                dark:file:bg-slate-700 dark:file:text-slate-200 cursor-pointer
                            ">
                            @if ($uploadedFile)
                                <span class="inline-flex items-center px-2 py-1 text-[11px] rounded bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300">
                                    <i class="fas fa-check mr-1"></i> {{ $uploadedFile->getClientOriginalName() }}
                                </span>
                            @endif
                        </div>

                        <div wire:loading wire:target="uploadedFile" class="mt-2 text-xs text-blue-600 font-bold flex items-center">
                            <i class="fas fa-spinner fa-spin mr-2"></i> Uploading...
                        </div>
                        @error('uploadedFile') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

            </div>

            {{-- Footer Action --}}
            <div class="bg-slate-50 dark:bg-slate-900/80 px-6 py-4 border-t border-slate-200 dark:border-slate-700 flex justify-between items-center sticky bottom-0 z-10">
                <div class="text-xs text-slate-500">
                    <span x-show="isDirty" class="text-blue-600 dark:text-blue-400 font-bold animate-pulse">
                        <i class="fas fa-circle text-[8px] mr-1"></i> Unsaved changes
                    </span>
                    <span x-show="!isDirty" class="text-emerald-500 font-bold">
                        <i class="fas fa-check-circle mr-1"></i> Settings saved
                    </span>
                </div>

                <button
                    wire:click="save"
                    wire:loading.attr="disabled"
                    :disabled="!isDirty"
                    :class="isDirty
                        ? 'bg-blue-600 hover:bg-blue-700 text-white shadow-md cursor-pointer'
                        : 'bg-slate-300 dark:bg-slate-700 text-slate-500 cursor-not-allowed shadow-none'"
                    class="px-6 py-2.5 rounded-lg text-sm font-bold transition-all active:scale-95 flex items-center gap-2 disabled:opacity-70"
                >
                    <span wire:loading><i class="fas fa-circle-notch fa-spin"></i> Saving...</span>

                    <span wire:loading.remove>
                        <i class="fas" :class="isDirty ? 'fa-save' : 'fa-check'"></i>
                        <span x-text="isDirty ? 'Save Settings' : 'Saved'"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
