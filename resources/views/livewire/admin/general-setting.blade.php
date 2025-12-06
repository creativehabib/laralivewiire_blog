<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- বাঁ দিক: GENERAL SETTINGS --}}
    <div class="lg:col-span-2">
        <div class="rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm p-5 bg-white dark:bg-slate-900">
            <h6 class="text-xs font-semibold uppercase tracking-wide text-slate-700 dark:text-slate-300">
                GENERAL SETTINGS
            </h6>
            <hr class="my-3 border-slate-200 dark:border-slate-700">

            <form wire:submit.prevent="updateSiteInfo" class="space-y-6">
                {{-- 1st row --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-slate-300">
                            Site title
                        </label>
                        <input
                            type="text"
                            wire:model.defer="site_title"
                            placeholder="Enter site title"
                            class="block w-full h-10 rounded-lg border border-slate-200 dark:border-slate-700
                                   bg-white dark:bg-slate-900
                                   px-3 text-sm text-gray-900 dark:text-gray-100
                                   placeholder-gray-400 dark:placeholder-gray-500
                                   focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 focus:outline-none"
                        >
                        @error('site_title')
                        <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-slate-300">
                            Site email
                        </label>
                        <input
                            type="email"
                            wire:model.defer="site_email"
                            placeholder="Enter site email"
                            class="block w-full h-10 rounded-lg border border-slate-200 dark:border-slate-700
                                   bg-white dark:bg-slate-900
                                   px-3 text-sm text-gray-900 dark:text-gray-100
                                   placeholder-gray-400 dark:placeholder-gray-500
                                   focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 focus:outline-none"
                        >
                        @error('site_email')
                        <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- description --}}
                <div>
                    <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-slate-300">
                        Site description <span class="text-xs text-gray-400 dark:text-gray-500">(Optional)</span>
                    </label>
                    <textarea
                        rows="3"
                        wire:model.defer="site_description"
                        placeholder="Write a short description about your site..."
                        class="block w-full rounded-lg border border-slate-200 dark:border-slate-700
                               bg-white dark:bg-slate-900
                               px-3 py-2 text-sm text-gray-900 dark:text-gray-100
                               placeholder-gray-400 dark:placeholder-gray-500
                               focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 focus:outline-none"
                    ></textarea>
                    @error('site_description')
                    <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                {{-- 2nd row --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-slate-300">
                            Site phone number
                        </label>
                        <input
                            type="text"
                            wire:model.defer="site_phone"
                            placeholder="Enter site contact phone"
                            class="block w-full h-10 rounded-lg border border-slate-200 dark:border-slate-700
                                   bg-white dark:bg-slate-900
                                   px-3 text-sm text-gray-900 dark:text-gray-100
                                   placeholder-gray-400 dark:placeholder-gray-500
                                   focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 focus:outline-none"
                        >
                        @error('site_phone')
                        <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-slate-300">
                            Site Meta keywords <span class="text-xs text-gray-400 dark:text-gray-500">(Optional)</span>
                        </label>
                        <input
                            type="text"
                            wire:model.defer="site_meta_keywords"
                            placeholder="Eg: ecommerce, free api, laravel"
                            class="block w-full h-10 rounded-lg border border-slate-200 dark:border-slate-700
                                   bg-white dark:bg-slate-900
                                   px-3 text-sm text-gray-900 dark:text-gray-100
                                   placeholder-gray-400 dark:placeholder-gray-500
                                   focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 focus:outline-none"
                        >
                        @error('site_meta_keywords')
                        <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- meta description --}}
                <div>
                    <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-slate-300">
                        Site Meta Description <span class="text-xs text-gray-400 dark:text-gray-500">(Optional)</span>
                    </label>
                    <textarea
                        rows="4"
                        wire:model.defer="site_meta_description"
                        placeholder="Type site meta description..."
                        class="block w-full rounded-lg border border-slate-200 dark:border-slate-700
                               bg-white dark:bg-slate-900
                               px-3 py-2 text-sm text-gray-900 dark:text-gray-100
                               placeholder-gray-400 dark:placeholder-gray-500
                               focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 focus:outline-none"
                    ></textarea>
                    @error('site_meta_description')
                    <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                {{-- copyright --}}
                <div>
                    <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-slate-300">
                        Site Copyright text <span class="text-xs text-gray-400 dark:text-gray-500">(Optional)</span>
                    </label>
                    <input
                        type="text"
                        wire:model.defer="site_copyright"
                        placeholder="Eg: © {{ date('Y') }} LaraBlog. All rights reserved."
                        class="block w-full h-10 rounded-lg border border-slate-200 dark:border-slate-700
                               bg-white dark:bg-slate-900
                               px-3 text-sm text-gray-900 dark:text-gray-100
                               placeholder-gray-400 dark:placeholder-gray-500
                               focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/50 focus:outline-none"
                    >
                    @error('site_copyright')
                    <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <button
                        type="submit"
                        class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1 focus:ring-offset-gray-50 dark:focus:ring-offset-gray-900 disabled:opacity-60 disabled:cursor-not-allowed cursor-pointer"
                        wire:loading.attr="disabled"
                        wire:target="updateSiteInfo"
                    >
                        <span wire:loading.remove wire:target="updateSiteInfo">
                            Save changes
                        </span>
                        <span wire:loading wire:target="updateSiteInfo">
                            Saving...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ডান কলাম: LOGO & FAVICON --}}
    <div class="lg:col-span-1">
        <div class="rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-5 bg-white dark:bg-slate-900">
            <h6 class="text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-300">
                LOGO & FAVICON
            </h6>

            <hr class="my-3 border-gray-200 dark:border-gray-700">

            <form wire:submit.prevent="updateBranding" class="space-y-6">
                <div class="space-y-6">
                    {{-- SITE LOGO --}}
                    @include('mediamanager::includes.media-input', [
                        'name'  => 'site_logo',
                        'id'    => 'site_logo',
                        'label' => 'Site Logo',
                        'value' => $site_logo,
                    ])

                    {{-- SITE FAVICON --}}
                    @include('mediamanager::includes.media-input', [
                        'name'  => 'site_favicon',
                        'id'    => 'site_favicon',
                        'label' => 'Site Favicon',
                        'value' => $site_favicon,
                    ])
                </div>

                <button
                    type="submit"
                    class="inline-flex items-center rounded-lg bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md cursor-pointer hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1 focus:ring-offset-gray-50 dark:focus:ring-offset-gray-900 disabled:opacity-60 disabled:cursor-not-allowed transition duration-150"
                    wire:loading.attr="disabled"
                    wire:target="updateBranding"
                >
                    <span wire:loading.remove wire:target="updateBranding">
                        Update branding
                    </span>
                    <span wire:loading wire:target="updateBranding">
                        Updating...
                    </span>
                </button>
            </form>
        </div>
    </div>
</div>
