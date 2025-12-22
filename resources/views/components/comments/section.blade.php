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
            colorScheme: document.documentElement.classList.contains('dark') ? 'dark' : 'light',

            getColorScheme() {
                return document.documentElement.classList.contains('dark') ? 'dark' : 'light';
            },

            syncColorScheme() {
                this.colorScheme = this.getColorScheme();

                const fbElement = this.$refs.fbComments;

                if (fbElement) {
                    fbElement.setAttribute('data-colorscheme', this.colorScheme);

                    if (this.activeTab === 'facebook' && this.fbLoaded && window.FB) {
                        window.FB.XFBML.parse(document.getElementById('fb-tab-content'));
                    }
                }
            },

            observeThemeChanges() {
                const observer = new MutationObserver(() => this.syncColorScheme());
                observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });

                const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');

                if (mediaQuery?.addEventListener) {
                    mediaQuery.addEventListener('change', () => this.syncColorScheme());
                }
            },

            init() {
                this.observeThemeChanges();
                this.syncColorScheme();
            },

            // ট্যাব পরিবর্তনের ফাংশন
            ensureFacebookSdk() {
                return new Promise((resolve) => {
                    if (! this.sdkUrl) {
                        resolve();
                        return;
                    }

                    if (this.fbLoaded || (typeof window !== 'undefined' && window.FB)) {
                        this.fbLoaded = true;
                        resolve();
                        return;
                    }

                    if (this.sdkUrl && ! document.getElementById('facebook-jssdk')) {
                        const fbScript = document.createElement('script');
                        fbScript.id = 'facebook-jssdk';
                        fbScript.async = true;
                        fbScript.defer = true;
                        fbScript.src = this.sdkUrl;
                        fbScript.crossOrigin = 'anonymous';
                        document.body.appendChild(fbScript);
                    }

                    const waitForFb = () => {
                        if (typeof window !== 'undefined' && window.FB) {
                            this.fbLoaded = true;
                            resolve();
                        } else {
                            setTimeout(waitForFb, 150);
                        }
                    };

                    waitForFb();
                });
            },

            loadFacebook() {
                this.activeTab = 'facebook';
                this.syncColorScheme();

                this.ensureFacebookSdk().then(() => {
                    if (window.FB) {
                        window.FB.XFBML.parse(document.getElementById('fb-tab-content'));
                    }
                });
            }
        }"
        x-init="init()"
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
        {{-- x-show এর বদলে hidden ক্লাস টগল করা হচ্ছে --}}
        <div
            id="fb-tab-content"
            class="p-4 bg-white dark:bg-slate-800"
            :class="activeTab === 'facebook' ? 'block' : 'hidden'"
        >
            {{-- যদি লেআউটে স্ক্রিপ্ট থাকে তবে এখানে আর @push দরকার নেই --}}
            {{-- কিন্তু যদি লেআউটে না থাকে তবে নিচের অংশটুকু রাখা লাগবে --}}
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
                        {{-- nonce বাদ দেওয়া হয়েছে কারণ এটি 403 এরর করছিল --}}
                        <script id="facebook-jssdk" async defer crossorigin="anonymous" src="{{ $facebookSdkUrl }}"></script>
                    @endpush
                @endonce
            @endif

            <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-4 bg-white flex justify-center">
                <div class="fb-comments"
                     x-ref="fbComments"
                     data-href="{{ $canonicalUrl }}"
                     data-width="100%"
                     data-numposts="5"
                     :data-colorscheme="colorScheme">
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

    <div
        x-data="{
            colorScheme: document.documentElement.classList.contains('dark') ? 'dark' : 'light',
            fbLoaded: typeof window !== 'undefined' && !!window.FB,

            getColorScheme() {
                return document.documentElement.classList.contains('dark') ? 'dark' : 'light';
            },

            refreshFacebook() {
                if (this.fbLoaded && window.FB && this.$refs.fbCommentsOnly) {
                    window.FB.XFBML.parse(this.$refs.fbCommentsOnly.parentElement);
                }
            },

            syncColorScheme() {
                this.colorScheme = this.getColorScheme();
                this.$refs.fbCommentsOnly?.setAttribute('data-colorscheme', this.colorScheme);
                this.refreshFacebook();
            },

            observeThemeChanges() {
                const observer = new MutationObserver(() => this.syncColorScheme());
                observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });

                const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');

                if (mediaQuery?.addEventListener) {
                    mediaQuery.addEventListener('change', () => this.syncColorScheme());
                }
            },

            init() {
                this.observeThemeChanges();
                this.syncColorScheme();

                const waitForFb = setInterval(() => {
                    if (window.FB) {
                        this.fbLoaded = true;
                        clearInterval(waitForFb);
                        this.refreshFacebook();
                    }
                }, 200);
            }
        }"
        x-init="init()"
        class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg p-4 flex justify-center"
    >
        <div
            class="fb-comments"
            x-ref="fbCommentsOnly"
            data-href="{{ $canonicalUrl }}"
            data-width="100%"
            data-numposts="5"
            :data-colorscheme="colorScheme"
        ></div>
    </div>

@else
    {{-- শুধু লোকাল মোড --}}
    <livewire:frontend.comments :commentable="$commentable" :comment-relation="$commentRelation" />
@endif
