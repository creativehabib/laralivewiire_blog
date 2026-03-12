<div class="space-y-6 py-4 text-slate-900 dark:text-slate-100">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h1 class="text-lg font-semibold">{{ __('API / Developer') }}</h1>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    {{ __('Create and manage API tokens for external apps like mobile apps or integrations.') }}
                </p>
            </div>
        </div>

        <div class="mt-6 grid gap-4 md:grid-cols-3">
            <div class="md:col-span-2">
                <label for="token_name" class="mb-2 block text-sm font-medium">{{ __('Token Name') }}</label>
                <input
                    id="token_name"
                    type="text"
                    wire:model.defer="token_name"
                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-800"
                    placeholder="{{ __('e.g. android-app') }}"
                >
                @error('token_name') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="expires_in_days" class="mb-2 block text-sm font-medium">{{ __('Expire In (Days)') }}</label>
                <input
                    id="expires_in_days"
                    type="number"
                    min="1"
                    max="365"
                    wire:model.defer="expires_in_days"
                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-800"
                    placeholder="{{ __('Optional') }}"
                >
                @error('expires_in_days') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mt-4 flex items-center gap-3">
            <button
                wire:click="generateToken"
                type="button"
                class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-700"
            >
                {{ __('Generate API Token') }}
            </button>
        </div>

        @if ($plain_text_token)
            <div class="mt-6 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900 dark:border-amber-900/50 dark:bg-amber-900/20 dark:text-amber-200">
                <p class="font-semibold">{{ __('Copy your token now. You will not be able to see it again.') }}</p>
                <div class="mt-2 rounded-lg bg-white/80 p-3 font-mono text-xs break-all dark:bg-slate-900/70">{{ $plain_text_token }}</div>
            </div>
        @endif
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
        <h2 class="text-base font-semibold">{{ __('Active Tokens') }}</h2>

        <div class="mt-4 overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-slate-700">
                <thead>
                    <tr class="text-left text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400">
                        <th class="px-3 py-2">{{ __('Name') }}</th>
                        <th class="px-3 py-2">{{ __('Created') }}</th>
                        <th class="px-3 py-2">{{ __('Last Used') }}</th>
                        <th class="px-3 py-2">{{ __('Expires') }}</th>
                        <th class="px-3 py-2 text-right">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($tokens as $token)
                        <tr>
                            <td class="px-3 py-3 font-medium">{{ $token->name }}</td>
                            <td class="px-3 py-3 text-slate-500 dark:text-slate-400">{{ $token->created_at?->format('M d, Y h:i A') }}</td>
                            <td class="px-3 py-3 text-slate-500 dark:text-slate-400">{{ $token->last_used_at?->diffForHumans() ?? __('Never') }}</td>
                            <td class="px-3 py-3 text-slate-500 dark:text-slate-400">{{ $token->expires_at?->format('M d, Y h:i A') ?? __('No expiry') }}</td>
                            <td class="px-3 py-3 text-right">
                                <button
                                    type="button"
                                    wire:click="revokeToken({{ $token->id }})"
                                    wire:confirm="{{ __('Are you sure you want to revoke this token?') }}"
                                    class="rounded-md border border-rose-200 px-3 py-1 text-xs font-medium text-rose-600 transition hover:bg-rose-50 dark:border-rose-900/60 dark:text-rose-300"
                                >
                                    {{ __('Revoke') }}
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-3 py-8 text-center text-slate-500 dark:text-slate-400">
                                {{ __('No API tokens have been created yet.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
