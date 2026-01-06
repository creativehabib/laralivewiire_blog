@auth
    @php
        $pageModel = request()->route('page');
        $editPageUrl = ($pageModel && $pageModel instanceof \App\Models\Admin\Page)
            ? route('admins.pages.edit', ['pageId' => $pageModel->id])
            : route('admins.pages.index');
    @endphp
    <div class="bg-slate-900 text-white text-sm">
        <div class="container flex flex-wrap items-center justify-between gap-3 px-4 py-2">
            <div class="flex items-center gap-4">
                <span class="font-semibold uppercase tracking-wide">{{ __('Admin Bar') }}</span>
                <span class="text-slate-300">{{ __('Username: :name', ['name' => auth()->user()->name]) }}</span>
            </div>
            <div class="flex flex-wrap items-center gap-4">
                <a href="{{ $editPageUrl }}" class="hover:text-white hover:underline">
                    {{ __('Edit Page') }}
                </a>
                <div class="relative group">
                    <button type="button" class="flex items-center gap-1 hover:text-white hover:underline">
                        {{ __('Add New') }}
                        <span aria-hidden="true">▾</span>
                    </button>
                    <div class="absolute right-0 mt-2 w-40 rounded-md border border-slate-700 bg-slate-900/95 shadow-lg opacity-0 invisible group-hover:visible group-hover:opacity-100 transition">
                        <a href="{{ route('admins.pages.create') }}" class="block px-3 py-2 hover:bg-slate-800">
                            {{ __('Page') }}
                        </a>
                        <a href="{{ route('blogs.posts.create') }}" class="block px-3 py-2 hover:bg-slate-800">
                            {{ __('Post') }}
                        </a>
                        <a href="{{ route('system.users.create') }}" class="block px-3 py-2 hover:bg-slate-800">
                            {{ __('User') }}
                        </a>
                    </div>
                </div>
                <div class="relative group">
                    <button type="button" class="flex items-center gap-1 hover:text-white hover:underline">
                        {{ __('Appearance') }}
                        <span aria-hidden="true">▾</span>
                    </button>
                    <div class="absolute right-0 mt-2 w-44 rounded-md border border-slate-700 bg-slate-900/95 shadow-lg opacity-0 invisible group-hover:visible group-hover:opacity-100 transition">
                        <a href="{{ route('appearance.menus.index') }}" class="block px-3 py-2 hover:bg-slate-800">
                            {{ __('Menu') }}
                        </a>
                        <a href="{{ route('appearance.admin-appearance') }}" class="block px-3 py-2 hover:bg-slate-800">
                            {{ __('Setting') }}
                        </a>
                        <a href="{{ route('theme.theme-options') }}" class="block px-3 py-2 hover:bg-slate-800">
                            {{ __('Theme Option') }}
                        </a>
                    </div>
                </div>
                <a href="{{ route('dashboard') }}" class="hover:text-white hover:underline">
                    {{ __('Dashboard') }}
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="hover:text-white hover:underline">
                        {{ __('Logout') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
@endauth
