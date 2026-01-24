<div class="bg-secondary-light text-white text-sm">
    <div class="container flex items-center justify-between px-4 py-2">
        <div class="flex items-center gap-2">
            <i class="fa fa-calendar"></i>
            <span>{{ frontend_bangla_date() }}</span>
            <span aria-hidden="true">|</span>
            @php
                $displayTimezone = setting('timezone', config('app.timezone', 'Asia/Dhaka'));
            @endphp
            <span
                id="live-time"
                class="font-medium"
                aria-live="polite"
                data-timezone="{{ $displayTimezone }}"
            >Live Time</span>
            <span class="text-xs text-slate-100/80" aria-hidden="true">({{ $displayTimezone }})</span>
        </div>
        <div class="social_link">
            <div class="flex items-center gap-2 text-xs sm:text-sm">
                <a href="#" class="hover:text-primary-light">Facebook</a>
                <span>|</span>
                <a href="#" class="hover:text-primary-light">YouTube</a>
                <span>|</span>
                <a href="#" class="hover:text-primary-light">X (Twitter)</a>
            </div>
        </div>
    </div>
</div>
<script>
    (() => {
        const liveTimeElement = document.getElementById('live-time');
        if (!liveTimeElement) {
            return;
        }

        const timeZone = liveTimeElement.dataset.timezone;
        const formatter = new Intl.DateTimeFormat('bn-BD', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            timeZone: timeZone || undefined,
        });

        const updateTime = () => {
            liveTimeElement.textContent = formatter.format(new Date());
        };

        updateTime();
        setInterval(updateTime, 1000);
    })();
</script>
