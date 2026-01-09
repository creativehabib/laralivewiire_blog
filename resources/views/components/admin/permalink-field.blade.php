@props([
    'label' => 'Permalink',
    'required' => true,
    'baseUrl' => null,
    'slug' => null,
    'previewType' => null,
    'previewUrl' => null,
    'previewAsLink' => true,
    'labelClass' => 'block text-xs font-semibold text-slate-700 dark:text-slate-200 mb-1',
    'containerClass' => '',
    'inputWrapperClass' => 'mt-2',
    'prefixClass' => 'px-2 py-2 border border-r-0 rounded-l bg-gray-50 dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-500 dark:text-gray-300 text-xs',
    'inputClass' => 'block w-full rounded-lg border px-3 py-2 text-sm border-slate-300 bg-slate-50 text-slate-900 placeholder-slate-400 focus:border-sky-500 focus:ring-1 focus:ring-sky-500 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:placeholder-slate-500',
    'actionWrapperClass' => 'inline-flex items-center border border-l-0 border-gray-300 dark:border-gray-700 rounded-r-md bg-gray-50 dark:bg-gray-900 overflow-hidden',
    'actionButtonClass' => 'px-3 py-2 text-gray-400 hover:text-amber-500 cursor-pointer transition-colors',
    'generateAction' => null,
    'previewWrapperClass' => 'text-xs text-gray-500 dark:text-gray-400 mt-1',
    'previewClass' => 'text-blue-500 dark:text-blue-400 underline',
    'previewLabel' => 'Preview:',
    'placeholder' => 'slug',
    'errorName' => 'slug',
    'errorClass' => 'text-xs text-rose-500 mt-1',
])

@php
    $computedPreviewUrl = $previewUrl ?? ($previewType ? preview_url($previewType, $slug) : null);
@endphp

<div class="{{ $containerClass }}">
    <label class="{{ $labelClass }}">
        {{ $label }}
        @if ($required)
            <span class="text-rose-500">*</span>
        @endif
    </label>

    <div class="{{ $inputWrapperClass }}">
        @if ($baseUrl)
            <span class="{{ $prefixClass }}">
                {{ rtrim($baseUrl, '/') }}/
            </span>
        @endif

        <input
            type="text"
            {{ $attributes->merge(['class' => $inputClass]) }}
            placeholder="{{ $placeholder }}"
        >

        @if ($generateAction)
            <div class="{{ $actionWrapperClass }}">
                <button type="button"
                        wire:click="{{ $generateAction }}"
                        class="{{ $actionButtonClass }}"
                        title="Regenerate Slug">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                </button>
            </div>
        @endif
    </div>

    @error($errorName)
    <div class="{{ $errorClass }}">{{ $message }}</div>
    @enderror

    @if ($computedPreviewUrl)
        <div class="{{ $previewWrapperClass }}">
            {{ $previewLabel }}
            @if ($previewAsLink)
                <a href="{{ $computedPreviewUrl }}" target="_blank" class="{{ $previewClass }}">
                    {{ $computedPreviewUrl }}
                </a>
            @else
                <span class="{{ $previewClass }}">{{ $computedPreviewUrl }}</span>
            @endif
        </div>
    @endif
</div>
