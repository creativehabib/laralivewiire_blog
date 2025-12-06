<x-layouts.app :title="__('Role & Permission')">
    {{-- Header --}}
    <header class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">Create Role</h1>
        </div>

        <a
            href="{{ route('admin.roles.index') }}"
            class="text-sm text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300"
        >
            &larr; Back to roles
        </a>
    </header>

    {{-- Page Section --}}
    <div class="mt-6">
        <div class="border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 shadow-sm">
            <div class="p-6">
                <form action="{{ route('admin.roles.store') }}" method="POST">
                    @csrf
                    @include('backend.pages.roles._form', ['submitLabel' => __('Create Role')])
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
