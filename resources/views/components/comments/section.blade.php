@props([
    'commentable',
    'commentRelation' => 'comments',
    'commentConfig' => null,
    'canonicalUrl' => request()->url(),
])

@php($config = $commentConfig ?? \App\Support\CommentConfig::get())
{{-- হার্ডকোড করা অ্যাপ আইডি সহ URL --}}
@php($facebookSdkUrl = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v18.0&appId=4371088989883299")

<div
    x-data="{
        activeTab: 'default',
        refreshFacebook() {
            console.log('Tab switched. Checking for FB SDK...');

            let attempts = 0;
            let interval = setInterval(() => {
                attempts++;
                if (window.FB) {
                    console.log('Success: FB SDK found. Rendering...');
                    window.FB.XFBML.parse(document.getElementById('fb-comment-wrapper'));
                    clearInterval(interval);
                } else {
                    console.log('Waiting for FB SDK...');
                }

                if (attempts > 20) clearInterval(interval); // ১০ সেকেন্ড চেষ্টা করবে
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
            :class="activeTab === 'default' ? 'text-blue-600 border-b-2 border-blue-600 bg-white' : 'text-slate-600'"
            @click="activeTab = 'default'"
        >
            Default comments
        </button>
        <button
            type="button"
            class="flex-1 px-4 py-2 text-sm font-semibold focus:outline-none transition-colors border-l border-slate-200"
            :class="activeTab === 'facebook' ? 'text-blue-600 border-b-2 border-blue-600 bg-white' : 'text-slate-600'"
            @click="activeTab = 'facebook'; refreshFacebook()"
        >
            Facebook comments
        </button>
    </div>

    {{-- Default Comments --}}
    <div class="p-4" x-show="activeTab === 'default'">
        <livewire:frontend.comments :commentable="$commentable" :comment-relation="$commentRelation" />
    </div>

    {{-- Facebook Comments --}}
    <div class="p-4" x-show="activeTab === 'facebook'" x-cloak id="fb-comment-wrapper">

        {{-- ১. স্ক্রিপ্টটি সরাসরি এখানে দিচ্ছি (@push বাদ দিয়ে) --}}
        <script async defer crossorigin="anonymous" src="{{ $facebookSdkUrl }}" nonce="fb-comments"></script>

        {{-- ডিবাগ মেসেজ --}}
        <div class="text-xs text-red-400 mb-2 text-center" x-show="!window.FB">
            Connecting to Facebook... (Please disable AdBlock if it takes too long)
        </div>

        <div class="bg-white flex justify-center w-full">
            <div class="fb-comments"
                 data-href="{{ $canonicalUrl }}"
                 data-width="100%"
                 data-numposts="5"
                 style="width: 100%;">
            </div>
        </div>
    </div>
</div>
