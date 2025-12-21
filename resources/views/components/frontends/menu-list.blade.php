@props(['items' => [], 'variant' => 'desktop'])

@php
    $items = collect($items);
@endphp

@if($items->isNotEmpty())
    @if($variant === 'desktop')
        <div class="flex items-center gap-6 text-sm font-medium">
            @foreach($items as $item)
                @php
                    $children = collect(data_get($item, 'children', []));
                    $title = data_get($item, 'title');
                    $url = data_get($item, 'url', '#');
                    $target = data_get($item, 'target', '_self');
                    $hasChildren = $children->isNotEmpty();
                @endphp
                <div class="relative group">
                    <a href="{{ $url }}"
                       target="{{ $target }}"
                       class="p-2 text-slate-700 dark:text-slate-200 hover:text-primary-dark dark:hover:text-primary-light transition-colors duration-150">
                        {{ $title }}
                    </a>
                    @if($hasChildren)
                        <div class="absolute left-0 mt-2 hidden group-hover:block z-30">
                            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-md shadow-md py-2 min-w-[180px]">
                                <x-frontends.menu-list :items="$children" variant="dropdown" />
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @elseif($variant === 'dropdown')
        <div class="flex flex-col gap-1">
            @foreach($items as $item)
                @php
                    $children = collect(data_get($item, 'children', []));
                    $title = data_get($item, 'title');
                    $url = data_get($item, 'url', '#');
                    $target = data_get($item, 'target', '_self');
                    $hasChildren = $children->isNotEmpty();
                @endphp
                <div class="group relative px-4">
                    <a href="{{ $url }}"
                       target="{{ $target }}"
                       class="block py-1.5 text-sm text-slate-700 dark:text-slate-200 hover:text-primary-dark dark:hover:text-primary-light">
                        {{ $title }}
                    </a>
                    @if($hasChildren)
                        <div class="absolute left-full top-0 ml-2 hidden group-hover:block z-30">
                            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-md shadow-md py-2 min-w-[180px]">
                                <x-frontends.menu-list :items="$children" variant="dropdown" />
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @elseif($variant === 'mobile')
        <div class="flex flex-col gap-1">
            @foreach($items as $item)
                @php
                    $children = collect(data_get($item, 'children', []));
                    $title = data_get($item, 'title');
                    $url = data_get($item, 'url', '#');
                    $target = data_get($item, 'target', '_self');
                    $hasChildren = $children->isNotEmpty();
                @endphp
                <div class="flex flex-col gap-1">
                    <a href="{{ $url }}"
                       target="{{ $target }}"
                       class="block px-2 py-2 rounded-md text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800">
                        {{ $title }}
                    </a>
                    @if($hasChildren)
                        <div class="ml-4 border-l border-slate-200 dark:border-slate-700 pl-3">
                            <x-frontends.menu-list :items="$children" variant="mobile" />
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @elseif($variant === 'footer')
        <ul class="space-y-1 text-xs text-slate-100/85">
            @foreach($items as $item)
                @php
                    $title = data_get($item, 'title');
                    $url = data_get($item, 'url', '#');
                    $target = data_get($item, 'target', '_self');
                @endphp
                <li>
                    <a href="{{ $url }}" target="{{ $target }}" class="hover:underline">
                        {{ $title }}
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
@endif
