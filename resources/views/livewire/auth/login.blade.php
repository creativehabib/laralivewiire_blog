<x-layouts.auth>
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Log in to your account')" :description="__('Enter your email and password below to log in')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form id="login-form" method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Email address')"
                :value="old('email')"
                type="email"
                required
                autofocus
                autocomplete="email"
                placeholder="email@example.com"
            />

            <!-- Password -->
            <div class="relative">
                <flux:input
                    name="password"
                    :label="__('Password')"
                    type="password"
                    required
                    autocomplete="current-password"
                    :placeholder="__('Password')"
                    viewable
                />

                @if (Route::has('password.request'))
                    <flux:link class="absolute top-0 text-sm end-0" :href="route('password.request')" wire:navigate>
                        {{ __('Forgot your password?') }}
                    </flux:link>
                @endif
            </div>

            @php($recaptchaEnabled = filter_var(setting('recaptcha_enabled', config('services.recaptcha.enabled')), FILTER_VALIDATE_BOOLEAN))
            @php($recaptchaSiteKey = trim((string) setting('recaptcha_site_key', config('services.recaptcha.site_key'))))
            @php($recaptchaKeyType = (string) setting('recaptcha_key_type', config('services.recaptcha.key_type', 'v2_checkbox')))

            @if ($recaptchaEnabled && $recaptchaSiteKey)
                <div class="space-y-2">
                    @if ($recaptchaKeyType === 'v3_score')
                        <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
                    @else
                        <div class="g-recaptcha" data-sitekey="{{ $recaptchaSiteKey }}"></div>
                    @endif

                    @error('g-recaptcha-response')
                        <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            @endif

            <!-- Remember Me -->
            <flux:checkbox name="remember" :label="__('Remember me')" :checked="old('remember')" />

            <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" class="w-full" data-test="login-button">
                    {{ __('Log in') }}
                </flux:button>
            </div>
        </form>

        @if (Route::has('register'))
            <div class="space-x-1 text-sm text-center rtl:space-x-reverse text-zinc-600 dark:text-zinc-400">
                <span>{{ __('Don\'t have an account?') }}</span>
                <flux:link :href="route('register')" wire:navigate>{{ __('Sign up') }}</flux:link>
            </div>
        @endif
    </div>

    @if ($recaptchaEnabled && $recaptchaSiteKey)
        @push('scripts')
            @if ($recaptchaKeyType === 'v3_score')
                <script src="https://www.google.com/recaptcha/api.js?render={{ $recaptchaSiteKey }}"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const form = document.getElementById('login-form');
                        if (!form || typeof grecaptcha === 'undefined') {
                            return;
                        }

                        form.addEventListener('submit', function (event) {
                            event.preventDefault();

                            grecaptcha.ready(function () {
                                grecaptcha.execute('{{ $recaptchaSiteKey }}', { action: 'login' }).then(function (token) {
                                    const input = form.querySelector('#g-recaptcha-response');
                                    if (input) {
                                        input.value = token;
                                    }

                                    form.submit();
                                });
                            });
                        });
                    });
                </script>
            @else
                <script src="https://www.google.com/recaptcha/api.js" async defer></script>
            @endif
        @endpush
    @endif
</x-layouts.auth>
