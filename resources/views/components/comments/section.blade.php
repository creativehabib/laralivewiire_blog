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

            // ট্যাব পরিবর্তনের ফাংশন
            loadFacebook() {
                this.activeTab = 'facebook';

                // ১০০ মিলি-সেকেন্ড অপেক্ষা করে ফেসবুককে রেন্ডার করতে বলা
                setTimeout(() => {
                    if (window.FB) {
                        window.FB.XFBML.parse(document.getElementById('fb-tab-content'));
                    }
                }, 100);
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
                        {{-- nonce বাদ দেওয়া হয়েছে কারণ এটি 403 এরর করছিল --}}
                        <script async defer crossorigin="anonymous" src="{{ $facebookSdkUrl }}"></script>
                    @endpush
                @endonce
            @endif

            <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-4 bg-white dark:bg-slate-700 flex justify-center">
                <div class="fb-comments"
                     data-href="{{ $canonicalUrl }}"
                     data-width="100%"
                     data-numposts="5">
                </div>
            </div>

            {{-- লোডিং মেসেজ --}}
            <div x-show="activeTab === 'facebook' && !window.FB" class="text-center text-xs text-red-400 mt-2">
                Loading Facebook SDK...
            </div>
        </div>
    </div>

@elseif(($config['provider'] ?? 'local') === 'facebook' && $facebookSdkUrl)
    {{-- শুধু ফেসবুক মোড --}}
    @once
        <div id="fb-root"></div>
        @push('scripts')
            <script async defer crossorigin="anonymous" src="{{ $facebookSdkUrl }}"></script>
        @endpush
    @endonce

    <div class="bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-700 rounded-lg p-4 flex justify-center">
        <div class="fb-comments" data-href="{{ $canonicalUrl }}" data-width="100%" data-numposts="5"></div>
    </div>

@else
    {{-- শুধু লোকাল মোড --}}
    <livewire:frontend.comments :commentable="$commentable" :comment-relation="$commentRelation" />
@endif
