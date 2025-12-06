{{-- resources/views/livewire/admin/categories/category-table.blade.php --}}
<div class="space-y-4">
    {{-- Breadcrumb --}}
    <div class="text-sm text-gray-600">
        <span class="text-gray-400">Dashboard</span> /
        <span class="text-gray-400">Blog</span> /
        <span class="font-semibold text-gray-800">Categories</span>
    </div>

    @if (session()->has('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-2 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded shadow-sm border border-gray-200">
        {{-- Top bar --}}
        <div class="px-4 py-3 flex items-center justify-between border-b border-gray-100">
            <div class="flex items-center space-x-2">
                <button
                    class="text-xs px-3 py-1.5 border border-gray-200 rounded bg-gray-50">
                    Bulk Actions <i class="fa-solid fa-caret-down ml-1"></i>
                </button>
                <button
                    class="text-xs px-3 py-1.5 border border-gray-200 rounded bg-gray-50">
                    <i class="fa-solid fa-sliders mr-1"></i> Filters
                </button>
                <div class="ml-2 relative">
                    <span class="absolute inset-y-0 left-2 flex items-center text-gray-400 text-xs">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="text"
                           wire:model.debounce.400ms="search"
                           placeholder="Search..."
                           class="text-sm border rounded pl-7 pr-3 py-1.5">
                </div>
            </div>

            <div class="flex items-center space-x-2">
                <a href="{{ route('blogs.categories.create') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-3 py-1.5 rounded flex items-center space-x-1">
                    <i class="fa-solid fa-plus"></i>
                    <span>Create</span>
                </a>
                <button
                    wire:click="$refresh"
                    class="border border-gray-200 bg-gray-50 text-xs px-3 py-1.5 rounded flex items-center space-x-1">
                    <i class="fa-solid fa-rotate-right"></i>
                    <span>Reload</span>
                </button>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-4 py-2">
                        <input type="checkbox">
                    </th>
                    <th class="px-2 py-2 text-left text-xs text-gray-500">
                        ID <i class="fa-solid fa-sort text-[10px] ml-1"></i>
                    </th>
                    <th class="px-2 py-2 text-left text-xs text-gray-500">
                        Name <i class="fa-solid fa-sort text-[10px] ml-1"></i>
                    </th>
                    <th class="px-2 py-2 text-left text-xs text-gray-500">
                        Parent Category <i class="fa-solid fa-sort text-[10px] ml-1"></i>
                    </th>
                    <th class="px-2 py-2 text-left text-xs text-gray-500">
                        Order <i class="fa-solid fa-sort text-[10px] ml-1"></i>
                    </th>
                    <th class="px-2 py-2 text-left text-xs text-gray-500">
                        Is Featured
                    </th>
                    <th class="px-2 py-2 text-left text-xs text-gray-500">
                        Is Default
                    </th>
                    <th class="px-2 py-2 text-left text-xs text-gray-500">
                        Created At
                    </th>
                    <th class="px-2 py-2 text-left text-xs text-gray-500">
                        Status
                    </th>
                    <th class="px-2 py-2 text-right text-xs text-gray-500">
                        Operations
                    </th>
                </tr>
                </thead>
                <tbody>
                @forelse($categories as $category)
                    <tr class="border-b border-gray-100 hover:bg-gray-50/60">
                        <td class="px-4 py-2">
                            <input type="checkbox">
                        </td>

                        {{-- ID --}}
                        <td class="px-2 py-2 text-xs text-gray-500">
                            {{ $category->id }}
                        </td>

                        {{-- Name: child হলে আগে ↳ দেখাই --}}
                        <td class="px-2 py-2">
                            @if($category->parent_id)
                                <span class="text-gray-400 mr-1">↳</span>
                            @endif
                            <a href="{{ route('blogs.categories.edit', $category->id) }}"
                               class="text-blue-600 hover:underline">
                                {{ $category->name }}
                            </a>
                        </td>

                        {{-- Parent column: chain --}}
                        <td class="px-2 py-2 text-gray-700">
                            {{ $category->parent_path ?: '—' }}
                        </td>

                        {{-- Order --}}
                        <td class="px-2 py-2 text-gray-700">
                            {{ $category->order }}
                        </td>

                        {{-- Is featured --}}
                        <td class="px-2 py-2">
                            @if($category->is_featured)
                                <span class="px-2 py-0.5 rounded-full bg-green-500 text-white text-xs">
                                        Yes
                                    </span>
                            @else
                                <span class="px-2 py-0.5 rounded-full bg-red-500 text-white text-xs">
                                        No
                                    </span>
                            @endif
                        </td>

                        {{-- Is default --}}
                        <td class="px-2 py-2">
                            @if($category->is_default)
                                <span class="px-2 py-0.5 rounded-full bg-green-500 text-white text-xs">
                                        Yes
                                    </span>
                            @else
                                <span class="px-2 py-0.5 rounded-full bg-red-500 text-white text-xs">
                                        No
                                    </span>
                            @endif
                        </td>

                        {{-- Created at --}}
                        <td class="px-2 py-2 text-gray-700">
                            {{ $category->created_at?->format('Y-m-d') }}
                        </td>

                        {{-- Status --}}
                        <td class="px-2 py-2">
                            @if($category->status === 'published')
                                <span class="px-2 py-0.5 rounded-full bg-green-500 text-white text-xs">
                                        Published
                                    </span>
                            @else
                                <span class="px-2 py-0.5 rounded-full bg-gray-400 text-white text-xs">
                                        Draft
                                    </span>
                            @endif
                        </td>

                        {{-- Operations --}}
                        <td class="px-2 py-2 text-right">
                            <a href="{{ route('blogs.categories.edit', $category->id) }}"
                               class="inline-flex items-center justify-center px-2 py-1 rounded bg-blue-500 text-white text-xs mr-1"
                               title="Edit">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <button
                                wire:click="deleteCategory({{ $category->id }})"
                                onclick="return confirm('Delete this category?')"
                                class="inline-flex items-center justify-center px-2 py-1 rounded bg-red-500 text-white text-xs"
                                title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="px-4 py-6 text-center text-sm text-gray-500">
                            No categories found.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination footer --}}
        <div class="px-4 py-3 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
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
