@extends('install.layout')

@section('title', 'Welcome')

@section('content')
    <div class="space-y-6">
        <div>
            <h2 class="text-2xl font-semibold text-slate-900">Welcome</h2>
            <p class="mt-2 text-sm text-slate-600">
                Before getting started, we need some information on the database. You will need to know the
                following items before proceeding.
            </p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <label class="text-sm font-semibold text-slate-700" for="language">Language</label>
            <select class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200" id="language" name="language">
                <option value="en">English - en</option>
            </select>
        </div>
        <div class="flex flex-wrap gap-3">
            <a class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700" href="{{ route('install.requirements') }}">
                Let's go
            </a>
        </div>
    </div>
@endsection
