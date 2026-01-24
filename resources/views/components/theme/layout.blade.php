@props(['menus' => config('theme-options.menus', []), 'activeMenu' => null])

<div class="w-full bg-white dark:bg-slate-900 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between p-6 border-b border-slate-200 dark:border-slate-700 gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900 dark:text-white">{{ __('Theme Options') }}</h1>
            <p class="text-sm text-slate-500">{{ __('Manage your website appearance and general settings') }}</p>
        </div>
    </div>

    <div class="flex flex-col md:flex-row">
        {{-- Sidebar Tabs --}}
        <aside class="w-full md:w-60 border-r border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50">
            <nav class="p-4 space-y-1">
                @php
                    $menus = is_array($menus) ? $menus : [];
                    $resolvedActiveMenu = $activeMenu ?? request()->query('as', $menus[0]['id'] ?? 'general');
                    $activeMenuData = collect($menus)->firstWhere('id', $resolvedActiveMenu) ?? ($menus[0] ?? null);
                @endphp
                @foreach($menus as $menu)
                    <a href="{{ route('theme.theme-options', ['as' => $menu['id']]) }}" wire:navigate class="flex items-center px-2 py-1 text-sm font-medium rounded-md {{ $resolvedActiveMenu === $menu['id'] ? 'bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 shadow-sm border border-slate-200 dark:border-slate-600' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700' }}">
                        <span class="mr-3 text-lg opacity-70"><i class="fa fa-{{ $menu['icon'] }}"></i> </span> {{ __($menu['label']) }}
                    </a>
                @endforeach
            </nav>
        </aside>

        {{-- Main Form --}}
        <main class="flex-1 p-6 space-y-8">
            @if($activeMenuData)
                <div class="flex items-start gap-3 rounded-md border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 p-4">
                    <span class="mt-1 text-lg text-indigo-600 dark:text-indigo-400">
                        <i class="fa fa-{{ $activeMenuData['icon'] }}"></i>
                    </span>
                    <div>
                        <h2 class="text-base font-semibold text-slate-900 dark:text-white">{{ __($activeMenuData['label']) }}</h2>
                        <p class="text-sm text-slate-500">{{ __($activeMenuData['description']) }}</p>
                    </div>
                </div>
            @endif
            {{ $slot }}
        </main>
    </div>
</div>
