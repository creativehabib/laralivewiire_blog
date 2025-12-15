<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    @include('partials.head')

    <!-- Nestable CSS -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.css"/>
    <style>
        .dd {
            max-width: 100%;
        }

        .dd3-handle {
            height: 40px;
            width: 40px;
            background: #4f46e5; /* Tailwind indigo-600 */
            border-radius: 0.5rem; /* rounded-lg */
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: move;
        }

        .dd3-handle:before {
            content: '\2630';
            font-size: 18px;
        }

        .dd3-content {
            margin: 0.375rem 0;
            padding: 0.75rem 1rem;
            border: 1px solid #e5e7eb; /* gray-200 */
            border-radius: 0.5rem; /* rounded-lg */
            background: #ffffff;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.08); /* subtle slate shadow */
        }

        .menu-picker {
            max-height: 240px;
            overflow-y: auto;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 0.75rem;
            background: #f9fafb; /* gray-50 */
        }

        .menu-picker .form-check {
            margin-bottom: 0.5rem;
        }

        .menu-picker .form-check:last-child {
            margin-bottom: 0;
        }
        .dd-handle {
            display: block;
            padding: 5px 10px;
            color: #333;
            cursor: move !important;
            text-decoration: none;
            font-weight: 700;
            border: none !important;
            background: none !important;
            border-radius: 3px;
            box-sizing: border-box;
        }
        .dd { max-width: 100%; }
        .dd3-content {
            height: auto;
            padding: 0;
            display: block;
        }
        .dd3-content .menu-item-header {
            flex-wrap: wrap;
        }
        .dd3-content .drag-handle {
            cursor: move;
            flex: 1;
            user-select: none;
        }
        .dd3-content .drag-handle:active {
            cursor: grabbing;
        }
        .dd-placeholder {
            background: #f2f2f2;
            border: 1px dashed #b6bcbf;
            box-sizing: border-box;
            min-height: 50px;
            margin: 5px 0;
        }
        .dd-list { list-style: none; padding-left: 0; }
    </style>
</head>
<body class="min-h-screen bg-white dark:bg-slate-800">

<a
    href="#main-content"
    class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:rounded-md focus:bg-white focus:px-4 focus:py-2 focus:text-slate-900 focus:shadow-lg dark:focus:bg-slate-800 dark:focus:text-white"
>
    {{ __('Skip to main content') }}
</a>

<div class="min-h-screen flex">
    {{-- ============= SIDEBAR ============= --}}
    <flux:sidebar sticky collapsible breakpoint="0" class="bg-slate-50 dark:bg-slate-900 border-r border-slate-200 dark:border-slate-700">
        {{-- HEADER: Logo + collapse --}}
        <flux:sidebar.header>
            <flux:sidebar.brand
                href="{{ route('dashboard') }}"
                logo="https://fluxui.dev/img/demo/logo.png"
                logo:dark="https://fluxui.dev/img/demo/dark-mode-logo.png"
                name="Laravel Livewire"
            />

            <flux:sidebar.collapse class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2" />
        </flux:sidebar.header>

        {{-- QUICK LINK --}}
        <flux:sidebar.nav>
            {{-- ড্যাশবোর্ড এবং মিডিয়া আইটেম --}}
            <flux:sidebar.item
                icon="home"
                :href="route('dashboard')"
                :current="request()->routeIs('dashboard')"
                tooltip="{{ __('Dashboard') }}"
                wire:navigate
            >
                {{ __('Dashboard') }}
            </flux:sidebar.item>

            <flux:sidebar.item
                icon="book-open"
                :href="route('admins.pages.index')"
                :current="request()->routeIs('admins.pages.*')"
                tooltip="{{ __('Pages') }}"
                wire:navigate
            >
                {{ __('Pages') }}
            </flux:sidebar.item>

            {{--Blog, Category, Tags--}}
            {{-- রুট admin.* সক্রিয় থাকলেই গ্রুপটি Expanded থাকবে (parent active) --}}
            <flux:sidebar.group
                heading="{{ __('Blog') }}"
                icon="book-open"
                expandable
                class="grid"
                :expanded="request()->routeIs('blogs.*')"
            >
                {{-- সাবমেনু আইটেম: Roles --}}
                <flux:sidebar.item
                    icon="users"
                    :href="route('blogs.posts.index')"
                    :current="request()->routeIs('blogs.posts*')"
                    tooltip="{{ __('Posts') }}"
                    wire:navigate
                >
                    {{ __('Posts') }}
                </flux:sidebar.item>

                <flux:sidebar.item
                    icon="home"
                    :href="route('blogs.categories.index')"
                    :current="request()->routeIs('blogs.categories.*')"
                    tooltip="{{ __('Categories') }}"
                    wire:navigate
                >
                    {{ __('Categories') }}
                </flux:sidebar.item>

                <flux:sidebar.item
                    icon="home"
                    :href="route('blogs.tags.index')"
                    :current="request()->routeIs('blogs.tags.*')"
                    tooltip="{{ __('Tags') }}"
                    wire:navigate
                >
                    {{ __('Tags') }}
                </flux:sidebar.item>


            </flux:sidebar.group>

            {{-- রুট admin.* সক্রিয় থাকলেই গ্রুপটি Expanded থাকবে (parent active) --}}
            <flux:sidebar.group
                heading="{{ __('Administration') }}"
                icon="book-open"
                expandable
                class="grid"
                :expanded="request()->routeIs('admin.*')"
            >
                {{-- সাবমেনু আইটেম: Roles --}}
                <flux:sidebar.item
                    icon="users"
                    :href="route('admin.roles.index')"
                    :current="request()->routeIs('admin.roles.*')"
                    tooltip="{{ __('Role & Permissions') }}"
                    wire:navigate
                >
                    {{ __('Role & Permissions') }}
                </flux:sidebar.item>

                {{-- সাবমেনু আইটেম: Permissions --}}
                <flux:sidebar.item
                    icon="book-open"
                    :href="route('admin.permissions.index')"
                    :current="request()->routeIs('admin.permissions.*')"
                    tooltip="{{ __('Permissions') }}"
                    wire:navigate
                >
                    {{ __('Permissions') }}
                </flux:sidebar.item>

                {{-- সাবমেনু আইটেম: Users --}}
                <flux:sidebar.item
                    icon="users"
                    :href="route('admin.users.index')"
                    :current="request()->routeIs('admin.users.*')"
                    tooltip="{{ __('Users') }}"
                    wire:navigate
                >
                    {{ __('Users') }}
                </flux:sidebar.item>

                {{-- সাবমেনু আইটেম: Menus --}}
                <flux:sidebar.item
                    icon="book-open"
                    :href="route('admin.menus.index')"
                    :current="request()->routeIs('admin.menus.*')"
                    tooltip="{{ __('Menus') }}"
                    wire:navigate
                >
                    {{ __('Menus') }}
                </flux:sidebar.item>
            </flux:sidebar.group>

            <flux:sidebar.item
                icon="book-open"
                :href="route('media')"
                :current="request()->routeIs('media')"
                tooltip="{{ __('Media') }}"
                wire:navigate
            >
                {{ __('Media') }}
            </flux:sidebar.item>

            <flux:sidebar.group
                heading="{{ __('Settings') }}"
                icon="square-3-stack-3d"
                expandable
                class="grid"
                :expanded="request()->routeIs('settings.dynamic')"
            >
{{--                <flux:sidebar.item--}}
{{--                    icon="home"--}}
{{--                    :href="route('settings.general')"--}}
{{--                    :current="request()->routeIs('settings.general')"--}}
{{--                    tooltip="{{ __('General Setting') }}"--}}
{{--                    wire:navigate--}}
{{--                >--}}
{{--                    {{ __('General Setting') }}--}}
{{--                </flux:sidebar.item>--}}

                <flux:sidebar.item
                    icon="home"
                    :href="route('settings.dynamic', 'general')"
                    :current="request()->routeIs('settings.dynamic', 'general')"
                    tooltip="{{ __('General Setting') }}"
                    wire:navigate
                >
                    {{ __('General Setting') }}
                </flux:sidebar.item>

{{--                <flux:sidebar.item--}}
{{--                    icon="home"--}}
{{--                    :href="route('settings.permalinks')"--}}
{{--                    :current="request()->routeIs('settings.permalinks')"--}}
{{--                    tooltip="{{ __('Permalinks') }}"--}}
{{--                    wire:navigate--}}
{{--                >--}}
{{--                    {{ __('Permalinks') }}--}}
{{--                </flux:sidebar.item>--}}

                <flux:sidebar.item
                    icon="home"
                    :href="route('settings.cacheManagement')"
                    :current="request()->routeIs('settings.cacheManagement')"
                    tooltip="{{ __('Cache Management') }}"
                    wire:navigate
                >
                    {{ __('Cache Management') }}
                </flux:sidebar.item>

                <flux:sidebar.item
                    icon="home"
                    :href="route('settings.sitemap')"
                    :current="request()->routeIs('settings.sitemap')"
                    tooltip="{{ __('Sitemap Setting') }}"
                    wire:navigate
                >
                    {{ __('Sitemap Setting') }}
                </flux:sidebar.item>

            </flux:sidebar.group>

        </flux:sidebar.nav>

        {{-- MAIN NAVIGATION --}}

        <flux:spacer />

        {{-- SECONDARY LINKS --}}
        <flux:sidebar.nav>
            <flux:sidebar.item
                icon="folder-git-2"
                href="https://github.com/laravel/livewire-starter-kit"
                target="_blank"
                tooltip="{{ __('Repository') }}"
            >
                {{ __('Repository') }}
            </flux:sidebar.item>

            <flux:sidebar.item
                icon="book-open-text"
                :href="route('home')"
                :current="request()->routeIs('home')"
                target="_blank"
                tooltip="{{ __('Visit Website') }}"
            >
                {{ __('Visit Website') }}
            </flux:sidebar.item>
        </flux:sidebar.nav>

        {{-- DESKTOP + MOBILE – দুই জায়গাতেই user menu দেখাতে চাই --}}
        <div class="mt-4">
            <flux:dropdown position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon:trailing="chevrons-up-down"
                    data-test="sidebar-menu-button"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                        <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                            <span
                                class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                            >
                                {{ auth()->user()->initials() }}
                            </span>
                        </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                            <span class="truncate font-semibold">
                                {{ auth()->user()->name }}
                            </span>
                                    <span class="truncate text-xs">
                                {{ auth()->user()->email }}
                            </span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item
                            :href="route('profile.edit')"
                            icon="cog"
                            wire:navigate
                        >
                            {{ __('Settings') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item
                            as="button"
                            type="submit"
                            icon="arrow-right-start-on-rectangle"
                            class="w-full"
                            data-test="logout-button"
                        >
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </div>
    </flux:sidebar>
    {{-- ============= /SIDEBAR ============= --}}

    {{-- ============= MAIN CONTENT ============= --}}
    <main id="main-content" tabindex="-1" class="flex-1 min-h-screen bg-white dark:bg-slate-800 focus:outline-none">
        <div class="p-4 sm:p-6 lg:p-8">
            {{ $slot }}
        </div>
    </main>
    {{-- ============= /MAIN CONTENT ============= --}}
</div>
@include('mediamanager::includes.media-modal')
@fluxScripts
@mediaScripts
<!-- jQuery (must load BEFORE nestable) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{ asset('ckeditor/ckeditor.js') }}" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.js" referrerpolicy="no-referrer"></script>
<script>
    const initMenuNestable = () => {
        const $el = $('#menuNestable');

        if (!$el.length || typeof $el.nestable !== 'function') {
            return;
        }

        if ($el.data('nestable')) {
            $el.nestable('destroy');
        }

        const serializeItems = (items) => {
            return items.map(item => {
                const children = Array.isArray(item.children) ? serializeItems(item.children) : [];

                return {
                    id: item.id,
                    ...(children.length ? { children } : {})
                };
            });
        };

        $el.nestable({
            maxDepth: 3,
            expandBtnHTML: '',
            collapseBtnHTML: ''
        }).on('change', function (e) {
            const list = e.length ? e : $(e.target);
            const structure = list.nestable('serialize');
            const serialized = Array.isArray(structure) ? serializeItems(structure) : [];
            Livewire.dispatch('menuOrderUpdated', { items: serialized });
        });
    };

    document.addEventListener('livewire:init', () => {
        initMenuNestable();

        Livewire.on('refreshNestable', () => {
            setTimeout(initMenuNestable, 100);
        });
    });

    document.addEventListener('livewire:init', () => {
        let nestableInstance = null;

        function initializeNestable() {
            if ($('#menuNestable').length === 0 || typeof $('#menuNestable').nestable !== 'function') {
                return;
            }

            if (nestableInstance) {
                nestableInstance.nestable('destroy');
            }

            nestableInstance = $('#menuNestable').nestable({
                maxDepth: 3
            });

            const serializeItems = (items) => {
                return items.map(item => {
                    const children = Array.isArray(item.children) ? serializeItems(item.children) : [];

                    return {
                        id: item.id,
                        ...(children.length ? { children } : {})
                    };
                });
            };

            nestableInstance.on('change', function (e) {
                const list = e.length ? e : $(e.target);
                const output = list.nestable('serialize');
                const serialized = Array.isArray(output) ? serializeItems(output) : [];

                Livewire.dispatch('menuOrderUpdated', { items: serialized });
            });
        }

        initializeNestable();

        Livewire.on('refreshNestable', () => {
            initializeNestable();
        });
    });
</script>
<script>

    // role & permission
    document.addEventListener('DOMContentLoaded', function () {
        const checkAll = document.getElementById('checkPermissionAll');
        const groupCheckboxes = document.querySelectorAll('.group-checkbox');
        const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');

        // "All Permissions" checkbox functionality
        checkAll.addEventListener('change', function () {
            permissionCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            groupCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // "Group" checkbox functionality
        groupCheckboxes.forEach(groupCheckbox => {
            groupCheckbox.addEventListener('change', function () {
                const group = this.getAttribute('data-group');
                const permissionsInGroup = document.querySelectorAll(
                    `[data-group-container="${group}"] .permission-checkbox`
                );
                permissionsInGroup.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateCheckAllState();
            });
        });

        // Individual permission checkbox functionality
        permissionCheckboxes.forEach(permissionCheckbox => {
            permissionCheckbox.addEventListener('change', function () {
                updateGroupCheckboxState(this);
                updateCheckAllState();
            });
        });

        function updateGroupCheckboxState(permissionCheckbox) {
            const container = permissionCheckbox.closest('.group-permissions-container');
            const group = container.getAttribute('data-group-container');
            const groupCheckbox = document.querySelector(`.group-checkbox[data-group="${group}"]`);
            const allInGroup = container.querySelectorAll('.permission-checkbox');
            const allCheckedInGroup = container.querySelectorAll('.permission-checkbox:checked');
            groupCheckbox.checked = allInGroup.length === allCheckedInGroup.length;
        }

        function updateCheckAllState() {
            checkAll.checked =
                permissionCheckboxes.length === document.querySelectorAll('.permission-checkbox:checked').length;
        }

        // Initial state on page load
        groupCheckboxes.forEach(groupCheckbox => {
            const group = groupCheckbox.getAttribute('data-group');
            const container = document.querySelector(`[data-group-container="${group}"]`);
            const allInGroup = container.querySelectorAll('.permission-checkbox');
            const allCheckedInGroup = container.querySelectorAll('.permission-checkbox:checked');
            groupCheckbox.checked = allInGroup.length > 0 && allInGroup.length === allCheckedInGroup.length;
        });
        updateCheckAllState();
    });
</script>
@stack('scripts')
</body>
</html>
