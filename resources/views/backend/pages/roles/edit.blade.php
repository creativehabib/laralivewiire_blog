<x-layouts.app :title="__('Role & Permission')">
    {{-- Header --}}
    <header class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">Edit Role</h1>
        </div>

        <a href="{{ route('system.roles.index') }}"
            class="text-sm text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300"
        >
            &larr; Back to roles
        </a>
    </header>

    <div class="page-section">
        <div class="card card-fluid">
            <div class="card-body">
                <form action="{{ route('system.roles.update', $role) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @include('backend.pages.roles._form', ['submitLabel' => __('Update Role')])
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
