<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Request para actualizar Personal
 * Validaciones estrictas con seguridad y trazabilidad
 */
class UpdateStaffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $staffId = $this->route('staff')?->id;

        return [
            // Datos personales - obligatorios
            'first_name' => [
                'required',
                'string',
                'min:2',
                'max:100',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\-\.]+$/',
            ],
            'paternal_surname' => [
                'required',
                'string',
                'min:2',
                'max:100',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\-\.]+$/',
            ],
            'maternal_surname' => [
                'required',
                'string',
                'min:2',
                'max:100',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\-\.]+$/',
            ],
            'birth_date' => [
                'required',
                'date',
                'before:today',
                'after:1940-01-01',
            ],

            // Identificadores fiscales mexicanos
            'curp' => [
                'nullable',
                'string',
                'size:18',
                'regex:/^[A-Z]{4}[0-9]{6}[HM][A-Z]{5}[0-9A-Z][0-9]$/i',
                Rule::unique('staff', 'curp')->ignore($staffId),
            ],
            'rfc' => [
                'nullable',
                'string',
                'min:12',
                'max:13',
                'regex:/^[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}$/i',
            ],

            // Contacto
            'phone' => [
                'nullable',
                'string',
                'regex:/^\d{10}$/',
            ],
            'mobile' => [
                'required',
                'string',
                'regex:/^\d{10}$/',
            ],

            // Puesto - obligatorio
            'position_id' => [
                'required',
                'integer',
                'exists:positions,id',
            ],

            // Fechas laborales
            'hire_date' => [
                'nullable',
                'date',
            ],
            'termination_date' => [
                'nullable',
                'date',
                'after:hire_date',
            ],

            // Emails - array de objetos
            'emails' => [
                'required',
                'array',
                'min:1',
                'max:5',
            ],
            'emails.*.email' => [
                'required',
                'email:rfc,dns',
                'max:100',
                'distinct',
            ],
            'emails.*.type' => [
                'required',
                'in:work,personal,other',
            ],
            'emails.*.is_primary' => [
                'nullable',
                'boolean',
            ],

            // Estado
            'is_active' => [
                'nullable',
                'boolean',
            ],

            // Notas
            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'El nombre es obligatorio.',
            'first_name.regex' => 'El nombre solo puede contener letras y espacios.',
            'paternal_surname.required' => 'El apellido paterno es obligatorio.',
            'paternal_surname.regex' => 'El apellido solo puede contener letras y espacios.',
            'maternal_surname.required' => 'El apellido materno es obligatorio.',
            'maternal_surname.min' => 'El apellido materno debe tener al menos 2 caracteres.',
            'maternal_surname.regex' => 'El apellido solo puede contener letras y espacios.',

            'birth_date.required' => 'La fecha de nacimiento es obligatoria.',

            'mobile.required' => 'El celular es obligatorio.',

            'curp.size' => 'El CURP debe tener exactamente 18 caracteres.',
            'curp.regex' => 'El formato del CURP no es válido.',
            'curp.unique' => 'Este CURP ya está registrado.',
            'rfc.regex' => 'El formato del RFC no es válido.',

            'phone.regex' => 'El teléfono debe tener exactamente 10 dígitos.',
            'mobile.regex' => 'El celular debe tener exactamente 10 dígitos.',

            'position_id.required' => 'El puesto es obligatorio.',
            'position_id.exists' => 'El puesto seleccionado no existe.',

            'birth_date.before' => 'La fecha de nacimiento debe ser anterior a hoy.',
            'termination_date.after' => 'La fecha de baja debe ser posterior a la fecha de alta.',

            'emails.required' => 'Debe agregar al menos un email.',
            'emails.min' => 'Debe agregar al menos un email.',
            'emails.max' => 'No puede agregar más de 5 emails.',
            'emails.*.email.required' => 'El email es obligatorio.',
            'emails.*.email.email' => 'El formato del email no es válido.',
            'emails.*.email.distinct' => 'Los emails no pueden repetirse.',
            'emails.*.type.required' => 'El tipo de email es obligatorio.',
            'emails.*.type.in' => 'El tipo de email no es válido.',
        ];
    }

    public function attributes(): array
    {
        return [
            'first_name' => 'nombre',
            'paternal_surname' => 'apellido paterno',
            'maternal_surname' => 'apellido materno',
            'birth_date' => 'fecha de nacimiento',
            'curp' => 'CURP',
            'rfc' => 'RFC',
            'phone' => 'teléfono',
            'mobile' => 'celular',
            'position_id' => 'puesto',
            'hire_date' => 'fecha de alta',
            'termination_date' => 'fecha de baja',
            'emails' => 'emails',
            'is_active' => 'estado',
            'notes' => 'notas',
        ];
    }

    /**
     * Preparar datos antes de validación
     */
    protected function prepareForValidation(): void
    {
        // Normalizar telefonos
        if ($this->has('phone')) {
            $this->merge([
                'phone' => preg_replace('/\D/', '', $this->input('phone')),
            ]);
        }

        if ($this->has('mobile')) {
            $this->merge([
                'mobile' => preg_replace('/\D/', '', $this->input('mobile')),
            ]);
        }

        // Normalizar CURP a mayusculas
        if ($this->has('curp')) {
            $this->merge([
                'curp' => strtoupper(trim($this->input('curp'))),
            ]);
        }

        // Normalizar RFC a mayusculas
        if ($this->has('rfc')) {
            $this->merge([
                'rfc' => strtoupper(trim($this->input('rfc'))),
            ]);
        }

        // Capitalizar nombres
        if ($this->has('first_name')) {
            $this->merge([
                'first_name' => mb_convert_case(trim($this->input('first_name')), MB_CASE_TITLE, 'UTF-8'),
            ]);
        }

        if ($this->has('paternal_surname')) {
            $this->merge([
                'paternal_surname' => mb_convert_case(trim($this->input('paternal_surname')), MB_CASE_TITLE, 'UTF-8'),
            ]);
        }

        if ($this->has('maternal_surname') && $this->input('maternal_surname')) {
            $this->merge([
                'maternal_surname' => mb_convert_case(trim($this->input('maternal_surname')), MB_CASE_TITLE, 'UTF-8'),
            ]);
        }

        // Normalizar emails a minusculas
        if ($this->has('emails')) {
            $emails = collect($this->input('emails'))->map(function ($email) {
                return [
                    'email' => strtolower(trim($email['email'] ?? '')),
                    'type' => $email['type'] ?? 'work',
                    'is_primary' => $email['is_primary'] ?? false,
                ];
            })->toArray();

            $this->merge(['emails' => $emails]);
        }
    }

    /**
     * Validación adicional después de las reglas
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $staffId = $this->route('staff')?->id;
            $emails = $this->input('emails', []);

            // Verificar que solo haya un email primario
            $primaryCount = collect($emails)->where('is_primary', true)->count();
            if ($primaryCount > 1) {
                $validator->errors()->add('emails', 'Solo puede haber un email primario.');
            }

            // Verificar unicidad de emails excluyendo los del staff actual
            foreach ($emails as $index => $emailData) {
                $email = strtolower(trim($emailData['email'] ?? ''));
                $exists = \App\Models\StaffEmail::where('email', $email)
                    ->where('staff_id', '!=', $staffId)
                    ->exists();

                if ($exists) {
                    $validator->errors()->add("emails.{$index}.email", 'Este email ya está registrado.');
                }
            }
        });
    }
}
