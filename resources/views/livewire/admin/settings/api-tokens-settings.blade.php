<div class="space-y-6 py-4 text-slate-900 dark:text-slate-100">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h1 class="text-lg font-semibold">{{ __('API / Developer') }}</h1>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    {{ __('Create, rotate, and monitor API tokens for external apps and integrations.') }}
                </p>
            </div>
            <a
                href="{{ url('/docs/api') }}"
                target="_blank"
                class="inline-flex items-center rounded-lg border border-indigo-200 px-3 py-2 text-sm font-medium text-indigo-700 transition hover:bg-indigo-50 dark:border-indigo-800 dark:text-indigo-300 dark:hover:bg-indigo-900/40"
            >
                {{ __('View API Documentation') }}
            </a>
        </div>

        <div class="mt-6 grid gap-4 lg:grid-cols-2">
            <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-4 dark:border-slate-700 dark:bg-slate-800/60">
                <h2 class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ __('Integration Workflow') }}</h2>
                <ol class="mt-3 list-decimal space-y-2 pl-5 text-sm text-slate-600 dark:text-slate-300">
                    <li>{{ __('Create token with a clear app/device name (e.g. ios-production).') }}</li>
                    <li>{{ __('Store token only in secure storage (Keychain/Keystore/Server secrets).') }}</li>
                    <li>{{ __('Send token as Authorization: Bearer {token} in every API request.') }}</li>
                    <li>{{ __('Rotate before expiry and revoke compromised/unused tokens immediately.') }}</li>
                </ol>
            </div>

            <div class="rounded-xl border border-amber-200 bg-amber-50/80 p-4 dark:border-amber-700/40 dark:bg-amber-900/20">
                <h2 class="text-sm font-semibold text-amber-900 dark:text-amber-200">{{ __('Security & Rotation Policy') }}</h2>
                <ul class="mt-3 space-y-2 text-sm text-amber-900/85 dark:text-amber-100/90">
                    <li>{{ __('Default expiry is 30 days. Set shorter expiry for high-risk integrations.') }}</li>
                    <li>{{ __('Never hardcode tokens directly in mobile app source or public repositories.') }}</li>
                    <li>{{ __('Rotate token on a schedule (recommended every 30-90 days).') }}</li>
                    <li>{{ __('If suspected leakage occurs, revoke immediately and re-issue.') }}</li>
                </ul>
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
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                    {{ __('Leave empty for no expiry (not recommended for production).') }}
                </p>
            </div>
        </div>

        <div class="mt-4">
            <h3 class="mb-2 text-sm font-medium">{{ __('Token Scopes / Permissions') }}</h3>
            <div class="grid gap-2 sm:grid-cols-2">
                @foreach ($scopes as $scopeKey => $scopeLabel)
                    <label class="flex items-start gap-2 rounded-lg border border-slate-200 p-2 text-sm dark:border-slate-700">
                        <input type="checkbox" wire:model="selected_scopes" value="{{ $scopeKey }}" class="mt-0.5 rounded border-slate-300 focus:ring-indigo-500">
                        <span>
                            <span class="font-mono text-xs">{{ $scopeKey }}</span>
                            <span class="block text-slate-500 dark:text-slate-400">{{ __($scopeLabel) }}</span>
                        </span>
                    </label>
                @endforeach
            </div>
            @error('selected_scopes') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
            @error('selected_scopes.*') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
        </div>

        <div class="mt-4 flex items-center gap-3">
            <button
                wire:click="generateToken"
                type="button"
                class="rounded-lg px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-700"
            >
                {{ __('Generate API Token') }}
            </button>
        </div>

        @if ($plain_text_token)
            <div
                class="mt-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-900 dark:border-emerald-900/50 dark:bg-emerald-900/20 dark:text-emerald-200"
                x-data="{ visible: false, copied: false, token: @js($plain_text_token) }"
            >
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <div>
                        <p class="font-semibold">{{ __('Token generated successfully. Copy and store it now.') }}</p>
                        <p class="mt-1 text-xs">{{ __('For security reasons, this value is shown only once.') }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button
                            type="button"
                            @click="visible = !visible"
                            class="rounded-md border border-emerald-300 px-2 py-1 text-xs font-medium hover:bg-emerald-100 dark:border-emerald-800 dark:hover:bg-emerald-900/40"
                        >
                            <span x-text="visible ? '{{ __('Hide') }}' : '{{ __('Show') }}'"></span>
                        </button>
                        <button
                            type="button"
                            @click="navigator.clipboard.writeText(token); copied = true; setTimeout(() => copied = false, 1800)"
                            class="rounded-md border border-emerald-300 px-2 py-1 text-xs font-medium hover:bg-emerald-100 dark:border-emerald-800 dark:hover:bg-emerald-900/40"
                        >
                            <span x-text="copied ? '{{ __('Copied!') }}' : '{{ __('Copy') }}'"></span>
                        </button>
                    </div>
                </div>
                <div class="mt-2 break-all rounded-lg bg-white/80 p-3 font-mono text-xs dark:bg-slate-900/70" x-text="visible ? token : '••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••••'"></div>
            </div>
        @endif
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
        <h2 class="text-base font-semibold">{{ __('API Playbook (v1)') }}</h2>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
            {{ __('Base URL:') }} <span class="font-mono text-xs">{{ url('/api/v1') }}</span>
        </p>

        <div class="mt-4 grid gap-4 xl:grid-cols-2">
            <div class="rounded-lg border border-slate-200 p-4 dark:border-slate-700">
                <h3 class="text-sm font-semibold">{{ __('Authentication Endpoints') }}</h3>
                <ul class="mt-2 space-y-1 text-sm text-slate-600 dark:text-slate-300">
                    <li><span class="font-mono text-xs">POST {{ route('api.v1.auth.token.store') }}</span></li>
                    <li><span class="font-mono text-xs">DELETE {{ route('api.v1.auth.token.destroy') }}</span></li>
                </ul>
            </div>

            <div class="rounded-lg border border-slate-200 p-4 dark:border-slate-700">
                <h3 class="text-sm font-semibold">{{ __('Content Endpoints') }}</h3>
                <ul class="mt-2 space-y-1 text-sm text-slate-600 dark:text-slate-300">
                    <li><span class="font-mono text-xs">GET {{ route('api.v1.posts.index') }}</span></li>
                    <li><span class="font-mono text-xs">GET {{ route('api.v1.categories.index') }}</span></li>
                    <li><span class="font-mono text-xs">GET {{ route('api.v1.tags.index') }}</span></li>
                    <li><span class="font-mono text-xs">GET {{ route('api.v1.pages.index') }}</span></li>
                    <li><span class="font-mono text-xs">GET {{ route('api.v1.comments.index') }}</span></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
        <h2 class="text-base font-semibold">{{ __('Active Tokens') }}</h2>

        <div class="mt-4 overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-slate-700">
                <thead>
                    <tr class="text-left text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400">
                        <th class="px-3 py-2">{{ __('Name') }}</th>
                        <th class="px-3 py-2">{{ __('Scopes') }}</th>
                        <th class="px-3 py-2">{{ __('Last Used') }}</th>
                        <th class="px-3 py-2">{{ __('Last IP') }}</th>
                        <th class="px-3 py-2">{{ __('24h Calls') }}</th>
                        <th class="px-3 py-2">{{ __('Expires') }}</th>
                        <th class="px-3 py-2 text-right">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($tokens as $token)
                        @php
                            $daysToExpiry = $token->expires_at ? now()->diffInDays($token->expires_at, false) : null;
                            $expiryState = is_null($daysToExpiry)
                                ? 'none'
                                : ($daysToExpiry <= 1 ? 'critical' : ($daysToExpiry <= 7 ? 'warning' : 'healthy'));
                        @endphp
                        <tr>
                            <td class="px-3 py-3 font-medium">
                                @if ($editingTokenId === $token->id)
                                    <div class="flex items-center gap-2">
                                        <input type="text" wire:model.defer="editingTokenName" class="w-44 rounded-md border border-slate-300 px-2 py-1 text-xs dark:border-slate-700 dark:bg-slate-800">
                                        <button type="button" wire:click="saveTokenName" class="rounded border border-emerald-300 px-2 py-1 text-xs text-emerald-700">{{ __('Save') }}</button>
                                        <button type="button" wire:click="cancelEditingToken" class="rounded border border-slate-300 px-2 py-1 text-xs">{{ __('Cancel') }}</button>
                                    </div>
                                @else
                                    {{ $token->name }}
                                @endif
                            </td>
                            <td class="px-3 py-3 text-xs text-slate-500 dark:text-slate-400">
                                {{ collect($token->abilities ?? [])->implode(', ') ?: __('No scopes') }}
                            </td>
                            <td class="px-3 py-3 text-slate-500 dark:text-slate-400">{{ $token->last_used_at?->diffForHumans() ?? __('Never') }}</td>
                            <td class="px-3 py-3 font-mono text-xs text-slate-500 dark:text-slate-400">{{ $token->last_used_ip ?? __('N/A') }}</td>
                            <td class="px-3 py-3 text-slate-500 dark:text-slate-400">{{ $token->calls_last_24h ?? 0 }}</td>
                            <td class="px-3 py-3 text-slate-500 dark:text-slate-400">
                                @if (is_null($token->expires_at))
                                    <span class="rounded-full bg-slate-100 px-2 py-1 text-xs text-slate-700 dark:bg-slate-800 dark:text-slate-300">{{ __('No expiry') }}</span>
                                @elseif ($expiryState === 'critical')
                                    <span class="rounded-full bg-rose-100 px-2 py-1 text-xs text-rose-700 dark:bg-rose-900/30 dark:text-rose-300">{{ __('Expiring soon') }} · {{ $token->expires_at?->format('M d, Y h:i A') }}</span>
                                @elseif ($expiryState === 'warning')
                                    <span class="rounded-full bg-amber-100 px-2 py-1 text-xs text-amber-700 dark:bg-amber-900/30 dark:text-amber-300">{{ __('Expires within 7 days') }} · {{ $token->expires_at?->format('M d, Y h:i A') }}</span>
                                @else
                                    <span>{{ $token->expires_at?->format('M d, Y h:i A') }}</span>
                                @endif
                            </td>
                            <td class="px-3 py-3 text-right">
                                <div class="inline-flex items-center gap-2">
                                    @if ($editingTokenId !== $token->id)
                                        <button
                                            type="button"
                                            wire:click="startEditingToken({{ $token->id }})"
                                            class="rounded-md border border-slate-200 px-3 py-1 text-xs font-medium text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300"
                                        >
                                            {{ __('Edit') }}
                                        </button>
                                    @endif
                                    <button
                                        type="button"
                                        wire:click="revokeToken({{ $token->id }})"
                                        wire:confirm="{{ __('Are you sure you want to revoke this token?') }}"
                                        class="rounded-md border border-rose-200 px-3 py-1 text-xs font-medium text-rose-600 transition hover:bg-rose-50 dark:border-rose-900/60 dark:text-rose-300"
                                    >
                                        {{ __('Revoke') }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-3 py-12 text-center">
                                <p class="text-base font-medium text-slate-700 dark:text-slate-200">{{ __('No active tokens found') }}</p>
                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('Generate your first API token to start integrating mobile apps and services.') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($tokens->hasPages())
            <div class="mt-4">
                {{ $tokens->links() }}
            </div>
        @endif
    </div>
</div>
