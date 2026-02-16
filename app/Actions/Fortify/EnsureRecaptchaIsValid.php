<?php

namespace App\Actions\Fortify;

use App\Rules\RecaptchaRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EnsureRecaptchaIsValid
{
    /**
     * Validate recaptcha response before authentication.
     */
    public function __invoke(Request $request, $next)
    {
        Validator::make($request->all(), [
            'g-recaptcha-response' => ['required', new RecaptchaRule()],
        ])->validate();

        return $next($request);
    }
}
