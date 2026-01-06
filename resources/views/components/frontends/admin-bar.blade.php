@auth
    <div class="bg-slate-900 text-white text-sm">
        <div class="container flex flex-wrap items-center justify-between gap-2 px-4 py-2">
            <div class="flex items-center gap-3">
                <span class="font-semibold uppercase tracking-wide">{{ __('Admin Bar') }}</span>
                <span class="text-slate-300">{{ __('Hello, :name', ['name' => auth()->user()->name]) }}</span>
            </div>
            <div class="flex items-center gap-4">
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
