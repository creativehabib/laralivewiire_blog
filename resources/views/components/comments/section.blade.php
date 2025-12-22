@props([
    'commentable',
    'commentRelation' => 'comments',
    'commentConfig' => null,
    'canonicalUrl' => request()->url(),
])

@php($config = $commentConfig ?? \App\Support\CommentConfig::get())
@php($fbAppId = '4371088989883299') {{-- আপনার অ্যাপ আইডি --}}

{{-- 🔥 FIX 3: SDK লোড করার স্ট্যান্ডার্ড পদ্ধতি (Active Tab এর বাইরে) --}}
@push('scripts')
    <script>
        window.fbAsyncInit = function() {
            FB.init({
                appId            : '{{ $fbAppId }}',
                autoLogAppEvents : true,
                xfbml            : true,
                version          : 'v18.0'
            });
        };
    </script>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js"></script>
@endpush

<div
    x-data="{
        activeTab: 'default',
        facebookInitialized: false,

        loadFacebook() {
            this.activeTab = 'facebook';

            // ট্যাব বদলানোর পর একটু সময় দিয়ে রেন্ডার করা
            setTimeout(() => {
                if (window.FB) {
                    console.log('Rendering FB Comments...');
                    window.FB.XFBML.parse(document.getElementById('fb-wrapper'));
                } else {
                    console.log('Waiting for FB SDK...');
                }
            }, 500);
        }
    }"
    class="border border-slate-200 dark:border-slate-700 rounded-lg overflow-hidden bg-white dark:bg-slate-800"
>
    {{-- Tabs --}}
    <div class="flex border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60">
        <button
            type="button"
            class="flex-1 px-4 py-2 text-sm font-semibold focus:outline-none transition-colors"
            :class="activeTab === 'default' ? 'text-blue-600 border-b-2 border-blue-600 bg-white dark:bg-slate-800' : 'text-slate-600 dark:text-slate-300'"
            @click="activeTab = 'default'"
        >
            Default comments
        </button>
        <button
            type="button"
            class="flex-1 px-4 py-2 text-sm font-semibold focus:outline-none transition-colors border-l border-slate-200 dark:border-slate-700"
            :class="activeTab === 'facebook' ? 'text-blue-600 border-b-2 border-blue-600 bg-white dark:bg-slate-800' : 'text-slate-600 dark:text-slate-300'"
            @click="loadFacebook()"
        >
            Facebook comments
        </button>
    </div>

    {{-- Default Comments --}}
    <div x-show="activeTab === 'default'" class="p-4">
        <livewire:frontend.comments :commentable="$commentable" :comment-relation="$commentRelation" />
    </div>

    {{-- Facebook Comments --}}
    {{-- x-show এর বদলে hidden ক্লাস ব্যবহার করা নিরাপদ --}}
    <div :class="activeTab === 'facebook' ? 'block' : 'hidden'" class="p-4 bg-white" id="fb-wrapper">
        <div class="flex justify-center w-full min-h-[150px]">
            <div class="fb-comments"
                 data-href="{{ $canonicalUrl }}"
                 data-width="100%"
                 data-numposts="5">
            </div>
        </div>

        {{-- Fallback --}}
        <div x-show="activeTab === 'facebook' && !window.FB" class="text-center text-xs text-slate-400 mt-4">
            Loading Facebook system...
        </div>
    </div>
</div>
