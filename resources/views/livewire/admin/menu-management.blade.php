<div class="">
    <div class="flex flex-col gap-6 xl:flex-row">
        {{-- Left column --}}
        <div class="w-full xl:w-1/3 space-y-4">
            {{-- Menus card --}}
            <div class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-sm">
                <div class="border-b border-slate-200 dark:border-slate-700 px-4 py-3">
                    <h5 class="mb-0 text-sm font-semibold text-slate-800 dark:text-slate-100">Menus</h5>
                </div>

                <div class="p-4 space-y-6">
                    @if(!empty($menus))
                        {{-- Select existing menu --}}
                        <div class="mb-3">
                            <label class="block mb-1 text-sm font-medium text-slate-700 dark:text-slate-200">
                                Select menu
                            </label>
                            <select
                                class="block w-full h-10 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                                wire:model.live="selectedMenuId"
                            >
                                @foreach($menus as $menu)
                                    <option value="{{ $menu['id'] }}">
                                        {{ $menu['name'] }} ({{ $menu['location'] }})
                                    </option>
                                @endforeach
                            </select>
                            @error('selectedMenuId')
                            <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif

                    {{-- Create new menu --}}
                    <form wire:submit.prevent="createMenu" class="border-t border-slate-200 dark:border-slate-700 pt-4">
                        <h6 class="mb-3 text-sm font-semibold text-slate-800 dark:text-slate-100">Create new menu</h6>

                        <div class="mb-3">
                            <label class="block mb-1 text-sm font-medium text-slate-700 dark:text-slate-200">
                                Menu name
                            </label>
                            <input
                                type="text"
                                class="block w-full h-10 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                                wire:model.defer="newMenuName"
                                placeholder="Primary navigation"
                            >
                            @error('newMenuName')
                            <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block mb-1 text-sm font-medium text-slate-700 dark:text-slate-200">
                                Location
                            </label>
                            <input
                                list="menu-locations"
                                class="block w-full h-10 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                                wire:model.defer="newMenuLocation"
                                placeholder="primary"
                            >
                            <datalist id="menu-locations">
                                @foreach($locationSuggestions as $locationKey => $label)
                                    <option value="{{ $locationKey }}">{{ $label }}</option>
                                @endforeach
                            </datalist>
                            <small class="mt-1 block text-xs text-slate-500 dark:text-slate-400">
                                Locations help you reuse menus in different parts of the site.
                            </small>
                            @error('newMenuLocation')
                            <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <button
                            type="submit"
                            class="inline-flex w-full items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >
                            Create menu
                        </button>
                    </form>

                    {{-- Edit selected menu --}}
                    @if($selectedMenu)
                        <form wire:submit.prevent="updateMenu" class="border-t border-slate-200 dark:border-slate-700 pt-4">
                            <h6 class="mb-3 text-sm font-semibold text-slate-800 dark:text-slate-100">Menu settings</h6>

                            <div class="mb-3">
                                <label class="block mb-1 text-sm font-medium text-slate-700 dark:text-slate-200">
                                    Menu name
                                </label>
                                <input
                                    type="text"
                                    class="block w-full h-10 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                                    wire:model.defer="editMenuName"
                                >
                                @error('editMenuName')
                                <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="block mb-1 text-sm font-medium text-slate-700 dark:text-slate-200">
                                    Location
                                </label>
                                <input
                                    list="menu-locations"
                                    class="block w-full h-10 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                                    wire:model.defer="editMenuLocation"
                                >
                                @error('editMenuLocation')
                                <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="flex flex-wrap items-center gap-2">
                                <button
                                    type="submit"
                                    class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                >
                                    Save changes
                                </button>

                                <button
                                    type="button"
                                    class="inline-flex items-center rounded-lg border border-red-500 px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-50 dark:hover:bg-red-950/30 focus:outline-none focus:ring-2 focus:ring-red-500"
                                    wire:click="deleteMenu({{ $selectedMenuId }})"
                                    onclick="confirm('This will delete the menu and all of its items. Continue?') || event.stopImmediatePropagation()"
                                >
                                    Delete menu
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Add menu items card --}}
            <div class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-sm">
                <div class="border-b border-slate-200 dark:border-slate-700 px-4 py-3">
                    <h5 class="mb-0 text-sm font-semibold text-slate-800 dark:text-slate-100">Add menu items</h5>
                </div>
                <div class="p-4">
                    @if(! $selectedMenu)
                        <p class="mb-0 text-sm text-slate-500 dark:text-slate-400">
                            Create a menu first to start adding links.
                        </p>
                    @else
                        {{-- Tabs --}}
                        <div class="border-b border-slate-200 dark:border-slate-700">
                            <nav class="-mb-px flex gap-4">
                                <button
                                    type="button"
                                    class="whitespace-nowrap border-b-2 px-3 py-2 text-sm font-medium
                                        {{ $activeTab === 'custom-link'
                                            ? 'border-indigo-600 text-indigo-600'
                                            : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:hover:text-slate-300' }}"
                                    wire:click.prevent="$set('activeTab', 'custom-link')"
                                >
                                    Custom link
                                </button>
                                <button
                                    type="button"
                                    class="whitespace-nowrap border-b-2 px-3 py-2 text-sm font-medium
                                        {{ $activeTab === 'categories'
                                            ? 'border-indigo-600 text-indigo-600'
                                            : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:hover:text-slate-300' }}"
                                    wire:click.prevent="$set('activeTab', 'categories')"
                                >
                                    Categories
                                </button>
                                <button
                                    type="button"
                                    class="whitespace-nowrap border-b-2 px-3 py-2 text-sm font-medium
                                        {{ $activeTab === 'posts'
                                            ? 'border-indigo-600 text-indigo-600'
                                            : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:hover:text-slate-300' }}"
                                    wire:click.prevent="$set('activeTab', 'posts')"
                                >
                                    Posts
                                </button>
                                <button
                                    type="button"
                                    class="whitespace-nowrap border-b-2 px-3 py-2 text-sm font-medium
                                        {{ $activeTab === 'tags'
                                            ? 'border-indigo-600 text-indigo-600'
                                            : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:hover:text-slate-300' }}"
                                    wire:click.prevent="$set('activeTab', 'tags')"
                                >
                                    Tags
                                </button>
                            </nav>
                        </div>

                        {{-- Tab panels --}}
                        <div class="pt-4">
                            {{-- Custom link tab --}}
                            <div class="{{ $activeTab === 'custom-link' ? 'block' : 'hidden' }}">
                                <form wire:submit.prevent="addCustomLink" class="space-y-4 p-2">
                                    <div>
                                        <label class="block mb-1 text-sm font-medium text-slate-700 dark:text-slate-200">
                                            Navigation label
                                        </label>
                                        <input
                                            type="text"
                                            class="block w-full h-10 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                                            wire:model.defer="customTitle"
                                        >
                                        @error('customTitle')
                                        <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block mb-1 text-sm font-medium text-slate-700 dark:text-slate-200">
                                            URL
                                        </label>
                                        <input
                                            type="text"
                                            class="block w-full h-10 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                                            wire:model.defer="customUrl"
                                            placeholder="/relative or https://absolute"
                                        >
                                        @error('customUrl')
                                        <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block mb-1 text-sm font-medium text-slate-700 dark:text-slate-200">
                                            Open link in
                                        </label>
                                        <select
                                            class="block w-full h-10 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 text-sm text-slate-900 dark:text-slate-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                                            wire:model.defer="customTarget"
                                        >
                                            @foreach($availableTargets as $value => $label)
                                                <option value="{{ $value }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @error('customTarget')
                                        <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <button
                                        type="submit"
                                        class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    >
                                        Add to menu
                                    </button>
                                </form>
                            </div>

                            {{-- Categories tab --}}
                            <div class="{{ $activeTab === 'categories' ? 'block' : 'hidden' }}">
                                <form wire:submit.prevent="addCategoriesToMenu" class="space-y-3">
                                    <div>
                                        <label class="block mb-1 text-sm font-medium text-slate-700 dark:text-slate-200">
                                            Search categories
                                        </label>
                                        <input
                                            type="text"
                                            class="block w-full h-10 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                                            placeholder="Type to filter..."
                                            wire:model.live.debounce.500ms="categorySearch"
                                        >
                                    </div>

                                    <div class="menu-picker max-h-52 overflow-y-auto rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 px-3 py-3">
                                        @forelse($this->categoryOptions as $category)
                                            <label
                                                class="mb-2 flex items-center gap-2 text-sm text-slate-700 dark:text-slate-200"
                                                wire:key="category-option-{{ $category->id }}"
                                            >
                                                <input
                                                    class="h-4 w-4 rounded border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-indigo-600 focus:ring-indigo-500"
                                                    type="checkbox"
                                                    value="{{ $category->id }}"
                                                    id="category-{{ $category->id }}"
                                                    wire:model.live="selectedCategories"
                                                >
                                                <span>{{ $category->name }}</span>
                                            </label>
                                        @empty
                                            <p class="mb-0 text-xs text-slate-500 dark:text-slate-400">No categories found.</p>
                                        @endforelse
                                    </div>

                                    @error('selectedCategories')
                                    <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                                    @enderror

                                    <button
                                        type="submit"
                                        class="mt-1 inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:cursor-not-allowed disabled:opacity-60"
                                        wire:loading.attr="disabled"
                                        @disabled(empty($selectedCategories))
                                    >
                                        Add to menu
                                    </button>
                                </form>
                            </div>

                            {{-- Posts tab --}}
                            <div class="{{ $activeTab === 'posts' ? 'block' : 'hidden' }}">
                                <form wire:submit.prevent="addPostsToMenu" class="space-y-3">
                                    <div>
                                        <label class="block mb-1 text-sm font-medium text-slate-700 dark:text-slate-200">
                                            Search posts
                                        </label>
                                        <input
                                            type="text"
                                            class="block w-full h-10 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                                            placeholder="Type to filter..."
                                            wire:model.live.debounce.500ms="postSearch"
                                        >
                                    </div>

                                    <div class="menu-picker max-h-52 overflow-y-auto rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 px-3 py-3">
                                        @forelse($this->postOptions as $post)
                                            <label
                                                class="mb-2 flex items-center gap-2 text-sm text-slate-700 dark:text-slate-200"
                                                wire:key="post-option-{{ $post->id }}"
                                            >
                                                <input
                                                    class="h-4 w-4 rounded border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-indigo-600 focus:ring-indigo-500"
                                                    type="checkbox"
                                                    value="{{ $post->id }}"
                                                    id="post-{{ $post->id }}"
                                                    wire:model.live="selectedPosts"
                                                >
                                                <span>{{ $post->name }}</span>
                                            </label>
                                        @empty
                                            <p class="mb-0 text-xs text-slate-500 dark:text-slate-400">No posts found.</p>
                                        @endforelse
                                    </div>

                                    @error('selectedPosts')
                                    <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                                    @enderror

                                    <button
                                        type="submit"
                                        class="mt-1 inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:cursor-not-allowed disabled:opacity-60"
                                        wire:loading.attr="disabled"
                                        @disabled(empty($selectedPosts))
                                    >
                                        Add to menu
                                    </button>
                                </form>
                            </div>

                            {{-- Tags tab --}}
                            <div class="{{ $activeTab === 'tags' ? 'block' : 'hidden' }}">
                                <form wire:submit.prevent="addTagsToMenu" class="space-y-3">
                                    <div>
                                        <label class="block mb-1 text-sm font-medium text-slate-700 dark:text-slate-200">
                                            Search tags
                                        </label>
                                        <input
                                            type="text"
                                            class="block w-full h-10 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                                            placeholder="Type to filter..."
                                            wire:model.live.debounce.500ms="tagSearch"
                                        >
                                    </div>

                                    <div class="menu-picker max-h-52 overflow-y-auto rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 px-3 py-3">
                                        @forelse($this->tagOptions as $tag)
                                            <label
                                                class="mb-2 flex items-center gap-2 text-sm text-slate-700 dark:text-slate-200"
                                                wire:key="tag-option-{{ $tag->id }}"
                                            >
                                                <input
                                                    class="h-4 w-4 rounded border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-indigo-600 focus:ring-indigo-500"
                                                    type="checkbox"
                                                    value="{{ $tag->id }}"
                                                    id="tag-{{ $tag->id }}"
                                                    wire:model.live="selectedTags"
                                                >
                                                <span>{{ $tag->name }}</span>
                                            </label>
                                        @empty
                                            <p class="mb-0 text-xs text-slate-500 dark:text-slate-400">No tags found.</p>
                                        @endforelse
                                    </div>

                                    @error('selectedTags')
                                    <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                                    @enderror

                                    <button
                                        type="submit"
                                        class="mt-1 inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:cursor-not-allowed disabled:opacity-60"
                                        wire:loading.attr="disabled"
                                        @disabled(empty($selectedTags))
                                    >
                                        Add to menu
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right column --}}
        <div class="w-full xl:w-2/3">
            <div class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-200 dark:border-slate-700 px-4 py-3">
                    <div>
                        <h5 class="mb-0 text-sm font-semibold text-slate-800 dark:text-slate-100">Menu structure</h5>
                        <small class="text-xs text-slate-500 dark:text-slate-400">
                            Drag and drop items to reorder. Nest items to create dropdowns.
                        </small>
                    </div>
                    @if($selectedMenu)
                        <span class="inline-flex items-center rounded-full bg-slate-100 dark:bg-slate-800 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-slate-600 dark:text-slate-200">
                            {{ $selectedMenu['location'] }}
                        </span>
                    @endif
                </div>

                <div class="p-4">
                    <div wire:key="menu-structure-{{ $selectedMenuId ?? 'none' }}">
                        @if(session()->has('success'))
                            <div class="mb-3 rounded-lg border border-emerald-200 dark:border-emerald-600 bg-emerald-50 dark:bg-emerald-900/30 px-4 py-2 text-sm text-emerald-700 dark:text-emerald-200">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(! $selectedMenu)
                            <p class="mb-0 text-sm text-slate-500 dark:text-slate-400">
                                Create a menu to start organising links.
                            </p>
                        @elseif(empty($selectedMenu['items']))
                            <p class="mb-0 text-sm text-slate-500 dark:text-slate-400">
                                This menu does not have any items yet.
                            </p>
                        @else
                            <div id="menuNestable" data-menu-structure class="dd">
                                @include('livewire.admin.partials.menu-items', [
                                    'items' => $selectedMenu['items'],
                                    'editingItemId' => $editingItemId,
                                    'availableTargets' => $availableTargets
                                ])
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
