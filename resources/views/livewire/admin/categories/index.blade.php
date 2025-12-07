<div class="space-y-4">
    {{-- Breadcrumb --}}
    <div class="text-sm text-gray-600 dark:text-gray-300">
        <span class="text-gray-400 dark:text-gray-500">Dashboard</span> /
        <span class="text-gray-400 dark:text-gray-500">Blog</span> /
        <span class="font-semibold text-gray-800 dark:text-gray-100">Categories</span>
    </div>

    {{-- Flash message --}}
    @if (session()->has('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-2 rounded dark:bg-green-900/40 dark:border-green-800 dark:text-green-200">
            {{ session('success') }}
        </div>
    @endif

    {{-- VIEW SWITCH --}}
    <div class="flex justify-between items-center">
        <div class="bg-blue-50 border border-blue-100 text-blue-800 px-4 py-3 rounded text-sm dark:bg-blue-900/40 dark:border-blue-800 dark:text-blue-100">
            For easier bulk management of categories, you can also
            <a href="{{ route('blogs.categories.table', ['as' => 'table']) }}" class="underline">
                manage categories as a table
            </a>.
        </div>

        <div class="flex items-center space-x-2">
            <input type="text"
                   wire:model.debounce.400ms="search"
                   placeholder="Search..."
                   class="text-sm border rounded px-3 py-1.5 bg-white text-gray-800 border-gray-300 focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700 dark:focus:border-blue-400">
        </div>
    </div>

    {{-- ====== TREE + FORM VIEW ====== --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {{-- LEFT: Category tree (textual drag info, order change form থেকেই হবে) --}}
        <div class="lg:col-span-4">
            <div class="bg-white rounded shadow-sm border border-gray-200 dark:bg-gray-900 dark:border-gray-800">
                <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between dark:border-gray-800">
                    <div class="text-sm font-semibold text-gray-800 dark:text-gray-100">
                        Drag and drop to change the order or parent of the categories.
                    </div>
                </div>

                <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between dark:border-gray-800">
                    <button
                        wire:click="createRootCategory"
                        class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-3 py-1.5 rounded flex items-center space-x-1">
                        <span>+ Create</span>
                    </button>
                </div>

                <div class="p-4 max-h-[600px] overflow-y-auto text-sm text-gray-800 dark:text-gray-100">
                    @php
                        $renderNode = function ($cat, $level = 0) use (&$renderNode) {
                             echo '<li class="group mt-2" data-id="'.$cat->id.'">';

                            echo    '<div class="flex items-center justify-between bg-gray-50 border border-gray-200 rounded px-2 py-2 dark:bg-gray-800 dark:border-gray-700">';
                            echo        '<div class="flex items-center space-x-2">';
                            echo            '<span class="js-handle cursor-move text-gray-400 dark:text-gray-500"><i class="fa-solid fa-bars"></i></span>';
                            echo            '<button type="button"
                                                        wire:click="selectCategory('.$cat->id.')"
                                                        class="flex items-center space-x-2 text-left text-gray-800 group-hover:text-blue-600 cursor-pointer dark:text-gray-100">';

                            echo                '<i class="fa-regular fa-folder"></i>';

                            $count = $cat->posts_count ?? 0;
                            echo                '<span>'.e($cat->name).' ('.$count.')</span>';

                            echo            '</button>';
                            echo        '</div>';

                            echo        '<button type="button"
                                                   wire:click="createChildCategory('.$cat->id.')"
                                                   class="text-xs text-blue-500 opacity-0 group-hover:opacity-100">';
                            echo            '+ child';
                            echo        '</button>';
                            echo    '</div>';

                            // সব সময় child UL, যেন এখানে drag করলে child হয়
                            $ulClasses = 'js-category-list space-y-2';
                            if ($level >= 0) {
                                $ulClasses .= ' ml-5';          // 👈 child গুলো visually ইনডেন্ট হবে
                            }

                            echo    '<ul class="'.$ulClasses.'">';

                            foreach ($cat->childrenRecursive as $child) {
                                $renderNode($child, $level + 1);
                            }

                            echo    '</ul>';

                            echo '</li>';
                        };
                    @endphp

                    @if($this->rootCategories->count())
                        <ul class="js-category-list js-category-root space-y-2">
                            @foreach($this->rootCategories as $cat)
                                {!! $renderNode($cat) !!}
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-400 text-sm dark:text-gray-500">No categories yet.</p>
                    @endif
                </div>
            </div>
        </div>


        {{-- RIGHT: Form (আগের মতই) --}}
        <div class="lg:col-span-8">
            <div class="bg-white rounded shadow-sm border border-gray-200 dark:bg-gray-900 dark:border-gray-800">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center dark:border-gray-800">
                    <div class="space-y-1">
                        <div class="text-sm font-semibold text-gray-800 dark:text-gray-100">
                            {{ $isEdit ? 'Edit Category' : 'Create Category' }}
                        </div>
                        @if($categoryId)
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                ID: {{ $categoryId }}
                            </div>
                        @endif
                    </div>
                    <div class="flex space-x-2">
                        @if($categoryId)
                            <button type="button"
                                    wire:click="deleteCategory({{ $categoryId }})"
                                    onclick="return confirm('Delete this category?')"
                                    class="text-xs px-3 py-1.5 border border-red-200 text-red-600 rounded hover:bg-red-50">
                                Delete
                            </button>
                        @endif
                    </div>
                </div>

                <div class="px-6 py-4 grid grid-cols-1 lg:grid-cols-1 gap-6 text-gray-800 dark:text-gray-100">
                    {{-- LEFT column --}}
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1 dark:text-gray-300">
                                Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model="name"
                                   class="w-full border rounded px-3 py-2 text-sm bg-white text-gray-800 border-gray-300 focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700 dark:focus:border-blue-400">
                            @error('name')
                            <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1 dark:text-gray-300">
                                Permalink <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-center text-sm">
                                    <span
                                        class="px-2 py-2 border border-r-0 rounded-l bg-gray-50 text-gray-500 text-xs truncate max-w-[40%] dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300">
                                        {{ rtrim($baseUrl, '/') }}/
                                    </span>
                                <input type="text" wire:model="slug"
                                       class="flex-1 border rounded-r px-3 py-2 text-sm bg-white text-gray-800 border-gray-300 focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700 dark:focus:border-blue-400">
                            </div>
                            @error('slug')
                            <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                            @enderror
                            <div class="text-xs text-gray-500 mt-1 dark:text-gray-400">
                                Preview:
                                <a href="{{ preview_url('category',$this->slug) }}"
                                   target="_blank"
                                   class="text-blue-500 underline">
                                    {{ preview_url('category', $this->slug) }}
                                </a>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1 dark:text-gray-300">
                                Parent
                            </label>
                            <select wire:model="parent_id"
                                    class="w-full border rounded px-3 py-2 text-sm bg-white text-gray-800 border-gray-300 focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700 dark:focus:border-blue-400">
                                <option value="">None</option>
                                @foreach(\App\Models\Category::orderBy('name')->get() as $cat)
                                    @if(!$categoryId || $cat->id !== $categoryId)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('parent_id')
                            <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1 dark:text-gray-300">
                                Description
                            </label>
                            <textarea wire:model="description"
                                      rows="4"
                                      class="w-full border rounded px-3 py-2 text-sm bg-white text-gray-800 border-gray-300 focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700 dark:focus:border-blue-400"></textarea>
                            @error('description')
                            <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="flex items-center space-x-4">
                            <label class="flex items-center space-x-2 text-xs text-gray-700 dark:text-gray-300">
                                <input type="checkbox" wire:model="is_default"
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800">
                                <span>Is default?</span>
                            </label>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1 dark:text-gray-300">
                                Icon
                            </label>
                            <input type="text" wire:model="icon"
                                   class="w-full border rounded px-3 py-2 text-sm bg-white text-gray-800 border-gray-300 focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700 dark:focus:border-blue-400"
                                   placeholder="Ex: ti ti-home">
                            @error('icon')
                            <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="flex items-center space-x-4">
                            <label class="flex items-center space-x-2 text-xs text-gray-700 dark:text-gray-300">
                                <input type="checkbox" wire:model="is_featured"
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800">
                                <span>Is featured?</span>
                            </label>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1 dark:text-gray-300">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="status"
                                    class="w-full border rounded px-3 py-2 text-sm bg-white text-gray-800 border-gray-300 focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700 dark:focus:border-blue-400">
                                <option value="published">Published</option>
                                <option value="draft">Draft</option>
                                <option value="pending">Pending</option>
                            </select>
                            @error('status')
                            <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Image --}}
                        <div class="border border-dashed border-gray-300 rounded p-4 text-sm dark:border-gray-700">
                            @include('mediamanager::includes.media-input', [
                                  'name'  => 'image',
                                  'id'    => 'image',
                                  'label' => 'Thumbnail',
                                  'value' => $image ?? '',
                              ])
                        </div>
                        {{-- SEO META --}}
                        @include('admin.meta.seo-meta-box', [
                            'baseUrl' => $baseUrl,
                        ])

                        {{-- Publish box --}}
                        <div class="border border-gray-200 rounded dark:border-gray-700">
                            <div class="px-4 py-3 border-b border-gray-100 text-xs font-semibold text-gray-800 dark:border-gray-700 dark:text-gray-100">
                                Publish
                            </div>
                            <div class="px-4 py-3 flex space-x-2">
                                <button type="button"
                                        wire:click="save(false)"
                                        class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-4 py-2 rounded flex items-center space-x-1 cursor-pointer">
                                    <span>Save</span>
                                </button>
                                <button type="button"
                                        wire:click="save(true)"
                                        class="bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-semibold px-4 py-2 rounded cursor-pointer dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-gray-100">
                                    Save &amp; Exit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    {{--category sortable--}}
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('livewire:init', function () {

            function buildTreeData() {
                const data = [];

                function walk(ul, parentId = null) {
                    // শুধু এই UL-এর সরাসরি LI গুলো
                    ul.querySelectorAll(':scope > li').forEach((li, index) => {
                        if (!li.dataset.id) return;

                        const id = parseInt(li.dataset.id);

                        data.push({
                            id: id,
                            parent_id: parentId,
                            order: index,
                        });

                        // এই LI এর নিজের child UL (আমরা Blade-এ সবসময় বানিয়ে দিচ্ছি)
                        const childUl = li.querySelector(':scope > ul.js-category-list');
                        if (childUl) {
                            walk(childUl, id);
                        }
                    });
                }

                document.querySelectorAll('.js-category-root').forEach(root => walk(root, null));

                return data;
            }

            function initSortableTree() {
                document.querySelectorAll('.js-category-list').forEach(function (list) {
                    if (list.dataset.sortableInit === '1') return;

                    new Sortable(list, {
                        group: 'categories-tree',
                        handle: '.js-handle',
                        animation: 150,
                        fallbackOnBody: true,
                        swapThreshold: 0.65,
                        ghostClass: 'bg-blue-50',
                        onEnd() {
                            const items = buildTreeData();
                            Livewire.dispatch('categories-tree-updated', { items: items });
                        }
                    });

                    list.dataset.sortableInit = '1';
                });
            }

            initSortableTree();

            Livewire.hook('message.processed', () => {
                initSortableTree();
            });
        });
    </script>
@endpush
