<div class="antialiased text-slate-900 dark:text-slate-100">
    <div class="max-w-5xl mx-auto">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="px-6 py-5 bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100 flex items-center gap-2">
                        <i class="fas fa-robot text-indigo-500"></i>
                        robots.txt
                    </h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Control crawler access and sitemap visibility for your site.</p>
                </div>
                <a href="{{ $robotsUrl }}" target="_blank" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1">
                    <i class="fas fa-external-link-alt"></i> {{ $robotsUrl }}
                </a>
            </div>

            <div class="p-6 space-y-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-2">Robots.txt Content</label>
                    <textarea
                        wire:model="robotsContent"
                        rows="10"
                        class="w-full rounded-lg border border-slate-300 p-2 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="User-agent: *&#10;Allow: /&#10;Sitemap: {{ $robotsUrl }}"
                    ></textarea>
                    @error('robotsContent')
                        <p class="mt-2 text-xs text-rose-500">{{ $message }}</p>
                    @enderror

                    <p class="mt-3 text-xs text-slate-500 dark:text-slate-400">
                        After saving, check your robots.txt here:
                        <a href="{{ $robotsUrl }}" target="_blank" class="font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">{{ $robotsUrl }}</a>
                    </p>
                </div>

                <div class="border border-dashed border-slate-300 dark:border-slate-700 rounded-lg p-4 bg-slate-50 dark:bg-slate-900/30">
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-2">Upload robots.txt file</label>
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                        <input type="file" wire:model="uploadedFile" accept=".txt" class="block text-sm text-slate-600 dark:text-slate-300">
                        @if ($uploadedFile)
                            <span class="inline-flex items-center px-2 py-1 text-[11px] rounded bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300">{{ $uploadedFile->getClientOriginalName() }}</span>
                        @endif
                    </div>
                    @error('uploadedFile')
                        <p class="mt-2 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Uploading a file will overwrite the text content above.</p>
                </div>
            </div>

            <div class="bg-slate-50 dark:bg-slate-900/80 px-6 py-4 border-t border-slate-200 dark:border-slate-700 flex justify-end">
                <button
                    wire:click="save"
                    wire:loading.attr="disabled"
                    wire:target="save"
                    class="bg-indigo-600 hover:bg-indigo-700 disabled:bg-indigo-400 disabled:cursor-not-allowed cursor-pointer text-white px-6 py-2.5 rounded-lg text-sm font-bold shadow-md transition-all active:scale-95 flex items-center"
                >
                    <span wire:loading.remove wire:target="save" class="flex items-center"><i class="fas fa-save mr-2"></i> Save Changes</span>
                    <span wire:loading wire:target="save" class="flex items-center"><i class="fas fa-circle-notch fa-spin mr-2"></i> Saving...</span>
                </button>
            </div>
        </div>
    </div>
</div>
