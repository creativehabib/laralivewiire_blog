@php
    $settings = general_settings();
    $siteEmail = $settings?->site_email;
    $sitePhone = $settings?->site_phone;
    $today = frontend_bangla_date();
    $calendarDay = frontend_bangla_day();
@endphp

<div class="bg-secondary-light text-white text-sm">
    <div class="container flex items-center justify-between px-4 py-2">
        <div class="flex items-center gap-2">
            <span aria-hidden="true" class="inline-flex h-8 w-8 items-center justify-center rounded bg-white/10 text-sm font-semibold text-white">
                {{ $calendarDay }}
            </span>
            <span>{{ $today }}</span>
        </div>
        <div class="flex items-center gap-4">
            @if($siteEmail || $sitePhone)
                <span class="hidden sm:inline text-slate-100/90">
                    যোগাযোগ: {{ $siteEmail ?? $sitePhone }}
                </span>
            @endif
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
