@auth
    @php
        $adminLogo = setting('admin_logo');
        $adminTitle = setting('admin_title', config('app.name'));
        $pageModel = request()->route('page');
        $postModel = request()->route('post');
        $categoryModel = request()->route('category');
        $tagModel = request()->route('tag');

        if ($postModel instanceof \App\Models\Post) {
            $editItemLabel = __('Edit Post');
            $editItemUrl = route('blogs.posts.edit', ['post' => $postModel->id]);
            $editIcon = 'fa-pencil';
        } elseif ($categoryModel instanceof \App\Models\Category) {
            $editItemLabel = __('Edit Category');
            $editItemUrl = route('blogs.categories.edit', ['categoryId' => $categoryModel->id]);
            $editIcon = 'fa-layer-group';
        } elseif ($tagModel instanceof \App\Models\Admin\Tag) {
            $editItemLabel = __('Edit Tag');
            $editItemUrl = route('blogs.tags.edit', ['tag' => $tagModel->id]);
            $editIcon = 'fa-tags';
        } elseif ($pageModel instanceof \App\Models\Admin\Page) {
            $editItemLabel = __('Edit Page');
            $editItemUrl = route('admins.pages.edit', ['pageId' => $pageModel->id]);
            $editIcon = 'fa-file-lines';
        } else {
            $editItemLabel = __('Admin Panel');
            $editItemUrl = route('dashboard');
            $editIcon = 'fa-gauge-high';
        }
    @endphp

    <div class="sticky top-0 z-[9999] bg-slate-900/95 backdrop-blur-md border-b border-slate-700/50 text-slate-300 text-[13px] font-medium antialiased shadow-2xl">
        <div class="max-w-[1920px] mx-auto flex items-center justify-between px-2 sm:px-4 h-10">

            {{-- বাম পাশের সেকশন --}}
            <div class="flex items-center gap-0.5 sm:gap-1 h-full">
                {{-- ব্র্যান্ড/লোগো --}}
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-2 sm:px-3 h-full hover:bg-white/10 transition-colors group">
                    @if($adminLogo)
                        <img src="{{ $adminLogo }}" alt="{{ $adminTitle }}" class="h-5 w-auto object-contain" />
                    @else
                        <i class="fa-solid fa-bolt text-amber-400"></i>
                        <span class="font-bold text-white tracking-tight hidden xs:inline-block">{{ $adminTitle }}</span>
                    @endif
                </a>

                <div class="w-px h-4 bg-slate-700 mx-1"></div>

                {{-- Appearance ড্রপডাউন --}}
                <div class="relative group h-full">
                    <button type="button" class="flex items-center gap-1.5 px-2 sm:px-3 h-full hover:text-white hover:bg-white/10 transition-all">
                        <i class="fa-solid fa-palette opacity-70"></i>
                        <span class="hidden md:inline-block">{{ __('Appearance') }}</span>
                        <i class="fa-solid fa-chevron-down text-[10px] mt-0.5 opacity-50 group-hover:rotate-180 transition-transform"></i>
                    </button>
                    <div class="absolute left-0 top-full w-48 py-2 bg-slate-900 border border-slate-700 shadow-2xl rounded-b-md opacity-0 invisible group-hover:visible group-hover:opacity-100 transition-all transform origin-top scale-95 group-hover:scale-100">
                        <a href="{{ route('appearance.menus.index') }}" class="flex items-center gap-2 px-4 py-2 hover:bg-indigo-600 hover:text-white transition">
                            <i class="fa-solid fa-bars-staggered w-4"></i> {{ __('Menus') }}
                        </a>
                        <a href="{{ route('appearance.admin-appearance') }}" class="flex items-center gap-2 px-4 py-2 hover:bg-indigo-600 hover:text-white transition">
                            <i class="fa-solid fa-sliders w-4"></i> {{ __('Settings') }}
                        </a>
                        <a href="{{ route('theme.theme-options') }}" class="block px-3 py-2 hover:bg-slate-800">
                           <i class="fa-solid fa-wand-magic-sparkles w-4"></i> {{ __('Theme Option') }}
                        </a>
                    </div>
                </div>

                {{-- Add New ড্রপডাউন --}}
                <div class="relative group h-full">
                    <button type="button" class="flex items-center gap-1.5 px-2 sm:px-3 h-full hover:text-white hover:bg-white/10 transition-all">
                        <i class="fa-solid fa-plus-circle opacity-70"></i>
                        <span class="hidden md:inline-block">{{ __('New') }}</span>
                        <i class="fa-solid fa-chevron-down text-[10px] mt-0.5 opacity-50 group-hover:rotate-180 transition-transform"></i>
                    </button>
                    <div class="absolute left-0 top-full w-40 py-2 bg-slate-900 border border-slate-700 shadow-2xl rounded-b-md opacity-0 invisible group-hover:visible group-hover:opacity-100 transition-all transform origin-top scale-95 group-hover:scale-100">
                        <a href="{{ route('blogs.posts.create') }}" class="flex items-center gap-2 px-4 py-2 hover:bg-indigo-600 hover:text-white transition">
                            <i class="fa-solid fa-pencil w-4"></i> {{ __('Post') }}
                        </a>
                        <a href="{{ route('admins.pages.create') }}" class="flex items-center gap-2 px-4 py-2 hover:bg-indigo-600 hover:text-white transition">
                            <i class="fa-solid fa-file w-4"></i> {{ __('Page') }}
                        </a>
                        <a href="{{ route('system.users.create') }}" class="flex items-center gap-2 px-4 py-2 hover:bg-indigo-600 hover:text-white transition">
                            <i class="fa-solid fa-user-plus w-4"></i> {{ __('User') }}
                        </a>
                    </div>
                </div>

                {{-- এডিট বাটন --}}
                <a href="{{ $editItemUrl }}" class="flex items-center gap-1.5 px-2 sm:px-3 h-full text-sky-400 hover:text-white hover:bg-sky-600 transition-all">
                    <i class="fa-solid {{ $editIcon }}"></i>
                    <span class="hidden lg:inline-block">{{ $editItemLabel }}</span>
                </a>
            </div>

            {{-- ডান পাশের সেকশন (User) --}}
            <div class="flex items-center h-full">
                <div class="relative group h-full border-l border-slate-800">
                    <button type="button" class="flex items-center gap-2 px-3 sm:px-4 h-full hover:bg-white/10 transition-all">
                        <div class="w-6 h-6 rounded-full bg-indigo-500 flex items-center justify-center text-[10px] font-bold text-white uppercase shadow-inner">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <span class="hidden sm:inline-block">{{ __('Hi, :name', ['name' => auth()->user()->name]) }}</span>
                        <i class="fa-solid fa-chevron-down text-[10px] opacity-50 group-hover:rotate-180 transition-transform"></i>
                    </button>
                    <div class="absolute right-0 top-full w-48 py-2 bg-slate-900 border border-slate-700 shadow-2xl rounded-b-md opacity-0 invisible group-hover:visible group-hover:opacity-100 transition-all transform origin-top scale-95 group-hover:scale-100">
                        {{-- মোবাইলে ইউজার ইনফো দেখার জন্য ছোট সেকশন --}}
                        <div class="px-4 py-2 border-b border-slate-800 mb-1 sm:hidden">
                            <p class="text-white font-bold truncate">{{ auth()->user()->name }}</p>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 hover:bg-slate-800 transition">
                            <i class="fa-solid fa-user-gear w-4 text-slate-400"></i> {{ __('Edit Profile') }}
                        </a>
                        <div class="h-px bg-slate-700 my-1 mx-2"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center gap-2 w-full px-4 py-2 text-left text-red-400 hover:bg-red-500 hover:text-white transition">
                                <i class="fa-solid fa-arrow-right-from-bracket w-4"></i> {{ __('Logout') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endauth
