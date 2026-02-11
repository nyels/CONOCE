<?php

namespace App\Http\Requests;

use App\Rules\StrongPassword;
use Illuminate\Foundation\Http\FormRequest;
use Src\Domain\Shared\Enums\UserRole;

/**
 * Request para crear usuarios del sistema
 *
 * Campos obligatorios:
 * - username: identificador único para login
 * - password: contraseña segura con requisitos específicos
 * - password_confirmation: confirmación de contraseña
 * - staff_id: personal asociado (obligatorio)
 * - role: rol del usuario en el sistema
 */
class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->canManageUsers();
    }

    public function rules(): array
    {
        return [
            // Username único para login (obligatorio)
            'username' => [
                'required',
                'string',
                'min:4',
                'max:50',
                'regex:/^[a-zA-Z][a-zA-Z0-9_\.]*$/',
                'unique:users,username',
            ],

            // Contraseña con requisitos de seguridad (obligatoria)
            'password' => [
                'required',
                'string',
                new StrongPassword(null, $this->input('username')),
            ],

            // Confirmar contraseña (obligatoria)
            'password_confirmation' => [
                'required',
                'string',
                'same:password',
            ],

            // Personal asociado (obligatorio)
            'staff_id' => [
                'required',
                'integer',
                'exists:staff,id',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $hasUser = \App\Models\Staff::where('id', $value)
                            ->whereHas('user')
                            ->exists();
                        if ($hasUser) {
                            $fail('Este personal ya tiene un usuario asignado.');
                        }
                    }
                },
            ],

            // Rol (obligatorio)
            'role' => [
                'required',
                'string',
                'in:' . implode(',', array_column(UserRole::cases(), 'value')),
            ],

            // Estado (opcional, default true)
            'is_active' => [
                'nullable',
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'El username es obligatorio.',
            'username.min' => 'El username debe tener al menos 4 caracteres.',
            'username.max' => 'El username no puede exceder 50 caracteres.',
            'username.regex' => 'El username debe empezar con letra y solo puede contener letras, números, guiones bajos y puntos.',
            'username.unique' => 'Este username ya está en uso.',

            'password.required' => 'La contraseña es obligatoria.',

            'password_confirmation.required' => 'La confirmación de contraseña es obligatoria.',
            'password_confirmation.same' => 'Las contraseñas no coinciden.',

            'staff_id.required' => 'El personal asociado es obligatorio.',
            'staff_id.exists' => 'El personal seleccionado no existe.',

            'role.required' => 'El rol es obligatorio.',
            'role.in' => 'El rol seleccionado no es válido.',
        ];
    }

    public function attributes(): array
    {
        return [
            'username' => 'username',
            'password' => 'contraseña',
            'password_confirmation' => 'confirmación de contraseña',
            'staff_id' => 'personal asociado',
            'role' => 'rol',
            'is_active' => 'estado',
        ];
    }

    /**
     * Preparar datos antes de validación
     */
    protected function prepareForValidation(): void
    {
        // Normalizar username a minúsculas
        if ($this->has('username')) {
            $this->merge([
                'username' => strtolower(trim($this->input('username'))),
            ]);
        }
    }
}
