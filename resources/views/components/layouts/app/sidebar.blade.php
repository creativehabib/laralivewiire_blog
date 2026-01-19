@php
    $adminLocale = setting('admin_language', app()->getLocale());
    $adminDirection = setting('admin_language_direction', 'ltr');
    $adminTitle = setting('admin_title', config('app.name'));
    $adminLogo = setting('admin_logo');
    $adminContainerWidth = setting('admin_container_width', 'default');
    $adminContainerClass = match ($adminContainerWidth) {
        'large' => 'max-w-screen-2xl',
        'full' => 'max-w-none',
        default => 'max-w-7xl',
    };
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', $adminLocale) }}" dir="{{ $adminDirection }}" class="dark admin-appearance">
<head>
    @include('partials.head')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.css"/>
    <style>
        .dd { max-width: 100%; }
        .dd3-handle {
            height: 40px; width: 40px; background: #4f46e5; border-radius: 0.5rem; color: #fff;
            display: flex; align-items: center; justify-content: center; cursor: move;
        }
        .dd3-handle:before { content: '\2630'; font-size: 18px; }
        .dd3-content {
            margin: 0.375rem 0; padding: 0.75rem 1rem; border: 1px solid #e5e7eb;
            border-radius: 0.5rem; background: #ffffff; box-shadow: 0 1px 2px rgba(15, 23, 42, 0.08);
        }
        .menu-picker {
            max-height: 240px; overflow-y: auto; border: 1px solid #e5e7eb;
            border-radius: 0.5rem; padding: 0.75rem; background: #f9fafb;
        }
        .menu-picker .form-check { margin-bottom: 0.5rem; }
        .menu-picker .form-check:last-child { margin-bottom: 0; }
        .dd-handle {
            display: block; padding: 5px 10px; color: #333; cursor: move !important;
            text-decoration: none; font-weight: 700; border: none !important;
            background: none !important; border-radius: 3px; box-sizing: border-box;
        }
        .dd3-content { height: auto; padding: 0; display: block; }
        .dd3-content .menu-item-header { flex-wrap: wrap; }
        .dd3-content .drag-handle { cursor: move; flex: 1; user-select: none; }
        .dd3-content .drag-handle:active { cursor: grabbing; }
        .dd-placeholder {
            background: #f2f2f2; border: 1px dashed #b6bcbf; box-sizing: border-box;
            min-height: 50px; margin: 5px 0;
        }
        .dd-list { list-style: none; padding-left: 0; }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen bg-white dark:bg-slate-800">

<a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:rounded-md focus:bg-white focus:px-4 focus:py-2 focus:text-slate-900 focus:shadow-lg dark:focus:bg-slate-800 dark:focus:text-white">
    {{ __('Skip to main content') }}
</a>

<div class="min-h-screen flex">
    {{-- ============= SIDEBAR ============= --}}
    <flux:sidebar sticky collapsible breakpoint="0" class="bg-slate-50 dark:bg-slate-900 border-r border-slate-200 dark:border-slate-700">

        {{-- HEADER --}}
        <flux:sidebar.header>
            <flux:sidebar.brand
                href="{{ route('dashboard') }}"
                logo="{{ $adminLogo ?: 'https://fluxui.dev/img/demo/logo.png' }}"
                logo:dark="{{ $adminLogo ?: 'https://fluxui.dev/img/demo/dark-mode-logo.png' }}"
                name="{{ $adminTitle }}"
            />
            <flux:sidebar.collapse class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2" />
        </flux:sidebar.header>

        {{-- NAVIGATION --}}
        <flux:sidebar.nav>

            {{-- Dashboard --}}
            <flux:sidebar.item
                icon="home"
                :href="route('dashboard')"
                :current="request()->routeIs('dashboard')"
                tooltip="{{ __('Dashboard') }}"
                wire:navigate
            >
                {{ __('Dashboard') }}
            </flux:sidebar.item>

            {{-- Pages --}}
            @can('page.view')
                <flux:sidebar.item
                    icon="document-text"
                    :href="route('admins.pages.index')"
                    :current="request()->routeIs('admins.pages.*')"
                    tooltip="{{ __('Pages') }}"
                    wire:navigate
                >
                    {{ __('Pages') }}
                </flux:sidebar.item>
            @endcan

            {{-- Blog Group --}}
            @canany(['post.view', 'category.view', 'tags.view'])
                <flux:sidebar.group
                    heading="{{ __('Blog') }}"
                    icon="newspaper"
                    expandable
                    class="grid"
                    :expanded="request()->routeIs('blogs.*')"
                >
                    @can('post.view')
                        <flux:sidebar.item
                            icon="pencil-square"
                            :href="route('blogs.posts.index')"
                            :current="request()->routeIs('blogs.posts*')"
                            tooltip="{{ __('Posts') }}"
                            wire:navigate
                        >
                            {{ __('Posts') }}
                        </flux:sidebar.item>
                    @endcan

                    @can('category.view')
                        <flux:sidebar.item
                            icon="folder"
                            :href="route('blogs.categories.index')"
                            :current="request()->routeIs('blogs.categories.*')"
                            tooltip="{{ __('Categories') }}"
                            wire:navigate
                        >
                            {{ __('Categories') }}
                        </flux:sidebar.item>
                    @endcan

                    @can('tags.view')
                        <flux:sidebar.item
                            icon="hashtag"
                            :href="route('blogs.tags.index')"
                            :current="request()->routeIs('blogs.tags.*')"
                            tooltip="{{ __('Tags') }}"
                            wire:navigate
                        >
                            {{ __('Tags') }}
                        </flux:sidebar.item>
                    @endcan

                </flux:sidebar.group>
            @endcanany

            <livewire:admin.comment-notifications />

            {{-- Media --}}
            @can('media.view')
                <flux:sidebar.item
                    icon="photo"
                    :href="route('media')"
                    :current="request()->routeIs('media')"
                    tooltip="{{ __('Media') }}"
                    wire:navigate
                >
                    {{ __('Media') }}
                </flux:sidebar.item>
            @endcan


        {{--Appearance--}}
        @canany(['menu.view', 'setting.view'])
            <flux:sidebar.group
                heading="{{ __('Appearance') }}"
                icon="swatch"
                expandable
                class="grid"
                :expanded="request()->routeIs('appearance.*')"
            >
                @can('setting.view')
                    <flux:sidebar.item
                        icon="paint-brush"
                        :href="route('appearance.admin-appearance')"
                        :current="request()->routeIs('appearance.admin-appearance')"
                        tooltip="{{ __('Admin appearance') }}"
                        wire:navigate
                    >
                        {{ __('Admin appearance') }}
                    </flux:sidebar.item>
                @endcan

                @can('menu.view')
                    <flux:sidebar.item
                        icon="bars-3"
                        :href="route('appearance.menus.index')"
                        :current="request()->routeIs('appearance.menus.*')"
                        tooltip="{{ __('Menus') }}"
                        wire:navigate
                    >
                        {{ __('Menus') }}
                    </flux:sidebar.item>
                @endcan

                @can('setting.view')
                    <flux:sidebar.item
                        icon="megaphone"
                        :href="route('appearance.ads-settings')"
                        :current="request()->routeIs('appearance.ads-settings')"
                        tooltip="{{ __('Ads Settings') }}"
                        wire:navigate
                    >
                        {{ __('Ads Settings') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item
                        icon="paint-brush"
                        :href="route('appearance.custom-css')"
                        :current="request()->routeIs('appearance.custom-css')"
                        tooltip="{{ __('Customs CSS') }}"
                        wire:navigate
                    >
                        {{ __('Customs CSS') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item
                        icon="code-bracket"
                        :href="route('appearance.custom-js')"
                        :current="request()->routeIs('appearance.custom-js')"
                        tooltip="{{ __('Customs JS') }}"
                        wire:navigate
                    >
                        {{ __('Customs JS') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item
                        icon="command-line"
                        :href="route('appearance.custom-html')"
                        :current="request()->routeIs('appearance.custom-html')"
                        tooltip="{{ __('Customs HTML') }}"
                        wire:navigate
                    >
                        {{ __('Customs HTML') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item
                        icon="document-magnifying-glass"
                        :href="route('appearance.robots')"
                        :current="request()->routeIs('appearance.robots')"
                        tooltip="{{ __('Robots.txt') }}"
                        wire:navigate
                    >
                        {{ __('Robots.txt') }}
                    </flux:sidebar.item>
                @endcan

            </flux:sidebar.group>
        @endcanany
        {{--Theme Option--}}
        @can('setting.view')
            <flux:sidebar.item
                icon="adjustments-horizontal"
                :href="route('theme.theme-options', ['as' => 'general'])"
                :current="request()->routeIs('theme.theme-options')"
                tooltip="{{ __('Theme Options') }}"
                wire:navigate
            >
                {{ __('Theme Options') }}
            </flux:sidebar.item>
        @endcan
        {{-- Settings Group --}}
        @can('setting.view')
            <flux:sidebar.group
                heading="{{ __('Settings') }}"
                icon="cog-6-tooth"
                expandable
                class="grid"
                :expanded="request()->routeIs('settings.*')"
            >
                <flux:sidebar.item
                    icon="adjustments-horizontal"
                    :href="route('settings.dynamic', 'general')"
                    :current="request()->routeIs('settings.dynamic', 'general')"
                    tooltip="{{ __('General Setting') }}"
                    wire:navigate
                >
                    {{ __('General Setting') }}
                </flux:sidebar.item>

                <flux:sidebar.item
                    icon="map"
                    :href="route('settings.sitemap')"
                    :current="request()->routeIs('settings.sitemap')"
                    tooltip="{{ __('Sitemap Setting') }}"
                    wire:navigate
                >
                    {{ __('Sitemap Setting') }}
                </flux:sidebar.item>

                <flux:sidebar.item
                    icon="server"
                    :href="route('settings.htaccess')"
                    :current="request()->routeIs('settings.htaccess')"
                    tooltip="{{ __('Htaccess') }}"
                    wire:navigate
                >
                    {{ __('Htaccess') }}
                </flux:sidebar.item>

                {{-- UPDATED ICONS BELOW --}}
                <flux:sidebar.item
                    icon="chat-bubble-left-right"
                    :href="route('settings.comments')"
                    :current="request()->routeIs('settings.comments')"
                    tooltip="{{ __('Comments') }}"
                    wire:navigate
                >
                    {{ __('Comments') }}
                </flux:sidebar.item>

            </flux:sidebar.group>
        @endcan

        {{--System Settings--}}
        @canany(['setting.view', 'role.view', 'permission.view', 'user.view'])
            <flux:sidebar.group
                heading="{{ __('System Settings') }}"
                icon="cpu-chip"
                expandable
                class="grid"
                :expanded="request()->routeIs('system.*')"
            >
                @can('setting.view')
                    <flux:sidebar.item
                        icon="circle-stack"
                        :href="route('system.cacheManagement')"
                        :current="request()->routeIs('system.cacheManagement')"
                        tooltip="{{ __('Cache Management') }}"
                        wire:navigate
                    >
                        {{ __('Cache Management') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item
                        icon="list-bullet"
                        :href="route('system.activity-logs')"
                        :current="request()->routeIs('system.activity-logs')"
                        tooltip="{{ __('Activity Logs') }}"
                        wire:navigate
                    >
                        {{ __('Activity Logs') }}
                    </flux:sidebar.item>
                @endcan

                @can('role.view')
                    <flux:sidebar.item
                        icon="lock-closed"
                        :href="route('system.roles.index')"
                        :current="request()->routeIs('system.roles.*')"
                        tooltip="{{ __('Role & Permissions') }}"
                        wire:navigate
                    >
                        {{ __('Role & Permissions') }}
                    </flux:sidebar.item>
                @endcan

                @can('permission.view')
                    <flux:sidebar.item
                        icon="key"
                        :href="route('system.permissions.index')"
                        :current="request()->routeIs('system.permissions.*')"
                        tooltip="{{ __('Permissions') }}"
                        wire:navigate
                    >
                        {{ __('Permissions') }}
                    </flux:sidebar.item>
                @endcan

                @can('user.view')
                    <flux:sidebar.item
                        icon="users"
                        :href="route('system.users.index')"
                        :current="request()->routeIs('system.users.*')"
                        tooltip="{{ __('Users') }}"
                        wire:navigate
                    >
                        {{ __('Users') }}
                    </flux:sidebar.item>
                @endcan
            </flux:sidebar.group>
        @endcanany

        </flux:sidebar.nav>

        <flux:spacer />

        {{-- SECONDARY LINKS --}}
        <flux:sidebar.nav>
            <flux:sidebar.item
                icon="globe-alt"
                :href="route('home')"
                :current="request()->routeIs('home')"
                target="_blank"
                tooltip="{{ __('Visit Website') }}"
            >
                {{ __('Visit Website') }}
            </flux:sidebar.item>
        </flux:sidebar.nav>

        {{-- USER MENU --}}
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
                                    <span class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>
                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                            {{ __('Settings') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full" data-test="logout-button">
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
        <div class="p-4 sm:p-6 lg:p-8 mx-auto w-full {{ $adminContainerClass }}">
            {{ $slot }}
        </div>
    </main>
</div>

@if($adminBodyJs = setting('admin_body_js'))
    {!! $adminBodyJs !!}
@endif

@include('mediamanager::includes.media-modal')
@fluxScripts
@mediaScripts

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{ asset('ckeditor/ckeditor.js') }}" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.js" referrerpolicy="no-referrer"></script>

<script>
    // Nestable Logic
    const initMenuNestable = () => {
        const $el = $('#menuNestable');
        if (!$el.length || typeof $el.nestable !== 'function') return;

        if ($el.data('nestable')) $el.nestable('destroy');

        const serializeItems = (items) => {
            return items.map(item => {
                const children = Array.isArray(item.children) ? serializeItems(item.children) : [];
                return { id: item.id, ...(children.length ? { children } : {}) };
            });
        };

        $el.nestable({ maxDepth: 3, expandBtnHTML: '', collapseBtnHTML: '' })
            .on('change', function (e) {
                const list = e.length ? e : $(e.target);
                const structure = list.nestable('serialize');
                const serialized = Array.isArray(structure) ? serializeItems(structure) : [];
                Livewire.dispatch('menuOrderUpdated', { items: serialized });
            });
    };

    document.addEventListener('livewire:init', () => {
        initMenuNestable();
        Livewire.on('refreshNestable', () => setTimeout(initMenuNestable, 100));
    });

    // Permission Checkboxes Logic
    document.addEventListener('DOMContentLoaded', function () {
        const checkAll = document.getElementById('checkPermissionAll');
        if(!checkAll) return; // Prevent errors on pages without this element

        const groupCheckboxes = document.querySelectorAll('.group-checkbox');
        const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');

        checkAll.addEventListener('change', function () {
            permissionCheckboxes.forEach(cb => cb.checked = this.checked);
            groupCheckboxes.forEach(cb => cb.checked = this.checked);
        });

        groupCheckboxes.forEach(groupCheckbox => {
            groupCheckbox.addEventListener('change', function () {
                const group = this.getAttribute('data-group');
                document.querySelectorAll(`[data-group-container="${group}"] .permission-checkbox`)
                    .forEach(cb => cb.checked = this.checked);
                updateCheckAllState();
            });
        });

        permissionCheckboxes.forEach(permissionCheckbox => {
            permissionCheckbox.addEventListener('change', function () {
                const container = this.closest('.group-permissions-container');
                updateGroupState(container);
                updateCheckAllState();
            });
        });

        function updateGroupState(container) {
            if (!container) return;
            const group = container.getAttribute('data-group-container');
            if (!group) return;
            const groupCheckbox = document.querySelector(`.group-checkbox[data-group="${group}"]`);
            if (!groupCheckbox) return;
            const allInGroup = container.querySelectorAll('.permission-checkbox');
            const allChecked = container.querySelectorAll('.permission-checkbox:checked');

            groupCheckbox.checked = allInGroup.length > 0 && allInGroup.length === allChecked.length;
            groupCheckbox.indeterminate = allChecked.length > 0 && allChecked.length < allInGroup.length;
        }

        function updateCheckAllState() {
            const checkedCount = document.querySelectorAll('.permission-checkbox:checked').length;
            checkAll.checked = permissionCheckboxes.length > 0 && permissionCheckboxes.length === checkedCount;
            checkAll.indeterminate = checkedCount > 0 && checkedCount < permissionCheckboxes.length;
        }

        document.querySelectorAll('.group-permissions-container').forEach(container => {
            updateGroupState(container);
        });
        updateCheckAllState();
    });
</script>
@stack('scripts')

@if($adminFooterJs = setting('admin_footer_js'))
    {!! $adminFooterJs !!}
@endif
</body>
</html>
