<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class RecaptchaRule implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! filter_var(setting('recaptcha_enabled', config('services.recaptcha.enabled')), FILTER_VALIDATE_BOOLEAN)) {
            return;
        }

        $secret = trim((string) setting('recaptcha_secret_key', config('services.recaptcha.secret_key')));

        if ($secret === '' || blank($value)) {
            $fail(__('reCAPTCHA verification failed.'));

            return;
        }

        $response = Http::asForm()->timeout(10)->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $secret,
            'response' => (string) $value,
            'remoteip' => request()->ip(),
        ]);

        if (! $response->ok() || ! (bool) $response->json('success')) {
            $fail(__('reCAPTCHA verification failed. Please try again.'));
        }
    }
}
