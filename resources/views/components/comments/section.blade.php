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
        x-data="{ activeTab: 'default' }"
        class="border border-slate-200 dark:border-slate-700 rounded-lg overflow-hidden bg-white dark:bg-slate-800"
    >
        <div class="flex border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60">
            <button
                type="button"
                class="flex-1 px-4 py-2 text-sm font-semibold focus:outline-none transition-colors"
                :class="activeTab === 'default' ? 'text-primary-dark dark:text-primary-light bg-white dark:bg-slate-800' : 'text-slate-600 dark:text-slate-300'"
                @click="activeTab = 'default'"
            >
                Default comments
            </button>
            <button
                type="button"
                class="flex-1 px-4 py-2 text-sm font-semibold focus:outline-none transition-colors border-l border-slate-200 dark:border-slate-700"
                :class="activeTab === 'facebook' ? 'text-primary-dark dark:text-primary-light bg-white dark:bg-slate-800' : 'text-slate-600 dark:text-slate-300'"
                @click="activeTab = 'facebook'"
            >
                Facebook comments
            </button>
        </div>

        <div class="p-4" x-show="activeTab === 'default'">
            <livewire:frontend.comments :commentable="$commentable" :comment-relation="$commentRelation" />
        </div>

        <div class="p-4" x-show="activeTab === 'facebook'" x-cloak>
            @if($facebookSdkUrl)
                @once
                    <div id="fb-root"></div>
                    @push('scripts')
                        <script async defer crossorigin="anonymous" src="{{ $facebookSdkUrl }}" nonce="fb-comments"></script>
                    @endpush
                @endonce
            @endif

            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                <div class="fb-comments" data-href="{{ $canonicalUrl }}" data-width="100%" data-numposts="5"></div>
            </div>
        </div>
    </div>
@elseif(($config['provider'] ?? 'local') === 'facebook' && $facebookSdkUrl)
    @once
        <div id="fb-root"></div>
        @push('scripts')
            <script async defer crossorigin="anonymous" src="{{ $facebookSdkUrl }}" nonce="fb-comments"></script>
        @endpush
    @endonce

    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg p-4">
        <div class="fb-comments" data-href="{{ $canonicalUrl }}" data-width="100%" data-numposts="5"></div>
    </div>
@else
    <livewire:frontend.comments :commentable="$commentable" :comment-relation="$commentRelation" />
@endif
