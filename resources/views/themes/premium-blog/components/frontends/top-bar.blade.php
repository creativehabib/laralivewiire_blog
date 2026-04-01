@php
    $displayTimezone = setting('timezone', config('app.timezone', 'Asia/Dhaka'));
    $dateDisplayFormat = setting('date_display_format', 'gregorian_and_bangla');
    $gregorianDate = frontend_bangla_gregorian_date();
    $banglaCalendarDate = $dateDisplayFormat === 'gregorian_only'
        ? null
        : frontend_bangla_calendar_date();

    $rawSocialLinks = setting('social_links', []);
    $socialLinks = collect(is_array($rawSocialLinks) ? $rawSocialLinks : [])
        ->map(function ($link) {
            if (! is_array($link)) {
                return null;
            }

            if (array_key_exists('name', $link)) {
                $normalized = [
                    'name' => $link['name'] ?? '',
                    'icon' => $link['icon'] ?? '',
                    'url' => $link['url'] ?? '',
                    'color' => $link['color'] ?? '#ffffff',
                    'bg_color' => $link['bg_color'] ?? 'transparent',
                ];
            } else {
                $normalized = [
                    'name' => '',
                    'icon' => '',
                    'url' => '',
                    'color' => '#ffffff',
                    'bg_color' => 'transparent',
                ];

                foreach ($link as $pair) {
                    if (! is_array($pair) || ! array_key_exists('key', $pair)) {
                        continue;
                    }

                    $key = $pair['key'];
                    $value = $pair['value'] ?? null;

                    if ($key === 'background-color') {
                        $normalized['bg_color'] = $value ?? $normalized['bg_color'];
                        continue;
                    }

                    if (array_key_exists($key, $normalized)) {
                        $normalized[$key] = $value;
                    }
                }
            }

            $icon = trim((string) ($normalized['icon'] ?? ''));
            if ($icon !== '' && ! str_contains($icon, 'fa-')) {
                $icon = 'fab fa-' . $icon;
            }

            $normalized['icon_class'] = $icon;

            return $normalized;
        })
        ->filter(fn ($link) => filled($link['url'] ?? null) && filled($link['icon_class'] ?? null))
        ->values();
@endphp

<div class="bg-secondary-light text-white text-sm">
    <div class="container flex items-center justify-between px-4 py-2">
        <div class="flex items-center gap-2">
            <i class="fa fa-calendar"></i>
            <span>{{ $gregorianDate }}</span>

            @if ($banglaCalendarDate)
                <span class="hidden sm:inline" aria-hidden="true">|</span>
                <span class="hidden sm:inline">{{ $banglaCalendarDate }}</span>
            @endif

            <span class="hidden sm:inline" aria-hidden="true">|</span>

            <span
                id="live-time"
                class="font-medium hidden sm:inline"
                aria-live="polite"
                data-timezone="{{ $displayTimezone }}"
            >Live Time</span>

            <span class="text-xs text-slate-100/80 hidden sm:inline" aria-hidden="true">({{ $displayTimezone }})</span>
        </div>

        @if ($socialLinks->isNotEmpty())
            <div class="social_link">
                <div class="flex items-center gap-2 text-xs sm:text-sm">
                    @foreach ($socialLinks as $link)
                        <a
                            href="{{ $link['url'] }}"
                            class="inline-flex h-7 w-7 items-center justify-center rounded border border-white/20 transition hover:border-primary-light/60 hover:text-primary-light"
                            style="color: {{ $link['color'] }}; background-color: {{ $link['bg_color'] }};"
                            target="_blank"
                            rel="noopener noreferrer"
                            aria-label="{{ $link['name'] ?: 'Social link' }}"
                            title="{{ $link['name'] }}"
                        >
                            <i class="{{ $link['icon_class'] }}"></i>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
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
