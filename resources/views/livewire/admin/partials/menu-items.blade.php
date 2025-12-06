@props(['items' => [], 'level' => 0, 'editingItemId' => null, 'availableTargets' => []])

@if(!empty($items))
    <ol class="dd-list">
        @foreach($items as $item)
            @php
                $hasChildren = !empty($item['children']);
                $isEditing = $editingItemId === $item['id'];
            @endphp

            <li class="dd-item dd3-item" data-id="{{ $item['id'] }}" wire:key="menu-item-{{ $item['id'] }}">

                <!-- Item Card -->
                <div class="dd3-content bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg p-3 shadow-sm">

                    <!-- Header: Drag + Title + Edit Button -->
                    <div class="flex justify-between items-center flex-wrap gap-2">

                        <!-- Drag handle -->
                        <div class="dd-handle drag-handle flex-1 cursor-move select-none font-medium text-slate-800 dark:text-slate-100">
                            {{ $item['title'] }}
                        </div>

                        <!-- Edit / Hide Button -->
                        <button
                            type="button"
                            class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-800 dark:hover:text-indigo-400 pr-2 cursor-pointer"
                            wire:click="toggleEditing({{ $item['id'] }})"
                        >
                            <span class="mr-1">
                                {{ $isEditing ? 'Hide options' : 'Edit item' }}
                            </span>

                            <i class="fas fa-chevron-{{ $isEditing ? 'up' : 'down' }} text-xs"></i>
                        </button>
                    </div>

                    <!-- Edit Panel -->
                    @if($isEditing)
                        <div class="border-t border-slate-200 dark:border-slate-700">

                            <form
                                wire:key="menu-item-edit-form-{{ $item['id'] }}"
                                wire:submit.prevent="updateMenuItem({{ $item['id'] }})"
                                wire:loading.class="opacity-50"
                                class="space-y-3 p-4"
                            >
                                <input type="hidden" wire:model.defer="editingItemId">

                                <!-- Title -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">
                                        Navigation label
                                    </label>
                                    <input
                                        type="text"
                                        class="block w-full h-10 rounded-lg border border-slate-300 dark:border-slate-700 px-3 text-sm
                                               bg-white dark:bg-slate-900
                                               text-slate-900 dark:text-slate-100
                                               placeholder-slate-400
                                               focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                                        wire:model.defer="editTitle"
                                    >
                                    @error('editTitle')
                                    <span class="block text-xs text-red-500 mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- URL -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">
                                        URL
                                    </label>
                                    <input
                                        type="text"
                                        class="block w-full h-10 rounded-lg border border-slate-300 dark:border-slate-700 px-3 text-sm
                                               bg-white dark:bg-slate-900
                                               text-slate-900 dark:text-slate-100
                                               placeholder-slate-400
                                               focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                                        wire:model.defer="editUrl"
                                    >
                                    @error('editUrl')
                                    <span class="block text-xs text-red-500 mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Target -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">
                                        Open link in
                                    </label>
                                    <select
                                        class="block w-full h-10 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 text-sm
                                               text-slate-900 dark:text-slate-100
                                               focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                                        wire:model.defer="editTarget"
                                    >
                                        @foreach($availableTargets as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('editTarget')
                                    <span class="block text-xs text-red-500 mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex flex-wrap items-center gap-2">

                                    <!-- Save -->
                                    <button
                                        type="submit"
                                        class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-1.5 text-sm
                                               font-semibold text-white shadow-sm hover:bg-indigo-700
                                               focus:outline-none focus:ring-2 focus:ring-indigo-500 cursor-pointer"
                                        wire:loading.attr="disabled"
                                        wire:target="updateMenuItem"
                                    >
                                        <span wire:loading.remove wire:target="updateMenuItem">Save</span>
                                        <span wire:loading wire:target="updateMenuItem">Saving...</span>
                                    </button>

                                    <!-- Remove -->
                                    <button
                                        type="button"
                                        class="inline-flex items-center rounded-lg border border-red-500 px-4 py-1.5 text-sm cursor-pointer
                                               font-semibold text-red-600 hover:bg-red-50 dark:hover:bg-red-950/40
                                               focus:outline-none focus:ring-2 focus:ring-red-500"
                                        wire:click="deleteMenuItem({{ $item['id'] }})"
                                        wire:loading.attr="disabled"
                                        onclick="confirm('Are you sure you want to remove this item?') || event.stopImmediatePropagation()"
                                    >
                                        Remove
                                    </button>

                                    <!-- Cancel -->
                                    <button
                                        type="button"
                                        class="inline-flex items-center rounded-lg bg-slate-100 dark:bg-slate-800 px-4 py-1.5 text-sm cursor-pointer
                                               font-semibold text-slate-700 dark:text-slate-100 hover:bg-slate-200 dark:hover:bg-slate-700
                                               focus:outline-none focus:ring-2 focus:ring-slate-400 dark:focus:ring-slate-500"
                                        wire:click="cancelEditing"
                                        wire:loading.attr="disabled"
                                    >
                                        Cancel
                                    </button>

                                </div>
                            </form>

                        </div>
                    @endif

                </div>

                <!-- Child Items -->
                @if($hasChildren)
                    @include('livewire.admin.partials.menu-items', [
                        'items' => $item['children'],
                        'level' => $level + 1,
                        'editingItemId' => $editingItemId,
                        'availableTargets' => $availableTargets
                    ])
                @endif

            </li>
        @endforeach
    </ol>
@endif
