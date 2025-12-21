{{-- resources/views/livewire/admin/categories/category-table.blade.php --}}
<div class="space-y-4">
    {{-- Breadcrumb --}}
    <div class="text-sm text-gray-600 dark:text-gray-300">
        <span class="text-gray-400 dark:text-gray-500">Dashboard</span> /
        <span class="text-gray-400 dark:text-gray-500">Blog</span> /
        <span class="font-semibold text-gray-800 dark:text-gray-100">Categories</span>
    </div>

    @if (session()->has('success'))
        <div class="bg-green-50 dark:bg-green-900/40 border border-green-200 dark:border-green-700 text-green-800 dark:text-green-200 px-4 py-2 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-900 rounded shadow-sm border border-gray-200 dark:border-gray-800">
        {{-- Top bar --}}
        <div class="px-4 py-3 flex items-center justify-between border-b border-gray-100 dark:border-gray-800">
            <div class="flex items-center space-x-2">
                <div x-data="{ open: false }" class="relative">
                    <button
                        type="button"
                        @click="open = !open"
                        class="text-xs px-3 py-1.5 border border-gray-200 dark:border-gray-700 rounded bg-gray-50 dark:bg-gray-800 dark:text-gray-100 flex items-center">
                        Bulk Actions <i class="fa-solid fa-caret-down ml-1"></i>
                    </button>

                    <div x-show="open" @click.outside="open = false"
                         class="absolute z-10 mt-1 w-48 rounded border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow">
                        <button type="button"
                                onclick="confirm('Delete selected categories?') || event.stopImmediatePropagation()"
                                wire:click="bulkDelete"
                                class="block w-full px-3 py-2 text-left text-xs text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
                            Delete selected
                        </button>
                        <button type="button"
                                wire:click="bulkPublish"
                                class="block w-full px-3 py-2 text-left text-xs text-green-700 dark:text-green-300 hover:bg-green-50 dark:hover:bg-green-900/20">
                            Mark as published
                        </button>
                        <button type="button"
                                wire:click="bulkDraft"
                                class="block w-full px-3 py-2 text-left text-xs text-yellow-700 dark:text-yellow-300 hover:bg-yellow-50 dark:hover:bg-yellow-900/20">
                            Mark as draft
                        </button>
                    </div>
                </div>
                <button
                    class="text-xs px-3 py-1.5 border border-gray-200 dark:border-gray-700 rounded bg-gray-50 dark:bg-gray-800 dark:text-gray-100">
                    <i class="fa-solid fa-sliders mr-1"></i> Filters
                </button>
                <div class="ml-2 relative">
                    <span class="absolute inset-y-0 left-2 flex items-center text-gray-400 dark:text-gray-600 text-xs">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="text"
                           wire:model.live.debounce.300ms="search"
                           placeholder="Search..."
                           class="text-sm border rounded pl-7 pr-3 py-1.5 bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div class="flex items-center space-x-2">
                <select
                    wire:model.live="perPage"
                    class="text-xs border border-gray-200 dark:border-gray-700 rounded bg-gray-50 dark:bg-gray-800 dark:text-gray-100 px-2 py-1.5">
                    <option value="10">10 / page</option>
                    <option value="25">25 / page</option>
                    <option value="50">50 / page</option>
                </select>
                <a href="{{ route('blogs.categories.create') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-3 py-1.5 rounded flex items-center space-x-1 shadow-sm">
                    <i class="fa-solid fa-plus"></i>
                    <span>Create</span>
                </a>
                <button
                    wire:click="refreshTable"
                    wire:loading.attr="disabled"
                    class="border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-xs px-3 py-1.5 rounded flex items-center space-x-1 text-gray-800 dark:text-gray-100 disabled:opacity-60">
                    <span class="inline-flex items-center space-x-1" wire:loading.remove>
                        <i class="fa-solid fa-rotate-right"></i>
                        <span>Reload</span>
                    </span>
                    <span class="inline-flex items-center space-x-1" wire:loading>
                        <i class="fa-solid fa-spinner fa-spin"></i>
                        <span>Refreshing</span>
                    </span>
                </button>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800/70 border-b border-gray-100 dark:border-gray-800">
                <tr>
                    <th class="w-12 px-4 py-2 text-center">
                        <input type="checkbox"
                               wire:click="toggleSelectAll"
                               {{ $selectAll ? 'checked' : '' }}
                               class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800">
                    </th>
                    <th class="px-2 py-2 text-left text-xs text-gray-500 dark:text-gray-400">
                        ID <i class="fa-solid fa-sort text-[10px] ml-1"></i>
                    </th>
                    <th class="px-2 py-2 text-left text-xs text-gray-500 dark:text-gray-400">
                        Name <i class="fa-solid fa-sort text-[10px] ml-1"></i>
                    </th>
                    <th class="px-2 py-2 text-left text-xs text-gray-500 dark:text-gray-400">
                        Parent Category <i class="fa-solid fa-sort text-[10px] ml-1"></i>
                    </th>
                    <th class="px-2 py-2 text-left text-xs text-gray-500 dark:text-gray-400">
                        Order <i class="fa-solid fa-sort text-[10px] ml-1"></i>
                    </th>
                    <th class="px-2 py-2 text-left text-xs text-gray-500 dark:text-gray-400">
                        Is Featured
                    </th>
                    <th class="px-2 py-2 text-left text-xs text-gray-500 dark:text-gray-400">
                        Is Default
                    </th>
                    <th class="px-2 py-2 text-left text-xs text-gray-500 dark:text-gray-400">
                        Created At
                    </th>
                    <th class="px-2 py-2 text-left text-xs text-gray-500 dark:text-gray-400">
                        Status
                    </th>
                    <th class="px-2 py-2 text-right text-xs text-gray-500 dark:text-gray-400">
                        Operations
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr wire:loading.delay>
                    <td colspan="10" class="px-4 py-6">
                        <div class="space-y-3 animate-pulse">
                            @for($i = 0; $i < 5; $i++)
                                <div class="grid grid-cols-10 gap-4 items-center">
                                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded col-span-1"></div>
                                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded col-span-1"></div>
                                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded col-span-2"></div>
                                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded col-span-2"></div>
                                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded col-span-1"></div>
                                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded col-span-1"></div>
                                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded col-span-1"></div>
                                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded col-span-1"></div>
                                </div>
                            @endfor
                        </div>
                    </td>
                </tr>
                @forelse($categories as $category)
                    <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50/60 dark:hover:bg-gray-800/60" wire:loading.remove>
                        <td class="w-12 px-4 py-2 text-center">
                            <input type="checkbox"
                                   wire:model="selected"
                                   value="{{ $category->id }}"
                                   class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800">
                        </td>

                        {{-- ID --}}
                        <td class="px-2 py-2 text-xs text-gray-500 dark:text-gray-400">
                            {{ $category->id }}
                        </td>

                        {{-- Name: child হলে আগে ↳ দেখাই --}}
                        <td class="px-2 py-2 text-gray-800 dark:text-gray-100">
                            @if($category->parent_id)
                                <span class="text-gray-400 dark:text-gray-600 mr-1">↳</span>
                            @endif
                            <a href="{{ route('blogs.categories.edit', $category->id) }}"
                               class="text-blue-600 dark:text-blue-400 hover:underline">
                                {{ $category->name }}
                            </a>
                        </td>

                        {{-- Parent column: chain --}}
                        <td class="px-2 py-2 text-gray-700 dark:text-gray-200">
                            {{ $category->parent_path ?: '—' }}
                        </td>

                        {{-- Order --}}
                        <td class="px-2 py-2 text-gray-700 dark:text-gray-200">
                            {{ $category->order }}
                        </td>

                        {{-- Is featured --}}
                        <td class="px-2 py-2">
                            @if($category->is_featured)
                                <span class="px-2 py-0.5 rounded-full bg-green-500 text-white text-xs shadow">
                                        Yes
                                    </span>
                            @else
                                <span class="px-2 py-0.5 rounded-full bg-red-500 text-white text-xs shadow">
                                        No
                                    </span>
                            @endif
                        </td>

                        {{-- Is default --}}
                        <td class="px-2 py-2">
                            @if($category->is_default)
                                <span class="px-2 py-0.5 rounded-full bg-green-500 text-white text-xs shadow">
                                        Yes
                                    </span>
                            @else
                                <span class="px-2 py-0.5 rounded-full bg-red-500 text-white text-xs shadow">
                                        No
                                    </span>
                            @endif
                        </td>

                        {{-- Created at --}}
                        <td class="px-2 py-2 text-gray-700 dark:text-gray-200">
                            {{ $category->created_at?->format('Y-m-d') }}
                        </td>

                        {{-- Status --}}
                        <td class="px-2 py-2">
                            @if($category->status === 'published')
                                <span class="px-2 py-0.5 rounded-full bg-green-500 text-white text-xs shadow">
                                        Published
                                    </span>
                            @else
                                <span class="px-2 py-0.5 rounded-full bg-gray-400 text-white text-xs shadow">
                                        Draft
                                    </span>
                            @endif
                        </td>

                        {{-- Operations --}}
                        <td class="px-2 py-2 text-right">
                            <a href="{{ route('blogs.categories.edit', $category->id) }}"
                               class="inline-flex items-center justify-center px-2 py-1 rounded bg-blue-500 hover:bg-blue-600 text-white text-xs mr-1 shadow"
                               title="Edit">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <button
                                wire:click="deleteCategory({{ $category->id }})"
                                onclick="return confirm('Delete this category?')"
                                class="inline-flex items-center justify-center px-2 py-1 rounded bg-red-500 hover:bg-red-600 text-white text-xs shadow"
                                title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-300">
                            No categories found.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination footer --}}
        <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
            <div>
                Show from {{ $categories->firstItem() ?? 0 }}
                to {{ $categories->lastItem() ?? 0 }}
                in {{ $categories->total() }} records
            </div>
            <div>
                {{ $categories->links() }}
            </div>
        </div>
    </div>
</div>
