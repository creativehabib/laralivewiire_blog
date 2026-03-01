@php
    $today = now()->locale('bn')->translatedFormat('j F, l');
@endphp

<section
    x-data="{
        selectedDivision: 'dhaka',
        divisions: {
            dhaka: { name: 'ঢাকা', sehri: '5:05', iftar: '6:03', fajr: '5:05', zuhr: '12:11', asr: '3:32', maghrib: '6:03', isha: '7:27' },
            chattogram: { name: 'চট্টগ্রাম', sehri: '4:58', iftar: '5:57', fajr: '4:58', zuhr: '12:05', asr: '3:27', maghrib: '5:57', isha: '7:20' },
            rajshahi: { name: 'রাজশাহী', sehri: '5:11', iftar: '6:10', fajr: '5:11', zuhr: '12:16', asr: '3:37', maghrib: '6:10', isha: '7:34' },
            khulna: { name: 'খুলনা', sehri: '5:08', iftar: '6:07', fajr: '5:08', zuhr: '12:14', asr: '3:35', maghrib: '6:07', isha: '7:31' },
            barishal: { name: 'বরিশাল', sehri: '5:04', iftar: '6:02', fajr: '5:04', zuhr: '12:10', asr: '3:31', maghrib: '6:02', isha: '7:26' },
            sylhet: { name: 'সিলেট', sehri: '4:55', iftar: '5:54', fajr: '4:55', zuhr: '12:02', asr: '3:24', maghrib: '5:54', isha: '7:17' },
            rangpur: { name: 'রংপুর', sehri: '5:06', iftar: '6:06', fajr: '5:06', zuhr: '12:12', asr: '3:34', maghrib: '6:06', isha: '7:30' },
            mymensingh: { name: 'ময়মনসিংহ', sehri: '5:03', iftar: '6:01', fajr: '5:03', zuhr: '12:09', asr: '3:30', maghrib: '6:01', isha: '7:24' }
        },
        toBnNumber(value) {
            return String(value).replace(/\d/g, (d) => '০১২৩৪৫৬৭৮৯'[d]);
        },
        getRamadanDay() {
            try {
                const formatter = new Intl.DateTimeFormat('en-TN-u-ca-islamic', {
                    day: 'numeric',
                    month: 'long',
                    timeZone: 'Asia/Dhaka'
                });

                const parts = formatter.formatToParts(new Date());
                const dayRaw = parts.find((part) => part.type === 'day')?.value ?? '';
                const day = Number.parseInt(dayRaw, 10);

                if (Number.isNaN(day)) {
                    return '';
                }

                // Bangladesh Ramadan calendars commonly differ by one day from browser Islamic calendar calculations.
                const bangladeshAdjustment = -1;
                const adjustedDay = ((day + bangladeshAdjustment - 1 + 30) % 30) + 1;

                return String(adjustedDay);
            } catch (error) {
                return '';
            }
        },
        get current() {
            return this.divisions[this.selectedDivision];
        }
    }"
    class="bg-white dark:bg-slate-800 rounded-xl border border-emerald-100 dark:border-slate-700 shadow-sm p-4"
>
    <div class="flex items-start justify-between gap-3">
        <div>
            <p class="text-sm font-semibold text-emerald-600 dark:text-emerald-400">আজকের সেহরি ও ইফতার</p>
            <p class="text-lg font-semibold text-slate-900 dark:text-white" x-text="current.name"></p>
            <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">আজ <span x-text="toBnNumber(getRamadanDay())"></span> রমজান</p>
            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $today }}</p>
        </div>

        <label class="sr-only" for="ramadan-division">বিভাগ নির্বাচন</label>
        <select
            id="ramadan-division"
            x-model="selectedDivision"
            class="w-36 rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-900 px-3 py-2 text-sm text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500"
        >
            <template x-for="(division, key) in divisions" :key="key">
                <option :value="key" x-text="division.name"></option>
            </template>
        </select>
    </div>

    <div class="my-4 border-t border-slate-200 dark:border-slate-700"></div>

    <div class="grid grid-cols-2 gap-3">
        <div class="rounded-lg bg-emerald-50/70 dark:bg-emerald-500/10 p-3 text-center">
            <p class="text-sm font-medium text-slate-700 dark:text-slate-200">🌙 সেহরি শেষ</p>
            <p class="mt-1 text-3xl font-bold text-emerald-600 dark:text-emerald-400" x-text="toBnNumber(current.sehri)"></p>
        </div>

        <div class="rounded-lg bg-rose-50/70 dark:bg-rose-500/10 p-3 text-center">
            <p class="text-sm font-medium text-slate-700 dark:text-slate-200">🌇 ইফতার</p>
            <p class="mt-1 text-3xl font-bold text-rose-600 dark:text-rose-400" x-text="toBnNumber(current.iftar)"></p>
        </div>
    </div>

    <div class="my-4 border-t border-slate-200 dark:border-slate-700"></div>

    <dl class="grid grid-cols-2 gap-x-3 gap-y-2 text-sm">
        <div class="flex items-center justify-between rounded-md bg-slate-50 dark:bg-slate-900/40 px-2 py-1.5">
            <dt class="text-slate-500 dark:text-slate-400">ফজর:</dt>
            <dd class="font-semibold text-slate-800 dark:text-slate-100" x-text="toBnNumber(current.fajr)"></dd>
        </div>
        <div class="flex items-center justify-between rounded-md bg-slate-50 dark:bg-slate-900/40 px-2 py-1.5">
            <dt class="text-slate-500 dark:text-slate-400">জোহর:</dt>
            <dd class="font-semibold text-slate-800 dark:text-slate-100" x-text="toBnNumber(current.zuhr)"></dd>
        </div>
        <div class="flex items-center justify-between rounded-md bg-slate-50 dark:bg-slate-900/40 px-2 py-1.5">
            <dt class="text-slate-500 dark:text-slate-400">আসর:</dt>
            <dd class="font-semibold text-slate-800 dark:text-slate-100" x-text="toBnNumber(current.asr)"></dd>
        </div>
        <div class="flex items-center justify-between rounded-md bg-slate-50 dark:bg-slate-900/40 px-2 py-1.5">
            <dt class="text-slate-500 dark:text-slate-400">মাগরিব:</dt>
            <dd class="font-semibold text-slate-800 dark:text-slate-100" x-text="toBnNumber(current.maghrib)"></dd>
        </div>
        <div class="col-span-2 flex items-center justify-between rounded-md bg-slate-50 dark:bg-slate-900/40 px-2 py-1.5">
            <dt class="text-slate-500 dark:text-slate-400">এশা:</dt>
            <dd class="font-semibold text-slate-800 dark:text-slate-100" x-text="toBnNumber(current.isha)"></dd>
        </div>
    </dl>
</section>
