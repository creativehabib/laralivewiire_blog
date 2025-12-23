<x-layouts.app :title="__('Create Permission')">

    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">Create New Permission</h1>
    </div>

    {{-- Card --}}
    <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm">
        <div class="p-6">

            <form action="{{ route('system.permissions.store') }}" method="POST">
                @csrf

                {{-- Permission Name --}}
                <div class="mb-5">
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                        Permission Name
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="e.g., post.create"
                        class="block w-full h-11 px-3 rounded-lg border border-gray-300 bg-white dark:bg-gray-800
                               text-sm text-gray-900 dark:text-gray-100 placeholder-gray-400
                               focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1
                               @error('name') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                    >

                    @error('name')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                {{-- Group Name --}}
                <div class="mb-5">
                    <label for="group_name" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                        Group Name
                    </label>

                    <input
                        type="text"
                        id="group_name"
                        name="group_name"
                        value="{{ old('group_name') }}"
                        placeholder="e.g., Post"
                        class="block w-full h-11 px-3 rounded-lg border border-gray-300 bg-white dark:bg-gray-800
                               text-sm text-gray-900 dark:text-gray-100 placeholder-gray-400
                               focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1
                               @error('group_name') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                    >

                    @error('group_name')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                {{-- Buttons --}}
                <div class="flex items-center gap-3">
                    <button
                        type="submit"
                        class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm
                               hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        Create Permission
                    </button>

                    <a
                        href="{{ route('system.permissions.index') }}"
                        class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2
                               text-sm font-semibold text-gray-700 hover:bg-gray-50
                               dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700
                               focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        Cancel
                    </a>
                </div>

            </form>

        </div>
    </div>

</x-layouts.app>
