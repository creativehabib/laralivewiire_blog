@props([
    'commentable',
    'commentRelation' => 'comments',
    'commentConfig' => null,
    'canonicalUrl' => request()->url(),
])

@php($config = $commentConfig ?? \App\Support\CommentConfig::get())
@php($facebookSdkUrl = \App\Support\CommentConfig::facebookSdkUrl($config))

{{-- Facebook SDK Script Load --}}
@if(($config['provider'] ?? 'local') !== 'local' && $facebookSdkUrl)
    @once
        <div id="fb-root"></div>
        @push('scripts')
            <script async defer crossorigin="anonymous" src="{{ $facebookSdkUrl }}" nonce="fb-comments"></script>
        @endpush
    @endonce
@endif

@if(($config['provider'] ?? 'local') === 'both')
    <div
        x-data="{
            activeTab: 'default',
            refreshFacebook() {
                // ট্যাব চেঞ্জ হওয়ার পর ১০০ms অপেক্ষা করে FB রেন্ডার করবে
                setTimeout(() => {
                    if (window.FB) {
                        window.FB.XFBML.parse();
                    }
                }, 100);
            }
        }"
        class="border border-slate-200 dark:border-slate-700 rounded-lg overflow-hidden bg-white dark:bg-slate-800"
    >
        {{-- Tabs --}}
        <div class="flex border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60">
            <button
                type="button"
                class="flex-1 px-4 py-2 text-sm font-semibold focus:outline-none transition-colors"
                :class="activeTab === 'default' ? 'text-blue-600 bg-white dark:bg-slate-800 border-b-2 border-blue-600' : 'text-slate-600 dark:text-slate-300'"
                @click="activeTab = 'default'"
            >
                Default comments
            </button>
            <button
                type="button"
                class="flex-1 px-4 py-2 text-sm font-semibold focus:outline-none transition-colors border-l border-slate-200 dark:border-slate-700"
                :class="activeTab === 'facebook' ? 'text-blue-600 bg-white dark:bg-slate-800 border-b-2 border-blue-600' : 'text-slate-600 dark:text-slate-300'"
                @click="activeTab = 'facebook'; refreshFacebook()" {{-- ক্লিক করলে রিফ্রেশ হবে --}}
            >
                Facebook comments
            </button>
        </div>

        {{-- Default Comments --}}
        <div class="p-4" x-show="activeTab === 'default'">
            <livewire:frontend.comments :commentable="$commentable" :comment-relation="$commentRelation" />
        </div>

        {{-- Facebook Comments --}}
        <div class="p-4" x-show="activeTab === 'facebook'" x-cloak>
            <div class="bg-white p-2 rounded flex justify-center min-h-[150px]">
                <div class="fb-comments"
                     data-href="{{ $canonicalUrl }}"
                     data-width="100%"
                     data-numposts="5"
                     data-colorscheme="light"> {{-- FB Dark mode সাপোর্ট করে না, তাই light --}}
                </div>
            </div>
        </div>
    </div>
@else
    {{-- Only Local or Only Facebook fallback --}}
    <livewire:frontend.comments :commentable="$commentable" :comment-relation="$commentRelation" />
@endif
