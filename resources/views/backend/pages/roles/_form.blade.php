@php
    $selectedPermissions = old('permissions', isset($role) ? $role->permissions->pluck('name')->all() : []);
@endphp

<div class="space-y-8">

    {{-- Role Information Card --}}
    <div class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-sm p-6">
        <h2 class="text-base font-semibold text-slate-900 dark:text-slate-100">Role Information</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">
            Define the role name and assign permissions below.
        </p>

        {{-- Role Name --}}
        <div class="mb-5">
            <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">
                Role Name <span class="text-red-500">*</span>
            </label>

            <input
                type="text"
                name="name"
                id="name"
                value="{{ old('name', $role->name ?? '') }}"
                required
                class="block w-full h-11 px-3 rounded-lg border border-slate-300 dark:border-slate-600
                       bg-white dark:bg-slate-800 text-sm
                       text-slate-900 dark:text-slate-100
                       placeholder-slate-400 dark:placeholder-slate-500
                       focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500
                       transition"
                placeholder="Administrator, Editor, Author..."
            >

            @error('name')
            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Permission Assignment Card --}}
    <div class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-sm p-6">
        <h2 class="text-base font-semibold text-slate-900 dark:text-slate-100">Assign Permissions</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">
            Select the permissions that should be granted to this role.
        </p>

        {{-- All Permissions --}}
        <div class="py-4 rounded-lg bg-slate-50 dark:bg-slate-800/40 px-4 flex items-center gap-3">
            <input
                type="checkbox"
                id="checkPermissionAll"
                class="h-5 w-5 rounded border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900
                       text-indigo-600 focus:ring-indigo-500"
            >
            <label for="checkPermissionAll" class="text-sm font-medium text-slate-900 dark:text-slate-100">
                Select all permissions
            </label>
        </div>

        {{-- Groups --}}
        <div class="mt-6 space-y-6">
            @foreach($groupedPermissions as $groupName => $permissions)

                <div class="rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/40 p-5">
                    {{-- Group Header --}}
                    <div class="flex justify-between items-center mb-3">
                        <div class="flex items-center gap-3">
                            <input
                                type="checkbox"
                                class="group-checkbox h-5 w-5 rounded border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-indigo-600 focus:ring-indigo-500"
                                id="checkGroup-{{ Str::slug($groupName) }}"
                                data-group="{{ Str::slug($groupName) }}"
                            >
                            <label class="text-sm font-semibold text-slate-900 dark:text-slate-100">
                                {{ $groupName }}
                            </label>
                        </div>
                    </div>

                    {{-- Permissions Items --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 mt-3"
                         data-group-container="{{ Str::slug($groupName) }}">

                        @foreach($permissions->sortBy('name') as $permission)
                            <label
                                for="checkPermission-{{ $permission->id }}"
                                class="flex items-center gap-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700
                                       px-3 py-2 rounded-md cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-800 transition"
                            >
                                <input
                                    type="checkbox"
                                    class="permission-checkbox h-4 w-4 rounded border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-indigo-600 focus:ring-indigo-500"
                                    name="permissions[]"
                                    id="checkPermission-{{ $permission->id }}"
                                    value="{{ $permission->name }}"
                                    {{ in_array($permission->name, $selectedPermissions) ? 'checked' : '' }}
                                >
                                <span class="text-sm text-slate-700 dark:text-slate-200">
                                    {{ $permission->name }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>

            @endforeach
        </div>
    </div>

    {{-- Footer Actions --}}
    <div class="sticky bottom-0 bg-white dark:bg-slate-900 py-4 border-t border-slate-200 dark:border-slate-700 flex gap-3">
        <button
            type="submit"
            class="inline-flex items-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm
                   hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900"
        >
            {{ $submitLabel ?? __('Save Role') }}
        </button>

        <a
            href="{{ route('system.roles.index') }}"
            class="inline-flex items-center rounded-lg border border-slate-300 dark:border-slate-600
                   bg-white dark:bg-slate-900 px-5 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-100
                   hover:bg-slate-50 dark:hover:bg-slate-800 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900"
        >
            Cancel
        </a>
    </div>

</div>
