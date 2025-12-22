@props([
    'commentable',
    'commentRelation' => 'comments',
    'commentConfig' => null,
    'canonicalUrl' => request()->url(),
])

@php($config = $commentConfig ?? \App\Support\CommentConfig::get())
@php($facebookSdkUrl = \App\Support\CommentConfig::facebookSdkUrl($config))

@if(($config['provider'] ?? 'local') === 'both')
    <div
        x-data="{
            activeTab: 'default',
            fbLoaded: typeof window !== 'undefined' && !!window.FB,
            sdkUrl: @js($facebookSdkUrl),

            // ফেসবুক SDK নিশ্চিত করার ফাংশন (Promise based)
            ensureFacebookSdk() {
                return new Promise((resolve) => {
                    // ১. যদি SDK URL না থাকে
                    if (! this.sdkUrl) {
                        resolve();
                        return;
                    }

                    // ২. যদি ইতিমধ্যে লোড হয়ে থাকে
                    if (this.fbLoaded || (typeof window !== 'undefined' && window.FB)) {
                        this.fbLoaded = true;
                        resolve();
                        return;
                    }

                    // ৩. স্ক্রিপ্ট ট্যাগ না থাকলে তৈরি করা (Fallback)
                    if (this.sdkUrl && ! document.getElementById('facebook-jssdk')) {
                        const fbScript = document.createElement('script');
                        fbScript.id = 'facebook-jssdk';
                        fbScript.async = true;
                        fbScript.defer = true;
                        fbScript.src = this.sdkUrl;
                        fbScript.crossOrigin = 'anonymous';
                        document.body.appendChild(fbScript);
                    }

                    // ৪. উইন্ডো অবজেক্টে FB আসা পর্যন্ত অপেক্ষা করা (Polling)
                    const waitForFb = () => {
                        if (typeof window !== 'undefined' && window.FB) {
                            this.fbLoaded = true;
                            resolve();
                        } else {
                            setTimeout(waitForFb, 150); // ১৫০ms পর পর চেক করবে
                        }
                    };

                    waitForFb();
                });
            },

            loadFacebook() {
                this.activeTab = 'facebook';

                // SDK রেডি হওয়ার পর রেন্ডার করবে
                this.ensureFacebookSdk().then(() => {
                    if (window.FB) {
                        // আগে কন্টেইনার ভিজিবল হতে হবে, তারপর পার্স
                        setTimeout(() => {
                            window.FB.XFBML.parse(document.getElementById('fb-tab-content'));
                        }, 50);
                    }
                });
            }
        }"
        class="border border-slate-200 dark:border-slate-700 rounded-lg overflow-hidden bg-white dark:bg-slate-800"
    >
        {{-- Tabs Header --}}
        <div class="flex border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60">
            <button
                type="button"
                class="flex-1 px-4 py-2 text-sm font-semibold focus:outline-none transition-colors"
                :class="activeTab === 'default' ? 'text-primary-dark dark:text-primary-light bg-white dark:bg-slate-800 border-b-2 border-blue-500' : 'text-slate-600 dark:text-slate-300'"
                @click="activeTab = 'default'"
            >
                Default comments
            </button>
            <button
                type="button"
                class="flex-1 px-4 py-2 text-sm font-semibold focus:outline-none transition-colors border-l border-slate-200 dark:border-slate-700"
                :class="activeTab === 'facebook' ? 'text-primary-dark dark:text-primary-light bg-white dark:bg-slate-800 border-b-2 border-blue-500' : 'text-slate-600 dark:text-slate-300'"
                @click="loadFacebook()"
            >
                Facebook comments
            </button>
        </div>

        {{-- Default Comments --}}
        <div class="p-4" x-show="activeTab === 'default'">
            <livewire:frontend.comments :commentable="$commentable" :comment-relation="$commentRelation" />
        </div>

        {{-- Facebook Comments --}}
        <div
            id="fb-tab-content"
            class="p-4 bg-white dark:bg-slate-800"
            :class="activeTab === 'facebook' ? 'block' : 'hidden'"
        >
            @if($facebookSdkUrl)
                @once
                    <div id="fb-root"></div>
                    @push('scripts')
                        <script>
                            window.fbAsyncInit = function() {
                                if (window.FB) {
                                    window.FB.init({
                                        appId: '{{ data_get($config, 'facebook.app_id') }}',
                                        xfbml: true,
                                        version: 'v18.0',
                                    });
                                }
                            }
                        </script>
                        <script id="facebook-jssdk" async defer crossorigin="anonymous" src="{{ $facebookSdkUrl }}"></script>
                    @endpush
                @endonce
            @endif

            <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-4 bg-white flex justify-center">
                <div class="fb-comments"
                     data-href="{{ $canonicalUrl }}"
                     data-width="100%"
                     data-numposts="5">
                </div>
            </div>

            {{-- লোডিং মেসেজ --}}
            <div x-show="activeTab === 'facebook' && !fbLoaded" class="text-center text-xs text-red-400 mt-2">
                Loading Facebook SDK...
            </div>
        </div>
    </div>

@elseif(($config['provider'] ?? 'local') === 'facebook' && $facebookSdkUrl)
    {{-- শুধু ফেসবুক মোড --}}
    @once
        <div id="fb-root"></div>
        @push('scripts')
            <script>
                window.fbAsyncInit = function() {
                    if (window.FB) {
                        window.FB.init({
                            appId: '{{ data_get($config, 'facebook.app_id') }}',
                            xfbml: true,
                            version: 'v18.0',
                        });
                    }
                }
            </script>
            <script id="facebook-jssdk" async defer crossorigin="anonymous" src="{{ $facebookSdkUrl }}"></script>
        @endpush
    @endonce

    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg p-4 flex justify-center">
        <div class="fb-comments" data-href="{{ $canonicalUrl }}" data-width="100%" data-numposts="5"></div>
    </div>

@else
    {{-- শুধু লোকাল মোড --}}
    <livewire:frontend.comments :commentable="$commentable" :comment-relation="$commentRelation" />
@endif
