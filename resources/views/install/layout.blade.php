<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Install') | {{ config('app.name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}"/>
    <link href="{{ asset('installer/css/style.css') }}" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet"/>
    @yield('style')
</head>
<body>
<div class="installer">
    <div class="installer__panel">
        <div class="installer__header">
            <div>
                <p class="installer__label">Installation Wizard</p>
                <h1 class="installer__title">{{ config('app.name') }}</h1>
            </div>
            <div class="installer__step-badge">
                <i class="fa-solid fa-bolt"></i>
                <span>Setup</span>
            </div>
        </div>
        <ul class="installer__steps">
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
                <li class="installer__step {{ $isActive ? 'is-active' : '' }} {{ $isDone ? 'is-done' : '' }}">
                    <span class="installer__step-icon">
                        <i class="fa-solid {{ $config['icon'] }}"></i>
                    </span>
                    <span class="installer__step-text">{{ $config['label'] }}</span>
                </li>
            @endforeach
        </ul>
        <div class="installer__body">
            @yield('content')
        </div>
    </div>
</div>
</body>
</html>
