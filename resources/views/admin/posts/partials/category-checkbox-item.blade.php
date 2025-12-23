@props(['category', 'level' => 0, 'selected' => []])

@php
    $indent = $level * 14;
    $isSelected = in_array($category->id, $selected ?? []);
@endphp

<div
    class="group relative rounded-lg px-3 py-2 transition hover:bg-white hover:shadow-sm
           dark:hover:bg-slate-800/70"
    style="padding-left: {{ $indent }}px;">
    <label class="flex items-start gap-3 text-xs text-slate-800 dark:text-slate-100 cursor-pointer">
        <input
            type="checkbox"
            wire:model="category_ids"
            wire:change="toggleCategory({{ $category->id }})"
            value="{{ $category->id }}"
            class="mt-0.5 h-4 w-4 rounded border-slate-300 focus:ring-indigo-500
                   dark:border-slate-600"
        />
        <div class="flex-1">
            <div class="flex items-center gap-2">
                <span class="font-semibold">{{ $category->name }}</span>
                @if($isSelected)
                    <span class="rounded-full bg-indigo-50 px-2 py-0.5 text-[10px] font-semibold text-indigo-600 border border-indigo-100
                                 dark:bg-indigo-900/40 dark:text-indigo-200 dark:border-indigo-700/60">Selected</span>
                @endif
            </div>
            <p class="text-[11px] text-slate-500 dark:text-slate-400">
                Level {{ $level + 1 }} category
            </p>
        </div>
    </label>

    <span class="pointer-events-none absolute inset-y-0 left-1 w-px bg-slate-200 group-first:hidden dark:bg-slate-700"></span>
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
