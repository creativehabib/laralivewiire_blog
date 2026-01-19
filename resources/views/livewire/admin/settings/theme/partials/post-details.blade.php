{{-- পোস্ট সেটিংস ট্যাব --}}
@if($activeMenu === 'post')
    <form wire:submit.prevent="savePost" class="space-y-6 animate-fade-in">

        <div class="bg-white dark:bg-slate-800 p-6 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700">
            <h3 class="text-base font-semibold text-slate-800 dark:text-slate-200 mb-4 flex items-center gap-2">
                <i class="fas fa-layer-group text-indigo-500"></i> {{ __('Layout & Visibility') }}
            </h3>

            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">{{ __('Sidebar Position') }}</label>
                    <div class="relative">
                        <select wire:model.defer="post.sidebar_position" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-indigo-500 appearance-none">
                            <option value="right">{{ __('Right Sidebar') }}</option>
                            <option value="left">{{ __('Left Sidebar') }}</option>
                            <option value="none">{{ __('No Sidebar (Full Width)') }}</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-slate-500">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="flex items-center justify-between border p-3 rounded-md border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-image text-slate-400 text-lg"></i>
                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Featured Image') }}</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="post.show_featured_image" class="sr-only peer">
                            <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between border p-3 rounded-md border-slate-200 dark:border-slate-700">
                        <div class="flex items-center gap-2">
                            <i class="far fa-calendar-alt text-slate-400 text-lg"></i>
                            <span class="text-sm text-slate-700 dark:text-slate-300">{{ __('Show Date') }}</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="post.show_date" class="sr-only peer">
                            <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between border p-3 rounded-md border-slate-200 dark:border-slate-700">
                        <div class="flex items-center gap-2">
                            <i class="far fa-eye text-slate-400 text-lg"></i>
                            <span class="text-sm text-slate-700 dark:text-slate-300">{{ __('Show Views') }}</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="post.show_views" class="sr-only peer">
                            <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 p-6 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700">
            <h3 class="text-base font-semibold text-slate-800 dark:text-slate-200 mb-4 flex items-center gap-2">
                <i class="fas fa-share-alt text-indigo-500"></i> {{ __('Social Share Buttons') }}
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($this->socialPlatforms as $key => $platform)
                    <label class="cursor-pointer flex items-center gap-3 p-3 rounded-md border border-slate-200 dark:border-slate-700 hover:border-indigo-500 hover:bg-slate-50 dark:hover:bg-slate-900 transition-all group select-none">
                        <input type="checkbox"
                               wire:model="post.share_{{ $key }}"
                               class="h-4 w-4 rounded border-slate-300 focus:ring-indigo-500">

                        <i class="{{ $platform['icon'] }} {{ $platform['color'] }} text-lg group-hover:scale-110 transition-transform"></i>

                        <span class="text-sm text-slate-700 dark:text-slate-300 font-medium">
                            {{ __($platform['label']) }}
                        </span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 p-6 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700">
            <h3 class="text-base font-semibold text-slate-800 dark:text-slate-200 mb-4 flex items-center gap-2">
                <i class="fas fa-comments text-indigo-500"></i> {{ __('Engagement') }}
            </h3>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-900 rounded-md border border-slate-100 dark:border-slate-600">
                    <div class="flex gap-3">
                        <div class="text-indigo-500 mt-0.5">
                            <i class="fas fa-user-pen text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Author Box') }}</p>
                            <p class="text-xs text-slate-500">{{ __('Show author bio below posts.') }}</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model="post.author_box_enabled" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                    </label>
                </div>

                <div class="p-4 bg-slate-50 dark:bg-slate-900 rounded-md border border-slate-100 dark:border-slate-600">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex gap-3">
                            <div class="text-indigo-500 mt-0.5">
                                <i class="far fa-newspaper text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Related News') }}</p>
                                <p class="text-xs text-slate-500">{{ __('Show related posts section.') }}</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="post.related_news_enabled" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>

                    @if($post['related_news_enabled'])
                        <div class="animate-fade-in-down border-t border-slate-200 dark:border-slate-700 pt-3">
                            <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-2 uppercase">{{ __('Number of Posts') }}</label>
                            <div class="relative">
                                <input type="number" min="1" max="12" wire:model.defer="post.related_news_count" class="w-full pl-3 pr-12 py-2 border border-slate-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-800 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                                <span class="absolute right-3 top-2 text-slate-400 text-xs font-bold tracking-wider py-1">POSTS</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="pt-4 flex justify-end sticky bottom-0 z-10 bg-gray-50/90 dark:bg-slate-900/90 backdrop-blur-sm py-4 border-t border-slate-200 dark:border-slate-700 -mx-6 px-6 -mb-6 rounded-b-lg">
            <button type="submit"
                    wire:loading.attr="disabled"
                    wire:target="savePost"
                    class="px-8 py-2.5 bg-indigo-600 hover:bg-indigo-700 disabled:bg-indigo-400 text-white font-medium cursor-pointer rounded-lg shadow-lg shadow-indigo-500/30 transition-all inline-flex items-center justify-center gap-2 transform active:scale-95 min-w-[160px]">

                    <span wire:loading.remove wire:target="savePost" class="inline-flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        <span>{{ __('Save Changes') }}</span>
                    </span>

                    <span wire:loading wire:target="savePost" class="inline-flex items-center gap-2">
                        <i class="fas fa-circle-notch fa-spin"></i>
                        <span>{{ __('Saving...') }}</span>
                    </span>

            </button>
        </div>
    </form>
@endif
