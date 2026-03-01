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
        divisions: {
            'Dhaka': 'ঢাকা', 'Chattogram': 'চট্টগ্রাম', 'Rajshahi': 'রাজশাহী', 'Khulna': 'খুলনা',
            'Barishal': 'বরিশাল', 'Sylhet': 'সিলেট', 'Rangpur': 'রংপুর', 'Mymensingh': 'ময়মনসিংহ'
        },

        async fetchTimes() {
            this.loading = true;
            try {
                const response = await fetch(`https://api.aladhan.com/v1/timingsByCity/{{ $apiDate }}?city=${this.selectedDivision}&country=Bangladesh&method=13`);
                const data = await response.json();
                this.times = data.data;
                this.loading = false;
            } catch (error) {
                console.error('Error fetching data:', error);
                this.loading = false;
            }
        },

        init() {
            this.fetchTimes();
            setInterval(() => { this.now = new Date(); }, 1000);
        },

        toBnNumber(value) {
            return String(value).replace(/\d/g, (d) => '০১২৩৪৫৬৭৮৯'[d]);
        },

        // সেহরি ৫:০৪ করতে ফজরের সাথে মিনিট অ্যাডজাস্টমেন্ট
        getSehriTime(fajrTime) {
            if (!fajrTime) return '--:--';
            let [hours, minutes] = fajrTime.split(':').map(Number);
            let date = new Date();
            date.setHours(hours, minutes, 0);

            // ৫:০২ দেখালে ১ বা ২ মিনিট যোগ করে ৫:০৪ এর সাথে মিলিয়ে নিন
            date.setMinutes(date.getMinutes() );

            let h = date.getHours() % 12 || 12;
            let m = String(date.getMinutes()).padStart(2, '0');
            return this.toBnNumber(`${h}:${m}`);
        },

        // ইফতার ৬:০৩ করতে মাগরিব থেকে ৬ মিনিট বিয়োগ
        getIftarTime(maghribTime) {
            if (!maghribTime) return '--:--';
            let [hours, minutes] = maghribTime.split(':').map(Number);
            let date = new Date();
            date.setHours(hours, minutes, 0);

            date.setMinutes(date.getMinutes() - 6);

            let h = date.getHours() % 12 || 12;
            let m = String(date.getMinutes()).padStart(2, '0');
            return this.toBnNumber(`${h}:${m}`);
        },

        formatTime(time24) {
            if (!time24) return '--:--';
            let [hours, minutes] = time24.split(':');
            hours = parseInt(hours);
            let h = hours % 12 || 12;
            return this.toBnNumber(`${h}:${minutes}`);
        },

        getRamadanDay() {
            if (!this.times || !this.times.date || !this.times.date.hijri) return '--';
            let day = parseInt(this.times.date.hijri.day);

            // ১৪ রমজান থেকে ১২ রমজান করতে -২ দিন অ্যাডজাস্টমেন্ট
            let adjustment = -1;
            let finalDay = day + adjustment;

            if (finalDay <= 0) return '--';
            return finalDay;
        },

        getIftarRemaining() {
            if (!this.times || !this.times.timings) return null;

            // কাউন্টডাউনও যেন সঠিক ইফতারের সময়ের (৬:০৩) সাথে কাজ করে
            const [hour, minute] = this.times.timings.Maghrib.split(':').map(Number);
            const nowDhaka = new Date(new Date().toLocaleString('en-US', { timeZone: 'Asia/Dhaka' }));
            const iftarTime = new Date(nowDhaka);
            iftarTime.setHours(hour, minute - 6, 0, 0); // এখানেও -৬ মিনিট করা হয়েছে

            const diffMs = iftarTime - nowDhaka;
            if (diffMs <= 0) return null;

            const totalSeconds = Math.floor(diffMs / 1000);
            return {
                hours: Math.floor(totalSeconds / 3600),
                minutes: Math.floor((totalSeconds % 3600) / 60),
                seconds: totalSeconds % 60
            };
        },

        get iftarCountdownLabel() {
            const remaining = this.getIftarRemaining();
            if (!remaining) return 'ইফতারের সময় হয়েছে';
            const formatted = `${String(remaining.hours).padStart(2, '0')}:${String(remaining.minutes).padStart(2, '0')}:${String(remaining.seconds).padStart(2, '0')}`;
            return `${this.toBnNumber(formatted)} বাকি`;
        }
    }"
    x-init="init()"
    class="bg-white dark:bg-slate-800 rounded-xl border border-emerald-100 dark:border-slate-700 shadow-sm p-4"
>
    <template x-if="loading">
        <div class="h-64 flex flex-col items-center justify-center">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-emerald-500"></div>
        </div>
    </template>

    <div x-show="!loading" x-cloak>
        <div class="flex items-start justify-between gap-3">
            <div>
                <p class="text-sm font-semibold text-emerald-600 dark:text-emerald-400">আজকের সেহরি ও ইফতার</p>
                <p class="text-lg font-semibold text-slate-900 dark:text-white" x-text="divisions[selectedDivision]"></p>
                <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">আজ <span x-text="toBnNumber(getRamadanDay())"></span> রমজান</p>
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

        <div class="grid grid-cols-2 gap-3">
            <div class="rounded-lg bg-emerald-50/70 dark:bg-emerald-500/10 p-3 text-center border border-emerald-50">
                <p class="text-sm font-medium text-slate-700 dark:text-slate-200">🌙 সেহরি শেষ</p>
                <p class="mt-1 text-3xl font-bold text-emerald-600 dark:text-emerald-400" x-text="getSehriTime(times?.timings?.Fajr)"></p>
            </div>

            <div class="rounded-lg bg-rose-50/70 dark:bg-rose-500/10 p-3 text-center border border-rose-50">
                <p class="text-sm font-medium text-slate-700 dark:text-slate-200">🌇 ইফতার</p>
                <p class="mt-1 text-3xl font-bold text-rose-600 dark:text-rose-400" x-text="getIftarTime(times?.timings?.Maghrib)"></p>
                <p class="mt-1 text-[10px] font-bold text-rose-600 dark:text-rose-300 uppercase tracking-tighter" x-text="iftarCountdownLabel"></p>
            </div>
        </div>

        <div class="my-4 border-t border-slate-200 dark:border-slate-700"></div>

        <dl class="grid grid-cols-2 gap-x-3 gap-y-2 text-sm">
            <template x-for="prayer in [
                {label: 'ফজর', time: times?.timings?.Fajr},
                {label: 'জোহর', time: times?.timings?.Dhuhr},
                {label: 'আসর', time: times?.timings?.Asr},
                {label: 'মাগরিব', time: times?.timings?.Maghrib},
                {label: 'এশা', time: times?.timings?.Isha}
            ]">
                <div class="flex items-center justify-between rounded-md bg-slate-50 dark:bg-slate-900/40 px-2 py-1.5 border border-slate-100 dark:border-slate-700/50">
                    <dt class="text-slate-500 dark:text-slate-400" x-text="prayer.label + ':'"></dt>
                    <dd class="font-semibold text-slate-800 dark:text-slate-100" x-text="formatTime(prayer.time)"></dd>
                </div>
            </template>
        </dl>
    </div>
</section>
