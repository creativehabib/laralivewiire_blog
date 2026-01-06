@auth
    @php
        $adminLogo = setting('admin_logo');
        $adminTitle = setting('admin_title', config('app.name'));
        $pageModel = request()->route('page');
        $postModel = request()->route('post');
        $categoryModel = request()->route('category');

        if ($postModel instanceof \App\Models\Post) {
            $editItemLabel = __('Edit This Post');
            $editItemUrl = route('blogs.posts.edit', ['post' => $postModel->id]);
        } elseif ($categoryModel instanceof \App\Models\Category) {
            $editItemLabel = __('Edit This Category');
            $editItemUrl = route('blogs.categories.edit', ['categoryId' => $categoryModel->id]);
        } elseif ($pageModel instanceof \App\Models\Admin\Page) {
            $editItemLabel = __('Edit This Page');
            $editItemUrl = route('admins.pages.edit', ['pageId' => $pageModel->id]);
        } else {
            $editItemLabel = __('Edit This Page');
            $editItemUrl = route('admins.pages.index');
        }
    @endphp
    <div class="sticky top-0 z-[9999] bg-slate-900 text-white text-sm">
        <div class="container flex flex-wrap items-center justify-between gap-3 px-4 py-2">
            <div class="flex flex-wrap items-center gap-4">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 font-semibold">
                    @if($adminLogo)
                        <img src="{{ $adminLogo }}" alt="{{ $adminTitle }}" class="h-6 w-auto" />
                    @else
                        <span class="uppercase tracking-wide">{{ $adminTitle }}</span>
                    @endif
                </a>
                <div class="relative group">
                    <button type="button" class="flex items-center gap-1 hover:text-white hover:underline">
                        {{ __('Appearence') }}
                        <span aria-hidden="true">▾</span>
                    </button>
                    <div class="absolute left-0 mt-2 w-44 rounded-md border border-slate-700 bg-slate-900/95 shadow-lg opacity-0 invisible group-hover:visible group-hover:opacity-100 transition">
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
                <div class="relative group">
                    <button type="button" class="flex items-center gap-1 hover:text-white hover:underline">
                        {{ __('Add New') }}
                        <span aria-hidden="true">▾</span>
                    </button>
                    <div class="absolute left-0 mt-2 w-40 rounded-md border border-slate-700 bg-slate-900/95 shadow-lg opacity-0 invisible group-hover:visible group-hover:opacity-100 transition">
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
                <a href="{{ $editItemUrl }}" class="hover:text-white hover:underline">
                    {{ $editItemLabel }}
                </a>
            </div>
            <div class="flex flex-wrap items-center gap-4">
                <div class="relative group">
                    <button type="button" class="flex items-center gap-1 text-slate-300 hover:text-white">
                        {{ __('Login :name', ['name' => auth()->user()->name]) }}
                        <span aria-hidden="true">▾</span>
                    </button>
                    <div class="absolute right-0 mt-2 w-40 rounded-md border border-slate-700 bg-slate-900/95 shadow-lg opacity-0 invisible group-hover:visible group-hover:opacity-100 transition">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full px-3 py-2 text-left hover:bg-slate-800">
                                {{ __('Logout') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endauth
