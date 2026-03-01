<div class="bg-white dark:bg-slate-800 rounded-xl border border-emerald-100 dark:border-slate-700 shadow-sm p-4">

    <div class="flex items-start justify-between gap-3">
        <div>
            <p class="text-sm font-semibold text-emerald-600">আজকের সেহরি ও ইফতার</p>

            <p class="text-lg font-semibold">
                {{ $divisions[$selectedDivision] }}
            </p>

            <p class="text-sm font-semibold">
                আজ {{ $times['date']['hijri']['day'] ?? '--' }} রমজান
            </p>

            <p class="text-xs text-slate-500">
                {{ now('Asia/Dhaka')->locale('bn')->translatedFormat('j F, l') }}
            </p>
        </div>

        <select wire:model="selectedDivision"
                class="w-36 rounded-lg border px-3 py-2 text-sm">
            @foreach($divisions as $key => $name)
                <option value="{{ $key }}">{{ $name }}</option>
            @endforeach
        </select>
    </div>

    <div class="my-4 border-t"></div>

    <div class="grid grid-cols-2 gap-3">

        <div class="rounded-lg bg-emerald-50 p-3 text-center">
            <p class="text-sm">🌙 সেহরি শেষ</p>
            <p class="text-2xl font-bold">
                {{ \Illuminate\Support\Str::of($times['timings']['Fajr'] ?? '--')->substr(0,5) }}
            </p>
        </div>

        <div class="rounded-lg bg-rose-50 p-3 text-center">
            <p class="text-sm">🌇 ইফতার</p>
            <p class="text-2xl font-bold">
                {{ \Illuminate\Support\Str::of($times['timings']['Maghrib'] ?? '--')->substr(0,5) }}
            </p>
        </div>

    </div>

    <div class="my-4 border-t"></div>

    <div class="grid grid-cols-2 gap-2 text-sm">
        @foreach ([
            'Fajr' => 'ফজর',
            'Dhuhr' => 'জোহর',
            'Asr' => 'আসর',
            'Maghrib' => 'মাগরিব',
            'Isha' => 'এশা',
        ] as $key => $label)

            <div class="flex justify-between bg-slate-50 px-2 py-1 rounded">
                <span>{{ $label }}:</span>
                <span>
                    {{ \Illuminate\Support\Str::of($times['timings'][$key] ?? '--')->substr(0,5) }}
                </span>
            </div>

        @endforeach
    </div>

</div>
