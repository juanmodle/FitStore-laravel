<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('users.view') ?? false;
    }

    public function rules(): array
    {
        $untilRules = ['nullable', 'date_format:Y-m-d'];
        if ($this->filled('registered_from')) {
            $untilRules[] = 'after_or_equal:registered_from';
        }

        return [
            'role' => ['nullable', 'string', Rule::exists('roles', 'name')->where('guard_name', 'web')],
            'registered_from' => ['nullable', 'date_format:Y-m-d'],
            'registered_until' => $untilRules,
            'verified_only' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'role.exists' => 'Selecciona un rol válido.',
            'registered_from.date_format' => 'Introduce una fecha inicial válida.',
            'registered_until.date_format' => 'Introduce una fecha final válida.',
            'registered_until.after_or_equal' => 'La fecha final no puede ser anterior a la inicial.',
            'verified_only.boolean' => 'El filtro de verificación no es válido.',
        ];
    }
}
