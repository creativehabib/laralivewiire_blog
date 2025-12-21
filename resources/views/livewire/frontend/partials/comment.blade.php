@props(['comment', 'level' => 0, 'threadDepth' => 0, 'threaded' => false, 'allowComments' => true, 'showAvatar' => true])

@php
    $isNested = $level > 0;
    $canReply = $allowComments && ($threaded ? ($threadDepth <= 0 || $level < ($threadDepth - 1)) : $level === 0);
@endphp

<div class="{{ $isNested ? 'pl-6 mt-2 space-y-2 border-l border-slate-200 dark:border-slate-700' : '' }}">
    <article class="p-3 rounded-lg border border-slate-200 dark:border-slate-700 {{ $isNested ? 'bg-white dark:bg-slate-900/50' : 'bg-slate-50 dark:bg-slate-900/60' }}">
        <div class="flex {{ $showAvatar && $comment->avatar_url ? 'gap-3' : 'gap-0' }}">
            @if($showAvatar && $comment->avatar_url)
                <img src="{{ $comment->avatar_url }}" alt="{{ $comment->name }}" class="w-10 h-10 rounded-full object-cover bg-white border border-slate-200 dark:border-slate-700">
            @endif

            <div class="flex-1 space-y-1">
                    <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                        <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $comment->name }}</span>
                        <div class="flex items-center gap-3">
                            @if($canReply)
                                <button type="button" wire:click="setReply({{ $comment->id }})" class="text-blue-600 hover:underline cursor-pointer">উত্তর দিন</button>
                            @endif
                            <span>{{ $comment->created_at?->diffForHumans() }}</span>
                        </div>
                    </div>

                @if($comment->parent)
                    <div class="text-xs text-slate-500 dark:text-slate-400">
                        <span class="italic">{{ $comment->parent->name }}</span> -কে উত্তর
                    </div>
                @endif

                <div class="text-sm text-slate-700 dark:text-slate-100 whitespace-pre-line">
                    {{ $comment->content }}
                </div>
            </div>
        </div>
    </article>

    @if($parentId === $comment->id && $canReply)
        <div class="mt-2">
            @include('livewire.frontend.partials.comment-form', ['formId' => 'comment-form'])
        </div>
    @endif

    @if($comment->repliesRecursive->isNotEmpty())
        <div class="space-y-2">
            @foreach($comment->repliesRecursive as $reply)
                @include('livewire.frontend.partials.comment', [
                    'comment' => $reply,
                    'level' => $level + 1,
                    'threadDepth' => $threadDepth,
                    'threaded' => $threaded,
                    'allowComments' => $allowComments,
                ])
            @endforeach
        </div>
    @endif
</div>
