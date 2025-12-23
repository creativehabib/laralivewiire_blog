<x-layouts.app :title="__('Edit User')">

    {{-- Header --}}
    <header class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div class="space-y-1">
            <div class="inline-flex items-center gap-2 rounded-full bg-slate-100 dark:bg-slate-800 px-3 py-1">
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                <span class="text-[11px] font-medium uppercase tracking-[0.12em] text-slate-600 dark:text-slate-300">
                    User Management
                </span>
            </div>

            <h1 class="text-2xl font-semibold text-slate-900 dark:text-slate-100">
                Edit user
            </h1>

            <p class="text-sm text-slate-500 dark:text-slate-400">
                Update profile details, credentials and role assignment.
            </p>
        </div>

        <a
            href="{{ route('system.users.index') }}"
            class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-800
                   dark:text-indigo-400 dark:hover:text-indigo-300"
        >
            <i class="fa-solid fa-arrow-left mr-1 text-xs"></i>
            Back to users
        </a>
    </header>

    {{-- Content --}}
    <div class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-sm">
        <div class="p-6 md:p-7">
            <form action="{{ route('system.users.update', $user) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')

                {{-- Basic Info --}}
                <section class="space-y-4">
                    <div>
                        <h2 class="text-sm font-semibold text-slate-900 dark:text-slate-100">
                            Basic information
                        </h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            These details are used to identify the user in the system.
                        </p>
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">
                        {{-- Name --}}
                        <div>
                            <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">
                                Name
                            </label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name', $user->name) }}"
                                class="block w-full h-11 rounded-lg border border-slate-300 dark:border-slate-600
                                       bg-white dark:bg-slate-800 px-3 text-sm
                                       text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500
                                       focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/70 focus:ring-offset-0
                                       transition
                                       @error('name') border-red-500 dark:border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                                placeholder="Enter full name"
                            >
                            @error('name')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">
                                Email
                            </label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email', $user->email) }}"
                                class="block w-full h-11 rounded-lg border border-slate-300 dark:border-slate-600
                                       bg-white dark:bg-slate-800 px-3 text-sm
                                       text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500
                                       focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/70 focus:ring-offset-0
                                       transition
                                       @error('email') border-red-500 dark:border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                                placeholder="you@example.com"
                            >
                            @error('email')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">
                                {{ $message }}
                            </p>
                            @enderror
                        </div>
                    </div>
                </section>

                <hr class="border-slate-200 dark:border-slate-800">

                {{-- Security --}}
                <section class="space-y-4">
                    <div>
                        <h2 class="text-sm font-semibold text-slate-900 dark:text-slate-100">
                            Security
                        </h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            Change the user password if you need to reset access. Leave blank to keep the current password.
                        </p>
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">
                        {{-- Password --}}
                        <div>
                            <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">
                                Password
                                <span class="text-[11px] text-slate-400 dark:text-slate-500">(leave blank to keep current)</span>
                            </label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="block w-full h-11 rounded-lg border border-slate-300 dark:border-slate-600
                                       bg-white dark:bg-slate-800 px-3 text-sm
                                       text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500
                                       focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/70 focus:ring-offset-0
                                       transition
                                       @error('password') border-red-500 dark:border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                                placeholder="New password"
                            >
                            @error('password')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        {{-- Confirm Password --}}
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">
                                Confirm password
                            </label>
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                class="block w-full h-11 rounded-lg border border-slate-300 dark:border-slate-600
                                       bg-white dark:bg-slate-800 px-3 text-sm
                                       text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500
                                       focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/70 focus:ring-offset-0
                                       transition"
                                placeholder="Re-enter new password"
                            >
                        </div>
                    </div>
                </section>

                <hr class="border-slate-200 dark:border-slate-800">

                {{-- Role --}}
                <section class="space-y-4">
                    <div>
                        <h2 class="text-sm font-semibold text-slate-900 dark:text-slate-100">
                            Role & access
                        </h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            Assign a primary role to control this user's permissions in the system.
                        </p>
                    </div>

                    <div class="max-w-md">
                        <label for="role" class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">
                            Assign role
                        </label>
                        <select
                            id="role"
                            name="role"
                            class="block w-full h-11 rounded-lg border border-slate-300 dark:border-slate-600
                                   bg-white dark:bg-slate-800 px-3 text-sm
                                   text-slate-900 dark:text-slate-100
                                   focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/70 focus:ring-offset-0 transition"
                        >
                            <option value="" class="text-slate-400">Select a role</option>
                            @foreach ($roles as $role)
                                <option
                                    value="{{ $role->name }}"
                                    {{ $user->hasRole($role->name) || old('role') === $role->name ? 'selected' : '' }}
                                >
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">
                            {{ $message }}
                        </p>
                        @enderror
                    </div>
                </section>

                {{-- Actions --}}
                <div class="mt-4 flex flex-wrap items-center gap-3 border-t border-slate-200 dark:border-slate-800 pt-4">
                    <button
                        type="submit"
                        class="inline-flex items-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm
                               hover:bg-indigo-700
                               focus:outline-none focus:ring-2 focus:ring-indigo-500
                               focus:ring-offset-2 focus:ring-offset-slate-50 dark:focus:ring-offset-slate-900"
                    >
                        <i class="fa-regular fa-floppy-disk mr-1.5 text-xs"></i>
                        Update user
                    </button>

                    <a
                        href="{{ route('system.users.index') }}"
                        class="inline-flex items-center rounded-lg border border-slate-300 dark:border-slate-600
                               bg-white dark:bg-slate-900 px-5 py-2.5 text-sm font-semibold
                               text-slate-700 dark:text-slate-100
                               hover:bg-slate-50 dark:hover:bg-slate-800
                               focus:outline-none focus:ring-2 focus:ring-indigo-500
                               focus:ring-offset-2 focus:ring-offset-slate-50 dark:focus:ring-offset-slate-900"
                    >
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
