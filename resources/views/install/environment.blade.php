@extends('install.layout')

@section('title', 'Environment')

@section('content')
    <div class="space-y-6">
        <div>
            <h2 class="text-2xl font-semibold text-slate-900">Environment Settings</h2>
            <p class="mt-2 text-sm text-slate-600">Fill in your application and database settings.</p>
        </div>

        <form class="space-y-6" method="post" action="{{ route('install.environment.save') }}">
            @csrf
            <div class="grid gap-6 sm:grid-cols-2">
                <div>
                    <label class="text-sm font-semibold text-slate-700" for="app_name">Application name</label>
                    <input class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200" id="app_name" name="app_name" type="text" value="{{ old('app_name', $defaults['app_name']) }}" required>
                    @error('app_name')
                        <span class="mt-2 block text-sm text-rose-600">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-700" for="app_url">Application URL</label>
                    <input class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200" id="app_url" name="app_url" type="url" value="{{ old('app_url', $defaults['app_url']) }}" required>
                    @error('app_url')
                        <span class="mt-2 block text-sm text-rose-600">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-700" for="db_connection">Database driver</label>
                    <input class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200" id="db_connection" name="db_connection" type="text" value="{{ old('db_connection', $defaults['db_connection']) }}" required>
                    @error('db_connection')
                        <span class="mt-2 block text-sm text-rose-600">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-700" for="db_host">Database host</label>
                    <input class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200" id="db_host" name="db_host" type="text" value="{{ old('db_host', $defaults['db_host']) }}" required>
                    @error('db_host')
                        <span class="mt-2 block text-sm text-rose-600">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-700" for="db_port">Database port</label>
                    <input class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200" id="db_port" name="db_port" type="text" value="{{ old('db_port', $defaults['db_port']) }}" required>
                    @error('db_port')
                        <span class="mt-2 block text-sm text-rose-600">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-700" for="db_database">Database name</label>
                    <input class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200" id="db_database" name="db_database" type="text" value="{{ old('db_database', $defaults['db_database']) }}" required>
                    @error('db_database')
                        <span class="mt-2 block text-sm text-rose-600">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-700" for="db_username">Database username</label>
                    <input class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200" id="db_username" name="db_username" type="text" value="{{ old('db_username', $defaults['db_username']) }}" required>
                    @error('db_username')
                        <span class="mt-2 block text-sm text-rose-600">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-700" for="db_password">Database password</label>
                    <input class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200" id="db_password" name="db_password" type="password" value="{{ old('db_password', $defaults['db_password']) }}">
                    @error('db_password')
                        <span class="mt-2 block text-sm text-rose-600">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                <a class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:text-slate-900" href="{{ route('install.permissions') }}">Back</a>
                <button class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700" type="submit">Save &amp; Install</button>
            </div>
        </form>
    </div>
@endsection
