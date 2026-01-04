<div class="w-full bg-white dark:bg-slate-900 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between p-6 border-b border-slate-200 dark:border-slate-700 gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900 dark:text-white">{{ __('Theme Options') }}</h1>
            <p class="text-sm text-slate-500">{{ __('Manage your website appearance and general settings') }}</p>
        </div>
        <button type="button" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md shadow-sm transition-colors">
            {{ __('Save Changes') }}
        </button>
    </div>

    <div class="flex flex-col md:flex-row">
        {{-- Sidebar Tabs --}}
        <aside class="w-full md:w-60 border-r border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50">
            <nav class="p-4 space-y-1">
                @php
                    $menus = [
                        ['id' => 'general', 'icon' => 'cog', 'label' => 'General', 'description' => 'Configure general theme preferences.'],
                        ['id' => 'header', 'icon' => 'table', 'label' => 'Header', 'description' => 'Manage header layout and visibility.'],
                        ['id' => 'page', 'icon' => 'file-text', 'label' => 'Page', 'description' => 'Adjust page templates and sections.'],
                        ['id' => 'logo', 'icon' => 'image', 'label' => 'Logo', 'description' => 'Upload logos and brand assets.'],
                        ['id' => 'social', 'icon' => 'share', 'label' => 'Social Links', 'description' => 'Configure social profile links.'],
                    ];
                    $activeMenu = request()->query('as', 'general');
                    $activeMenuData = collect($menus)->firstWhere('id', $activeMenu) ?? $menus[0];
                @endphp
                @foreach($menus as $menu)
                    <a href="{{ route('theme.theme-options', ['as' => $menu['id']]) }}" class="flex items-center px-2 py-1 text-sm font-medium rounded-md {{ $activeMenu === $menu['id'] ? 'bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 shadow-sm border border-slate-200 dark:border-slate-600' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700' }}">
                        <span class="mr-3 text-lg opacity-70"><i class="fa fa-{{ $menu['icon'] }}"></i> </span> {{ __($menu['label']) }}
                    </a>
                @endforeach
            </nav>
        </aside>

        {{-- Main Form --}}
        <main class="flex-1 p-6 space-y-8">
            <div class="flex items-start gap-3 rounded-md border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 p-4">
                <span class="mt-1 text-lg text-indigo-600 dark:text-indigo-400">
                    <i class="fa fa-{{ $activeMenuData['icon'] }}"></i>
                </span>
                <div>
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white">{{ __($activeMenuData['label']) }}</h2>
                    <p class="text-sm text-slate-500">{{ __($activeMenuData['description']) }}</p>
                </div>
            </div>
            {{ $slot }}
        </main>
    </div>
</div>
