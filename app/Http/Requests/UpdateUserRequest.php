<?php

namespace App\Http\Requests;

use App\Rules\StrongPassword;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Src\Domain\Shared\Enums\UserRole;

/**
 * Request para actualizar usuarios del sistema
 *
 * Campos obligatorios:
 * - username: identificador único para login
 * - staff_id: personal asociado
 * - role: rol del usuario
 *
 * Campos opcionales:
 * - password: solo si se quiere cambiar (con sus requisitos de seguridad)
 * - password_confirmation: requerido si se proporciona password
 */
class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->canManageUsers();
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id;

        $rules = [
            // Username único para login (obligatorio)
            'username' => [
                'required',
                'string',
                'min:4',
                'max:50',
                'regex:/^[a-zA-Z][a-zA-Z0-9_\.]*$/',
                Rule::unique('users', 'username')->ignore($userId),
            ],

            // Personal asociado (obligatorio)
            'staff_id' => [
                'required',
                'integer',
                'exists:staff,id',
                function ($attribute, $value, $fail) use ($userId) {
                    if ($value) {
                        $hasOtherUser = \App\Models\Staff::where('id', $value)
                            ->whereHas('user', fn($q) => $q->where('id', '!=', $userId))
                            ->exists();
                        if ($hasOtherUser) {
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

            // Estado
            'is_active' => [
                'nullable',
                'boolean',
            ],
        ];

        // Contraseña solo requerida si se proporciona
        if ($this->filled('password')) {
            $rules['password'] = [
                'required',
                'string',
                new StrongPassword($userId, $this->input('username')),
            ];
            $rules['password_confirmation'] = [
                'required',
                'string',
                'same:password',
            ];
        } else {
            $rules['password'] = ['nullable'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'username.required' => 'El username es obligatorio.',
            'username.min' => 'El username debe tener al menos 4 caracteres.',
            'username.max' => 'El username no puede exceder 50 caracteres.',
            'username.regex' => 'El username debe empezar con letra y solo puede contener letras, números, guiones bajos y puntos.',
            'username.unique' => 'Este username ya está en uso.',

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
