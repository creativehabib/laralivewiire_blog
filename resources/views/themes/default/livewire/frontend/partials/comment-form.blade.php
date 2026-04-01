<form wire:submit.prevent="submit" class="space-y-3 text-sm" id="{{ $formId ?? 'comment-form' }}">
    @if($replyingTo)
        <div class="flex items-center justify-between px-3 py-2 bg-blue-50 dark:bg-blue-900/40 border border-blue-200 dark:border-blue-700 rounded-md">
            <div class="text-blue-700 dark:text-blue-100 text-xs">
                <span class="font-semibold">{{ $replyingTo }}</span> - এর উত্তরে লিখছেন
            </div>
            <button type="button" wire:click="cancelReply" class="text-xs text-blue-600 hover:underline cursor-pointer">বাতিল</button>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <div>
            <input type="text"
                   wire:model.defer="name"
                   placeholder="নাম"
                   class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 rounded-md bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary/60">
            @error('name')
                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <input type="email"
                   wire:model.defer="email"
                   placeholder="ইমেইল"
                   class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 rounded-md bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary/60">
            @error('email')
                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <input type="url"
               wire:model.defer="website"
               placeholder="ওয়েবসাইট (ঐচ্ছিক)"
               class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 rounded-md bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary/60">
        @error('website')
            <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <textarea rows="4"
                  wire:model.defer="content"
                  placeholder="আপনার মন্তব্য লিখুন…"
                  class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 rounded-md bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary/60"></textarea>
        @error('content')
            <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <button type="submit"
            class="px-4 py-2 bg-primary-dark text-white rounded-md text-sm font-semibold hover:bg-primary disabled:opacity-60 cursor-pointer"
            wire:loading.attr="disabled"
            wire:target="submit">
        <span wire:loading.remove wire:target="submit">মন্তব্য পাঠান</span>
        <span wire:loading wire:target="submit">পাঠানো হচ্ছে…</span>
    </button>
</form>
