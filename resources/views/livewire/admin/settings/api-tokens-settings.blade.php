<div class="space-y-6 py-4 text-slate-900 dark:text-slate-100">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h1 class="text-lg font-semibold">{{ __('API / Developer') }}</h1>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    {{ __('Create, rotate, and monitor API tokens for external apps and integrations.') }}
                </p>
            </div>
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
            <div class="mt-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-900 dark:border-emerald-900/50 dark:bg-emerald-900/20 dark:text-emerald-200">
                <p class="font-semibold">{{ __('Token generated successfully. Copy and store it now.') }}</p>
                <p class="mt-1 text-xs">{{ __('For security reasons, this value is shown only once.') }}</p>
                <div class="mt-2 break-all rounded-lg bg-white/80 p-3 font-mono text-xs dark:bg-slate-900/70">{{ $plain_text_token }}</div>
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

        <div class="mt-4 grid gap-4 xl:grid-cols-2">
            <div class="rounded-lg border border-slate-200 p-4 dark:border-slate-700">
                <h3 class="text-sm font-semibold">{{ __('Get Token (Login) Example') }}</h3>
                <pre class="mt-2 overflow-x-auto rounded-lg bg-slate-950 p-3 text-xs text-slate-100"><code>curl -X POST "{{ route('api.v1.auth.token.store') }}" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"secret","device_name":"android-app","expires_in_days":30}'</code></pre>
            </div>

            <div class="rounded-lg border border-slate-200 p-4 dark:border-slate-700">
                <h3 class="text-sm font-semibold">{{ __('Data Access Example') }}</h3>
                <pre class="mt-2 overflow-x-auto rounded-lg bg-slate-950 p-3 text-xs text-slate-100"><code>curl -X GET "{{ route('api.v1.posts.index') }}?per_page=10&search=laravel&include_content=false" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"</code></pre>
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
