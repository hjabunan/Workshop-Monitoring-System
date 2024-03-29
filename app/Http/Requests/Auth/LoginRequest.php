<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Auth\Events\Failed;

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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            //     RateLimiter::hit($this->throttleKey());

            //     throw ValidationException::withMessages([
            //         'email' => trans('auth.failed'),
            //     ]);
        // }

        $user =  User::where('email',$this->login)
            ->orWhere('idnum',$this->login)
            ->first();

        if (!$user) {
            // User doesn't exist
            session(['failed_login' => [
                'login' => $this->login,
                'reason' => __('auth.failed'),
            ]]);
    
            event(new Failed($this, ['login' => $this->login], __('auth.failed')));
    
            RateLimiter::hit($this->throttleKey());
    
            throw ValidationException::withMessages([
                'login' => __('auth.failed'),
            ]);
        }
    
        // Proceed to password check
        if (!Hash::check($this->password, $user->password)) {
            session(['failed_login' => [
                'login' => $this->login,
                'reason' => __('auth.password'),
            ]]);
    
            event(new Failed($this, ['login' => $this->login], __('auth.password')));
    
            RateLimiter::hit($this->throttleKey());
    
            throw ValidationException::withMessages([
                'login' => __('auth.password'),
            ]);
        }else{
            // Check if the user is inactive
            if ($user->status == 0) {
                session(['failed_login' => [
                    'login' => $this->login,
                    'reason' => __('auth.inactive'),
                ]]);
        
                event(new Failed($this, ['login' => $this->login], __('auth.inactive')));
        
                throw ValidationException::withMessages([
                    'login' => __('auth.inactive'),
                ]);
            }
        }


    
        Auth::login($user, $this->boolean(key:'remember'));
        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
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
        return Str::transliterate(Str::lower($this->input('email')).'|'.$this->ip());
    }
}
