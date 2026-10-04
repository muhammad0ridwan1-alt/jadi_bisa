<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $input = trim($this->input('email'));
        $password = $this->input('password');
        $remember = $this->boolean('remember');

        // Prepare email variations (supports index code like 'ar', 'da', 'sh' or domain aliases)
        $candidates = [];
        if (!str_contains($input, '@')) {
            $code = strtolower($input);
            $candidates[] = $code . '@jadibisa.com';
            $candidates[] = $code . '@bogoreducare.org';
        } else {
            $candidates[] = $input;
            if (str_ends_with($input, '@jadibisa.com')) {
                $candidates[] = str_replace('@jadibisa.com', '@bogoreducare.org', $input);
            } elseif (str_ends_with($input, '@bogoreducare.org')) {
                $candidates[] = str_replace('@bogoreducare.org', '@jadibisa.com', $input);
            }
        }

        $authenticated = false;
        foreach ($candidates as $email) {
            if (Auth::attempt(['email' => $email, 'password' => $password], $remember)) {
                $authenticated = true;
                break;
            }
        }

        if (!$authenticated) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
