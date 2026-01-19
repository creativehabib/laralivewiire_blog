<div class="bg-secondary-light text-white text-sm">
    <div class="container flex items-center justify-between px-4 py-2">
        <div class="flex items-center gap-2">
            <i class="fa fa-calendar"></i>
            <span>{{ frontend_bangla_date() }}</span> | Live Time
        </div>
        <div class="flex items-center gap-4">
            @if(setting('site_email') || setting('site_phone'))
                <span class="hidden sm:inline text-slate-100/90">
                    যোগাযোগ: {{ setting('site_email') ?? setting('site_phone') }}
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
