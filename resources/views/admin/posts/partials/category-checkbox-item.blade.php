@props(['category', 'level' => 0, 'selected' => []])

@php
    $indent = $level * 12;
@endphp

<div class="flex items-center py-1" style="padding-left: {{ $indent }}px;">
    <label class="flex items-center space-x-2 text-xs text-gray-700 cursor-pointer">
        <input
            type="checkbox"
            wire:model="category_ids"
            wire:change="toggleCategory({{ $category->id }})"
            value="{{ $category->id }}"
            class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
        />
        <span>{{ $category->name }}</span>
    </label>
</div>

@if($category->childrenRecursive?->count())
    @foreach($category->childrenRecursive as $child)
        @include('admin.posts.partials.category-checkbox-item', [
            'category' => $child,
            'level'    => $level + 1,
            'selected' => $selected ?? [],
        ])
    @endforeach
@endif
