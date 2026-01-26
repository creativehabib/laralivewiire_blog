<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Install') | {{ config('app.name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}"/>
    @vite(['resources/css/app.css'])
    @yield('style')
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
<div class="min-h-screen px-4 py-10">
    <div class="mx-auto flex w-full max-w-5xl flex-col overflow-hidden rounded-3xl bg-white shadow-2xl">
        <div class="flex flex-col gap-6 bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-700 px-6 py-8 text-white sm:flex-row sm:items-center sm:justify-between sm:px-10">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.32em] text-blue-100">Installation Wizard</p>
                <h1 class="mt-2 text-2xl font-bold sm:text-3xl">{{ config('app.name') }}</h1>
                <p class="mt-2 max-w-xl text-sm text-blue-100">
                    Follow the guided steps to configure your database, environment, and administrator account.
                </p>
            </div>
            <div class="inline-flex items-center gap-3 rounded-full bg-white/15 px-4 py-2 text-sm font-semibold shadow-sm">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white/20 text-base">
                    <i class="fa-solid fa-bolt"></i>
                </span>
                <span>Setup in progress</span>
            </div>
        </div>
        <ul class="grid gap-3 border-b border-slate-200 bg-slate-50 px-6 py-6 sm:grid-cols-2 lg:grid-cols-6 lg:px-10">
            @php
                $steps = [
                    'welcome' => ['label' => 'Welcome', 'icon' => 'fa-flag-checkered'],
                    'requirements' => ['label' => 'Requirements', 'icon' => 'fa-list-check'],
                    'permissions' => ['label' => 'Permissions', 'icon' => 'fa-shield-halved'],
                    'environment' => ['label' => 'Environment', 'icon' => 'fa-gear'],
                    'account' => ['label' => 'Account', 'icon' => 'fa-user-shield'],
                    'final' => ['label' => 'Finish', 'icon' => 'fa-circle-check'],
                ];
            @endphp
            @foreach ($steps as $key => $config)
                @php
                    $isActive = $step === $key;
                    $isDone = array_search($step, array_keys($steps), true) > array_search($key, array_keys($steps), true);
                @endphp
                <li class="flex items-center gap-3 rounded-2xl border px-4 py-3 text-sm font-medium transition {{ $isActive ? 'border-blue-500 bg-white text-blue-700 shadow-md shadow-blue-100' : ($isDone ? 'border-slate-200 bg-white text-slate-700' : 'border-slate-200 bg-white/70 text-slate-400') }}">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl {{ $isActive ? 'bg-blue-100 text-blue-700' : ($isDone ? 'bg-slate-100 text-slate-700' : 'bg-white text-slate-400') }}">
                        <i class="fa-solid {{ $config['icon'] }}"></i>
                    </span>
                    <span>{{ $config['label'] }}</span>
                </li>
            @endforeach
        </ul>
        <div class="px-6 py-8 sm:px-10 sm:py-10">
            @yield('content')
        </div>
    </div>
</div>
</body>
</html>
