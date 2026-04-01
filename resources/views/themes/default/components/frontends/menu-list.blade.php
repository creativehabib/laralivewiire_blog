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
                       class="py-3 flex items-center gap-1 text-slate-700 dark:text-slate-200 hover:text-primary-dark dark:hover:text-primary-light transition-colors duration-150">
                        {{ $title }}
                        @if($hasChildren)
                            <i class="fa-solid fa-chevron-down text-[10px] transition-transform duration-150 group-hover:rotate-180"></i>
                        @endif
                    </a>
                    @if($hasChildren)
                        <div class="absolute left-0 hidden group-hover:block z-30">
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
                       class="flex items-center justify-between gap-2 py-1.5 text-sm text-slate-700 dark:text-slate-200 hover:text-primary-dark dark:hover:text-primary-light">
                        <span>{{ $title }}</span>
                        @if($hasChildren)
                            <i class="fa-solid fa-chevron-right text-[10px]"></i>
                        @endif
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
                @if($hasChildren)
                    <details class="group rounded-lg border border-slate-200/70 bg-white/90 open:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:open:bg-slate-800/70 transition-colors">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-2 px-3 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-200 marker:content-none">
                            <span>{{ $title }}</span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-500 transition-transform duration-200 group-open:rotate-180"></i>
                        </summary>

                        <div class="px-3 pb-3">
                            <a href="{{ $url }}"
                               target="{{ $target }}"
                               class="mb-2 inline-flex items-center text-xs font-medium text-primary-dark underline-offset-2 hover:underline dark:text-primary-light">
                                সব দেখুন
                            </a>

                            <div class="ml-2 border-l border-slate-200 pl-3 dark:border-slate-700">
                                <x-frontends.menu-list :items="$children" variant="mobile" />
                            </div>
                        </div>
                    </details>
                @else
                    <a href="{{ $url }}"
                       target="{{ $target }}"
                       class="flex items-center justify-between gap-2 rounded-md px-2 py-2 text-sm text-slate-700 transition hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800">
                        <span>{{ $title }}</span>
                    </a>
                @endif
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
