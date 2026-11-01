<?php

namespace App\Http\Requests;

use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return ! auth()->check();
    }

    protected function prepareForValidation(): void
    {
        if (is_string($this->input('email'))) {
            $this->merge(['email' => mb_strtolower(trim($this->input('email')))]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:254', Rule::unique('users', 'email')],
            'password' => [
                'required', 'string', 'max:72', Password::min(8)->letters()->numbers(), 'confirmed',
                function (string $attribute, mixed $value, Closure $fail) {
                    if (is_string($value) && strlen($value) > 72) {
                        $fail('La contraseña es demasiado larga.');
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Introduce tu nombre.',
            'email.required' => 'Introduce tu correo.',
            'email.email' => 'Introduce un correo válido.',
            'email.unique' => 'Ese correo ya está registrado.',
            'password.required' => 'Introduce una contraseña.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.letters' => 'La contraseña debe incluir letras.',
            'password.numbers' => 'La contraseña debe incluir números.',
        ];
    }
}
