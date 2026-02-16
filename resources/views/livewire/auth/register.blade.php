<x-layouts.auth>
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Create an account')" :description="__('Enter your details below to create your account')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form id="register-form" method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-6">
            @csrf
            <!-- Name -->
            <flux:input
                name="name"
                :label="__('Name')"
                :value="old('name')"
                type="text"
                required
                autofocus
                autocomplete="name"
                :placeholder="__('Full name')"
            />

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Email address')"
                :value="old('email')"
                type="email"
                required
                autocomplete="email"
                placeholder="email@example.com"
            />

            <!-- Password -->
            <flux:input
                name="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Password')"
                viewable
            />

            <!-- Confirm Password -->
            <flux:input
                name="password_confirmation"
                :label="__('Confirm password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Confirm password')"
                viewable
            />

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

            <div class="flex items-center justify-end">
                <flux:button type="submit" variant="primary" class="w-full" data-test="register-user-button">
                    {{ __('Create account') }}
                </flux:button>
            </div>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
            <span>{{ __('Already have an account?') }}</span>
            <flux:link :href="route('login')" wire:navigate>{{ __('Log in') }}</flux:link>
        </div>
    </div>

    @if ($recaptchaEnabled && $recaptchaSiteKey)
        @push('scripts')
            @if ($recaptchaKeyType === 'v3_score')
                <script src="https://www.google.com/recaptcha/api.js?render={{ $recaptchaSiteKey }}"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const form = document.getElementById('register-form');
                        if (!form || typeof grecaptcha === 'undefined') {
                            return;
                        }

                        form.addEventListener('submit', function (event) {
                            event.preventDefault();

                            grecaptcha.ready(function () {
                                grecaptcha.execute('{{ $recaptchaSiteKey }}', { action: 'register' }).then(function (token) {
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
