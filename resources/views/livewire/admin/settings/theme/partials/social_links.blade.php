<div class="space-y-8">
    @foreach($social_links as $index => $link)
        <div class="relative p-6 border border-slate-200 dark:border-slate-700 rounded-md bg-white dark:bg-slate-800 transition-all">
            {{-- Remove Button (X) --}}
            <button type="button" wire:click="removeSocialLink({{ $index }})" class="absolute top-4 right-4 text-slate-400 hover:text-red-500 cursor-pointer transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>

            <div class="space-y-5">
                {{-- Name Field --}}
                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Name</label>
                    <input type="text" wire:model="social_links.{{ $index }}.name" placeholder="e.g. Facebook"
                           class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-900 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                </div>

                {{-- Icon Selection --}}
                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Icon</label>
                    <div class="relative">
                        <input type="text" wire:model="social_links.{{ $index }}.icon" placeholder="facebook"
                               class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-900 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                    </div>
                </div>

                {{-- URL Field --}}
                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-slate-700 dark:text-slate-300">URL</label>
                    <input type="text" wire:model="social_links.{{ $index }}.url" placeholder="https://..."
                           class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-900 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                </div>
                {{-- Colors Section --}}
                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Color</label>
                    <div class="">
                        <input type="color" wire:model="social_links.{{ $index }}.color" class="w-10 h-10 p-0 border border-slate-300 rounded cursor-pointer">
                    </div>
                </div>
                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Background color</label>
                    <div class="">
                        <input type="color" wire:model="social_links.{{ $index }}.bg_color" class="w-10 h-10 p-0 border border-slate-300 rounded cursor-pointer">
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    {{-- Add New Button --}}
    <div class="mt-4">
        <button type="button" wire:click="addSocialLink" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 outline-none transition ease-in-out duration-150">
            + Add New
        </button>
    </div>

    {{-- Save Button --}}
    <div class="mt-6 pt-6 border-t flex justify-end">
        <button type="button" wire:click="save" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
            Save Changes
        </button>
    </div>
</div>
