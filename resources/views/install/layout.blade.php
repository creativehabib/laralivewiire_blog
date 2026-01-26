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
<body class="min-h-screen bg-gray-100 font-sans antialiased text-gray-800">
<div class="flex min-h-screen bg-gray-100 font-sans antialiased">
    <aside class="relative w-full max-w-sm bg-[#f0ebf8] p-10">
        @php
            $steps = [
                'welcome' => 'Welcome',
                'requirements' => 'Server Requirements',
                'environment' => 'Environment Settings',
                'account' => 'Create account',
                'license' => 'Activate License',
                'final' => 'Done',
            ];
            $activeStep = $step === 'permissions' ? 'requirements' : $step;
        @endphp
        <div class="relative z-10 flex flex-col space-y-8">
            @foreach ($steps as $key => $label)
                @php
                    $isActive = $activeStep === $key;
                    $isDone = array_search($activeStep, array_keys($steps), true) > array_search($key, array_keys($steps), true);
                    $circleClasses = $isActive
                        ? 'bg-blue-700 text-white'
                        : ($isDone ? 'bg-blue-100 text-blue-700' : 'bg-gray-300 text-gray-500');
                    $labelClasses = $isActive
                        ? 'text-gray-800 font-semibold'
                        : ($isDone ? 'text-gray-700 font-medium' : 'text-gray-400 font-medium');
                @endphp
                <div class="flex items-center space-x-4 {{ $isActive ? '' : 'opacity-80' }}">
                    <div class="z-20 flex h-10 w-10 items-center justify-center rounded-full {{ $circleClasses }} font-bold">
                        {{ array_search($key, array_keys($steps), true) + 1 }}
                    </div>
                    <span class="text-lg {{ $labelClasses }}">{{ $label }}</span>
                </div>
            @endforeach
            <div class="absolute left-5 top-4 -z-10 h-[85%] w-0.5 bg-gray-300"></div>
        </div>
    </aside>
    <main class="flex flex-1 flex-col bg-white">
        <div class="flex-1 p-12">
            @yield('content')
        </div>
        @hasSection('footer')
            <footer class="border-t border-gray-200 p-6">
                @yield('footer')
            </footer>
        @endif
    </main>
</div>
</body>
</html>
