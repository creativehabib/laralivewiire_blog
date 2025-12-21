<div class="space-y-4">
    @if($successMessage)
        <div class="p-3 rounded-md bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm">
            {{ $successMessage }}
        </div>
    @endif

    @if(! $allowComments)
        <p class="text-sm text-slate-600 dark:text-slate-300">
            এই পোস্টের মন্তব্য বন্ধ রয়েছে।
        </p>
    @else
        @if(! $parentId)
            @include('livewire.frontend.partials.comment-form', ['formId' => 'comment-form'])
        @endif
    @endif

    <div class="space-y-3">
        <h3 class="text-sm font-semibold border-b pb-2 border-slate-200 dark:border-slate-700 flex items-center gap-2">
            সর্বশেষ মন্তব্য
            <span class="px-2 py-0.5 text-[11px] bg-slate-100 dark:bg-slate-800 rounded-full text-slate-700 dark:text-slate-200">
                {{ $comments->count() }}
            </span>
        </h3>

        @forelse($comments as $comment)
            @include('livewire.frontend.partials.comment', ['comment' => $comment])
        @empty
            <p class="text-sm text-slate-600 dark:text-slate-300">এখনো কোনো মন্তব্য নেই। প্রথম মন্তব্যটি করুন!</p>
        @endforelse
    </div>

    @push('scripts')
        <script>
            document.addEventListener('livewire:load', () => {
                Livewire.on('scrollToCommentForm', () => {
                    const form = document.getElementById('comment-form');

                    if (! form) {
                        return;
                    }

                    form.scrollIntoView({ behavior: 'smooth', block: 'start' });

                    const commentField = form.querySelector('textarea, input, select');

                    if (commentField) {
                        commentField.focus({ preventScroll: true });
                    }
                });
            });
        </script>
    @endpush
</div>
