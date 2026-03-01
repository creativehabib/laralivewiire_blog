@php
    $today = now()->locale('bn')->translatedFormat('j F, l');
    $apiDate = now()->format('d-m-Y');
@endphp

<section
    x-data="{
        selectedDivision: 'Dhaka',
        now: new Date(),
        loading: true,
        times: null,

        // হিজরি দিন অ্যাডজাস্টমেন্ট (প্রয়োজনে পরিবর্তন করুন)
        hijriAdjustment: -1,

        // ইফতারের মিনিট অ্যাডজাস্টমেন্ট (মাগরিবের আগে কত মিনিট)
        iftarOffset: 6,

        divisions: {
            'Dhaka': 'ঢাকা',
            'Chattogram': 'চট্টগ্রাম',
            'Rajshahi': 'রাজশাহী',
            'Khulna': 'খুলনা',
            'Barishal': 'বরিশাল',
            'Sylhet': 'সিলেট',
            'Rangpur': 'রংপুর',
            'Mymensingh': 'ময়মনসিংহ'
        },

        async fetchTimes() {
            this.loading = true;
            try {
                const response = await fetch(
                    `https://api.aladhan.com/v1/timingsByCity/{{ $apiDate }}?city=${this.selectedDivision}&country=Bangladesh&method=13`
                );
                const data = await response.json();
                this.times = data.data;
            } catch (error) {
                console.error('Prayer times fetch error:', error);
            } finally {
                this.loading = false;
            }
        },

        init() {
            this.fetchTimes();
            setInterval(() => { this.now = new Date(); }, 1000);
        },

        // বাংলা সংখ্যায় রূপান্তর
        toBnNumber(value) {
            return String(value).replace(/\d/g, (d) => '০১২৩৪৫৬৭৮৯'[d]);
        },

        // Dhaka টাইমজোনে বর্তমান সময়
        getNowDhaka() {
            return new Date(new Date().toLocaleString('en-US', { timeZone: 'Asia/Dhaka' }));
        },

        // ২৪ ঘণ্টার সময়কে AM/PM ছাড়া ১২ ঘণ্টায় রূপান্তর (বাংলা সংখ্যায়)
        formatTime(time24) {
            if (!time24) return '--:--';
            let [hours, minutes] = time24.split(':');
            hours = parseInt(hours);
            let h = hours % 12 || 12;
            return this.toBnNumber(`${h}:${minutes}`);
        },

        // সেহরির শেষ সময় (ফজরের সময়)
        getSehriTime(fajrTime) {
            if (!fajrTime) return '--:--';
            return this.formatTime(fajrTime);
        },

        // ইফতারের সময় (মাগরিব থেকে iftarOffset মিনিট আগে)
        getIftarTime(maghribTime) {
            if (!maghribTime) return '--:--';
            let [hours, minutes] = maghribTime.split(':').map(Number);
            let date = new Date();
            date.setHours(hours, minutes - this.iftarOffset, 0, 0);
            let h = date.getHours() % 12 || 12;
            let m = String(date.getMinutes()).padStart(2, '0');
            return this.toBnNumber(`${h}:${m}`);
        },

        // রমজানের দিন (হিজরি দিন + অ্যাডজাস্টমেন্ট)
        getRamadanDay() {
            if (!this.times?.date?.hijri) return '--';
            let day = parseInt(this.times.date.hijri.day) + this.hijriAdjustment;
            return day > 0 ? day : '--';
        },

        // ইফতার কাউন্টডাউন লেবেল (প্রতি সেকেন্ডে আপডেট)
        get iftarCountdownLabel() {
            if (!this.times?.timings?.Maghrib) return 'লোড হচ্ছে...';

            const [hour, minute] = this.times.timings.Maghrib.split(':').map(Number);

            // Dhaka টাইমজোনে ইফতারের সময় নির্ধারণ
            const nowDhaka = this.getNowDhaka();
            const iftarTime = new Date(nowDhaka);
            iftarTime.setHours(hour, minute - this.iftarOffset, 0, 0);

            // this.now ব্যবহারে প্রতি সেকেন্ডে re-compute হয়
            const diffMs = iftarTime - this.now;

            if (diffMs <= 0) return 'ইফতারের সময় হয়েছে 🌙';

            const totalSeconds = Math.floor(diffMs / 1000);
            const h = Math.floor(totalSeconds / 3600);
            const m = Math.floor((totalSeconds % 3600) / 60);
            const s = totalSeconds % 60;

            const formatted = [
                String(h).padStart(2, '0'),
                String(m).padStart(2, '0'),
                String(s).padStart(2, '0')
            ].join(':');

            return this.toBnNumber(formatted) + ' বাকি';
        }
    }"
    x-init="init()"
    class="bg-white dark:bg-slate-800 rounded-xl border border-emerald-100 dark:border-slate-700 shadow-sm p-4"
>
    {{-- লোডিং স্টেট --}}
    <template x-if="loading">
        <div class="h-64 flex flex-col items-center justify-center gap-2">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-emerald-500"></div>
            <p class="text-sm text-slate-400">লোড হচ্ছে...</p>
        </div>
    </template>

    {{-- মূল কন্টেন্ট --}}
    <div x-show="!loading" x-cloak>

        {{-- হেডার: শিরোনাম + বিভাগ নির্বাচন --}}
        <div class="flex items-start justify-between gap-3">
            <div>
                <p class="text-sm font-semibold text-emerald-600 dark:text-emerald-400">আজকের সেহরি ও ইফতার</p>
                <p class="text-lg font-semibold text-slate-900 dark:text-white" x-text="divisions[selectedDivision]"></p>
                <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">
                    আজ <span x-text="toBnNumber(getRamadanDay())"></span> রমজান
                </p>
                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $today }}</p>
            </div>

            <select
                x-model="selectedDivision"
                @change="fetchTimes()"
                class="w-36 rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-900 px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 outline-none"
            >
                <template x-for="(name, key) in divisions" :key="key">
                    <option :value="key" x-text="name"></option>
                </template>
            </select>
        </div>

        <div class="my-4 border-t border-slate-200 dark:border-slate-700"></div>

        {{-- সেহরি ও ইফতারের সময় --}}
        <div class="grid grid-cols-2 gap-3">
            <div class="rounded-lg bg-emerald-50/70 dark:bg-emerald-500/10 p-3 text-center border border-emerald-100 dark:border-emerald-700/30">
                <p class="text-sm font-medium text-slate-700 dark:text-slate-200">🌙 সেহরি শেষ</p>
                <p class="mt-1 text-3xl font-bold text-emerald-600 dark:text-emerald-400"
                   x-text="getSehriTime(times?.timings?.Fajr)"></p>
            </div>

            <div class="rounded-lg bg-rose-50/70 dark:bg-rose-500/10 p-3 text-center border border-rose-100 dark:border-rose-700/30">
                <p class="text-sm font-medium text-slate-700 dark:text-slate-200">🌇 ইফতার</p>
                <p class="mt-1 text-3xl font-bold text-rose-600 dark:text-rose-400"
                   x-text="getIftarTime(times?.timings?.Maghrib)"></p>
                <p class="mt-1 text-[10px] font-bold text-rose-600 dark:text-rose-300 uppercase tracking-tighter"
                   x-text="iftarCountdownLabel"></p>
            </div>
        </div>

        <div class="my-4 border-t border-slate-200 dark:border-slate-700"></div>

        {{-- নামাজের সময়সূচি --}}
        <dl class="grid grid-cols-2 gap-x-3 gap-y-2 text-sm">
            <template x-for="prayer in [
                { label: 'ফজর',    key: 'Fajr'    },
                { label: 'জোহর',   key: 'Dhuhr'   },
                { label: 'আসর',    key: 'Asr'     },
                { label: 'মাগরিব', key: 'Maghrib' },
                { label: 'এশা',    key: 'Isha'    }
            ]">
                <div class="flex items-center justify-between rounded-md bg-slate-50 dark:bg-slate-900/40 px-2 py-1.5 border border-slate-100 dark:border-slate-700/50">
                    <dt class="text-slate-500 dark:text-slate-400" x-text="prayer.label + ':'"></dt>
                    <dd class="font-semibold text-slate-800 dark:text-slate-100"
                        x-text="formatTime(times?.timings?.[prayer.key])"></dd>
                </div>
            </template>
        </dl>

    </div>
</section>
