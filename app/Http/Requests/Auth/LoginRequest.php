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

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // 1. Preparamos los datos en el idioma de Active Directory
        $ldapCredentials = [
            'mail' => $this->input('email'), 
            'password' => $this->input('password'),
        ];

        // 2. INTENTO A: Servidor de la Universidad (Active Directory)
        if (\Illuminate\Support\Facades\Auth::attempt($ldapCredentials, $this->boolean('remember'))) {
            \Illuminate\Support\Facades\RateLimiter::clear($this->throttleKey());
            return; // ¡Login exitoso por AD!
        }

        // 3. INTENTO B: Base de datos Local (El Respaldo Infalible)
        // Buscamos si el correo existe físicamente en nuestra tabla 'users'
        $localUser = \App\Models\User::where('email', $this->input('email'))->first();

        // Si el usuario existe Y su contraseña local coincide con la que escribió
        if ($localUser && \Illuminate\Support\Facades\Hash::check($this->input('password'), $localUser->password)) {
            
            // Le iniciamos sesión forzosamente (Bypass de LDAP)
            \Illuminate\Support\Facades\Auth::login($localUser, $this->boolean('remember'));
            \Illuminate\Support\Facades\RateLimiter::clear($this->throttleKey());
            return; // ¡Login exitoso por Base de Datos Local!
        }

        // 4. Si no está en la Universidad ni en la Base de datos local, lo rechazamos
        \Illuminate\Support\Facades\RateLimiter::hit($this->throttleKey());

        throw \Illuminate\Validation\ValidationException::withMessages([
            'email' => trans('auth.failed'), // 'Estas credenciales no coinciden con nuestros registros.'
        ]);
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
