@extends('install.layout')

@section('title', 'Create Account')

@section('content')
    <div class="space-y-6">
        <div>
            <h2 class="text-2xl font-semibold text-slate-900">Create Administrator Account</h2>
            <p class="mt-2 text-sm text-slate-600">Create the first administrator user. You will use this account to log in.</p>
        </div>

        <form class="space-y-6" method="post" action="{{ route('install.account.store') }}">
            @csrf
            <div class="grid gap-6 sm:grid-cols-2">
                <div>
                    <label class="text-sm font-semibold text-slate-700" for="name">Full name</label>
                    <input class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200" id="name" name="name" type="text" value="{{ old('name') }}" required>
                    @error('name')
                        <span class="mt-2 block text-sm text-rose-600">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-700" for="username">Username</label>
                    <input class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200" id="username" name="username" type="text" value="{{ old('username') }}" required>
                    @error('username')
                        <span class="mt-2 block text-sm text-rose-600">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-700" for="email">Email address</label>
                    <input class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200" id="email" name="email" type="email" value="{{ old('email') }}" required>
                    @error('email')
                        <span class="mt-2 block text-sm text-rose-600">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-700" for="password">Password</label>
                    <input class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200" id="password" name="password" type="password" required>
                    @error('password')
                        <span class="mt-2 block text-sm text-rose-600">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-700" for="password_confirmation">Confirm password</label>
                    <input class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200" id="password_confirmation" name="password_confirmation" type="password" required>
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                <a class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:text-slate-900" href="{{ route('install.environment') }}">Back</a>
                <button class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700" type="submit">Create Account</button>
            </div>
        </form>
    </div>
@endsection
