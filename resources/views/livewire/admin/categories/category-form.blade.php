{{-- resources/views/livewire/admin/categories/category-form.blade.php --}}
<div class="space-y-4">
    {{-- Breadcrumb --}}
    <div class="text-sm text-gray-600 dark:text-gray-300">
        <span class="text-gray-400 dark:text-gray-500">Dashboard</span> /
        <span class="text-gray-400 dark:text-gray-500">Blog</span> /
        <a href="{{ route('blogs.categories.index') }}/table?as=table" class="text-blue-600 dark:text-blue-400">Categories</a> /
        <span class="font-semibold text-gray-800 dark:text-gray-100">
            {{ $categoryId ? 'Edit Category' : 'Create a new category' }}
        </span>
    </div>

    @if (session()->has('success'))
        <div class="bg-green-50 dark:bg-green-900/40 border border-green-200 dark:border-green-700 text-green-800 dark:text-green-200 px-4 py-2 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {{-- LEFT big form --}}
        <div class="lg:col-span-8">
            <div class="bg-white dark:bg-gray-900 rounded shadow-sm border border-gray-200 dark:border-gray-800 p-6 space-y-4">

                {{-- Name --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-200 mb-1">
                        Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" wire:model="name"
                           class="w-full border rounded px-3 py-2 text-sm bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Name">
                    @error('name')
                    <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Permalink --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-200 mb-1">
                        Permalink <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-2 flex items-center text-sm">
                        <span class="px-2 py-2 border border-r-0 rounded-l bg-gray-50 dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-500 dark:text-gray-300 text-xs">
                            {{ rtrim($baseUrl, '/') }}/
                        </span>
                        <input type="text" wire:model="slug"
                               class="flex-1 border rounded-r px-3 py-2 text-sm bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="slug">
                    </div>
                    @error('slug')
                    <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                    @enderror


                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        Preview:
                        <a href="{{ preview_url('category',$this->slug) }}"
                           target="_blank"
                           class="text-blue-500 dark:text-blue-400 underline">
                            {{ preview_url('category', $this->slug) }}
                        </a>
                    </div>
                </div>

                {{-- Parent --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-200 mb-1">
                        Parent
                    </label>
                    <select wire:model="parent_id"
                            class="w-full border rounded px-3 py-2 text-sm bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100">
                        <option value="">None</option>
                        @foreach($categories as $cat)
                            @if(!$categoryId || $cat->id !== $categoryId)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endif
                        @endforeach
                    </select>
                    @error('parent_id')
                    <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Description --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-200 mb-1">
                        Description
                    </label>
                    <textarea wire:model="description"
                              rows="4"
                              class="w-full border rounded px-3 py-2 text-sm bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Short description"></textarea>
                    @error('description')
                    <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Switches --}}
                <div class="flex flex-col sm:flex-row sm:space-x-6 space-y-3 sm:space-y-0">
                    <label class="flex items-center space-x-2 text-xs text-gray-700 dark:text-gray-200">
                        <input type="checkbox" wire:model="is_default"
                               class="rounded border-gray-300 dark:border-gray-600">
                        <span>Is default?</span>
                    </label>

                    <label class="flex items-center space-x-2 text-xs text-gray-700 dark:text-gray-200">
                        <input type="checkbox" wire:model="is_featured"
                               class="rounded border-gray-300 dark:border-gray-600">
                        <span>Is featured?</span>
                    </label>
                </div>

                {{-- Icon --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-200 mb-1">
                        Icon
                    </label>
                    <input type="text" wire:model="icon"
                           class="w-full border rounded px-3 py-2 text-sm bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Ex: ti ti-home">
                    @error('icon')
                    <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- SEO META --}}
                @include('admin.meta.seo-meta-box',['seoType' => 'category'])

                @php
                    $seo = $seo ?? $this->seoAnalysis;
                @endphp

                @include('admin.meta.yoast-box', ['seo' => $seo])

            </div>
        </div>

        {{-- RIGHT sidebar --}}
        <div class="lg:col-span-4 space-y-4">
            {{-- Publish box --}}
            <div class="bg-white dark:bg-gray-900 rounded shadow-sm border border-gray-200 dark:border-gray-800">
                <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-800 text-xs font-semibold text-gray-800 dark:text-gray-100">
                    Publish
                </div>
                <div class="px-4 py-3 space-y-3">
                    <div class="flex space-x-2">
                        <button type="button"
                                wire:click="save(false)"
                                class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-4 py-2 rounded cursor-pointer shadow">
                            Save
                        </button>
                        <button type="button"
                                wire:click="save(true)"
                                class="bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-800 dark:text-gray-100 text-xs font-semibold px-4 py-2 rounded cursor-pointer border border-gray-200 dark:border-gray-700">
                            Save &amp; Exit
                        </button>
                    </div>
                </div>
            </div>

            {{-- Status box --}}
            <div class="bg-white dark:bg-gray-900 rounded shadow-sm border border-gray-200 dark:border-gray-800">
                <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-800 text-xs font-semibold text-gray-800 dark:text-gray-100">
                    Status <span class="text-red-500">*</span>
                </div>
                <div class="px-4 py-3">
                    <select wire:model="status"
                            class="w-full border rounded px-3 py-2 text-sm bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100">
                        <option value="published">Published</option>
                        <option value="draft">Draft</option>
                    </select>
                    @error('status')
                    <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Image box --}}
            <div class="bg-white dark:bg-gray-900 rounded shadow-sm border border-gray-200 dark:border-gray-800 p-3">
                @include('mediamanager::includes.media-input', [
                      'name'  => 'image',
                      'id'    => 'image',
                      'label' => 'Thumbnail',
                      'value' => $image ?? '',
                  ])
            </div>
        </div>
    </div>
</div>
