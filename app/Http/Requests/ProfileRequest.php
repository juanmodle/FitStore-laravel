<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('account.access') ?? false;
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
            'email' => ['required', 'string', 'email', 'max:254', Rule::unique('users', 'email')->ignore($this->user()->id)],
            'phone' => ['nullable', 'string', 'max:30', 'regex:/^[+0-9 ()-]{6,30}$/'],
            'locale' => ['required', Rule::in(['es', 'en'])],
            'current_password' => [
                Rule::requiredIf($this->input('email') !== $this->user()->email),
                'nullable', 'string', 'current_password:web',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Introduce tu nombre.',
            'email.email' => 'Introduce un correo válido.',
            'email.unique' => 'Ese correo ya está registrado.',
            'phone.regex' => 'Introduce un teléfono válido.',
            'locale.in' => 'Selecciona español o inglés.',
            'current_password.required' => 'Confirma tu contraseña actual para cambiar el correo.',
            'current_password.current_password' => 'La contraseña actual no es correcta.',
        ];
    }
}
