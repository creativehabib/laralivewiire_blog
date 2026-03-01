<div wire:init="loadTimes" class="bg-white dark:bg-slate-800 rounded-xl border border-emerald-100 dark:border-slate-700 shadow-sm p-4">

    <div class="flex items-start justify-between gap-3">
        <div>
            <p class="text-sm font-semibold text-emerald-600 dark:text-emerald-400">আজকের সেহরি ও ইফতার</p>

            <p class="text-lg font-semibold text-slate-900 dark:text-white">
                {{ $divisions[$selectedDivision] }}
            </p>

            <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">
                আজ {{ $this->ramadanDay() }} রমজান
            </p>

            <p class="text-xs text-slate-500 dark:text-slate-400">
                {{ now('Asia/Dhaka')->locale('bn')->translatedFormat('j F, l') }}
            </p>
        </div>

        <select wire:model.live="selectedDivision"
                class="w-36 rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-900 px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
            @foreach($divisions as $key => $name)
                <option value="{{ $key }}">{{ $name }}</option>
            @endforeach
        </select>
    </div>

    <div class="my-4 border-t border-slate-200 dark:border-slate-700"></div>

    @if($loading)
        <div class="h-56 flex flex-col items-center justify-center gap-2">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-emerald-500"></div>
            <p class="text-sm text-slate-400">লোড হচ্ছে...</p>
        </div>
    @elseif(!$times)
        <div class="h-56 flex items-center justify-center text-sm text-rose-600 dark:text-rose-400">
            রমজানের সময়সূচি আপাতত পাওয়া যাচ্ছে না।
        </div>
    @else
        <div class="grid grid-cols-2 gap-3">

            <div class="rounded-lg bg-emerald-50/70 dark:bg-emerald-500/10 p-3 text-center border border-emerald-100 dark:border-emerald-700/30">
                <p class="text-sm font-medium text-slate-700 dark:text-slate-200">🌙 সেহরি শেষ</p>
                <p class="mt-1 text-3xl font-bold text-emerald-600 dark:text-emerald-400">
                    {{ $this->formatTime($times['timings']['Fajr'] ?? null) }}
                </p>
            </div>

            <div class="rounded-lg bg-rose-50/70 dark:bg-rose-500/10 p-3 text-center border border-rose-100 dark:border-rose-700/30">
                <p class="text-sm font-medium text-slate-700 dark:text-slate-200">🌇 ইফতার</p>
                <p class="mt-1 text-3xl font-bold text-rose-600 dark:text-rose-400">
                    {{ $this->formatTime($times['timings']['Maghrib'] ?? null) }}
                </p>
            </div>

        </div>

        <div class="my-4 border-t border-slate-200 dark:border-slate-700"></div>

        <div class="grid grid-cols-2 gap-2 text-sm">
            @foreach ([
                'Fajr' => 'ফজর',
                'Dhuhr' => 'জোহর',
                'Asr' => 'আসর',
                'Maghrib' => 'মাগরিব',
                'Isha' => 'এশা',
            ] as $key => $label)

                <div class="flex justify-between rounded-md bg-slate-50 dark:bg-slate-900/40 px-2 py-1.5 border border-slate-100 dark:border-slate-700/50">
                    <span class="text-slate-500 dark:text-slate-400">{{ $label }}:</span>
                    <span class="font-semibold text-slate-800 dark:text-slate-100">
                        {{ $this->formatTime($times['timings'][$key] ?? null) }}
                    </span>
                </div>

            @endforeach
        </div>
    @endif

</div>
