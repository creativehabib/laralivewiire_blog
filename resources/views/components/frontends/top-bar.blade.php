@php
    $settings = general_settings();
    $siteEmail = $settings?->site_email;
    $sitePhone = $settings?->site_phone;
    $today = now()->locale('bn')->translatedFormat('l, d F Y');
@endphp

<div class="bg-secondary-light text-white text-sm">
    <div class="container flex items-center justify-between px-4 py-2">
        <div>{{ $today }}</div>
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
