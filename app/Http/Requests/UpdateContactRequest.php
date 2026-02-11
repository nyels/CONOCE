<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Request para validar la actualizacion de contactos/intermediarios
 * Validaciones estrictas segun tipo de campo
 */
class UpdateContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $contactId = $this->route('contact')?->id;
        $nameRegex = '/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\.]+$/';

        return [
            // Nombre(s) - obligatorio
            'first_name' => [
                'required',
                'string',
                'min:2',
                'max:100',
                "regex:{$nameRegex}",
            ],

            // Apellido paterno - obligatorio
            'paternal_surname' => [
                'required',
                'string',
                'min:2',
                'max:100',
                "regex:{$nameRegex}",
            ],

            // Apellido materno - obligatorio
            'maternal_surname' => [
                'required',
                'string',
                'min:2',
                'max:100',
                "regex:{$nameRegex}",
            ],

            // Tipo de contacto - obligatorio (ID de contact_types)
            'type' => [
                'required',
                'integer',
                'exists:contact_types,id',
            ],

            // Email - formato valido, unico excepto el actual
            'email' => [
                'nullable',
                'email:rfc,dns',
                'max:100',
                Rule::unique('contacts', 'email')->ignore($contactId),
            ],

            // Telefono fijo - 10 digitos solo numeros
            'phone' => [
                'nullable',
                'string',
                'regex:/^\d{10}$/',
            ],

            // Telefono movil - obligatorio, 10 digitos solo numeros
            'mobile' => [
                'required',
                'string',
                'regex:/^\d{10}$/',
            ],

            // Cedula CNSF - formato alfanumerico
            'cnsf_license' => [
                'nullable',
                'string',
                'max:30',
                'regex:/^[A-Z0-9\-]+$/i',
            ],

            // Fecha de vencimiento de cedula
            'license_expiry' => [
                'nullable',
                'date',
            ],

            // Tasa de comision (porcentaje 0-100)
            'commission_rate' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            // Agente padre
            'parent_agent_id' => [
                'nullable',
                'exists:contacts,id',
                // No puede ser el mismo
                function ($attribute, $value, $fail) use ($contactId) {
                    if ($value && $value == $contactId) {
                        $fail('El agente padre no puede ser el mismo contacto.');
                    }
                },
            ],

            // Notas
            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],

            // Estado activo
            'is_active' => [
                'nullable',
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'El nombre es obligatorio.',
            'first_name.min' => 'El nombre debe tener al menos 2 caracteres.',
            'first_name.max' => 'El nombre no puede exceder 100 caracteres.',
            'first_name.regex' => 'El nombre solo puede contener letras, espacios y puntos.',

            'paternal_surname.required' => 'El apellido paterno es obligatorio.',
            'paternal_surname.min' => 'El apellido paterno debe tener al menos 2 caracteres.',
            'paternal_surname.max' => 'El apellido paterno no puede exceder 100 caracteres.',
            'paternal_surname.regex' => 'El apellido paterno solo puede contener letras, espacios y puntos.',

            'maternal_surname.required' => 'El apellido materno es obligatorio.',
            'maternal_surname.min' => 'El apellido materno debe tener al menos 2 caracteres.',
            'maternal_surname.max' => 'El apellido materno no puede exceder 100 caracteres.',
            'maternal_surname.regex' => 'El apellido materno solo puede contener letras, espacios y puntos.',

            'mobile.required' => 'El celular es obligatorio.',

            'type.required' => 'El tipo de contacto es obligatorio.',
            'type.exists' => 'El tipo de contacto no es valido.',

            'email.email' => 'El correo electronico no tiene un formato valido.',
            'email.unique' => 'Este correo electronico ya esta registrado.',

            'phone.regex' => 'El telefono debe tener exactamente 10 digitos numericos.',
            'mobile.regex' => 'El celular debe tener exactamente 10 digitos numericos.',

            'cnsf_license.regex' => 'La cedula CNSF solo puede contener letras, numeros y guiones.',

            'commission_rate.min' => 'La comision no puede ser negativa.',
            'commission_rate.max' => 'La comision no puede exceder 100%.',

            'parent_agent_id.exists' => 'El agente padre seleccionado no existe.',
        ];
    }

    public function attributes(): array
    {
        return [
            'first_name' => 'nombre(s)',
            'paternal_surname' => 'apellido paterno',
            'maternal_surname' => 'apellido materno',
            'type' => 'tipo',
            'email' => 'correo electronico',
            'phone' => 'telefono',
            'mobile' => 'celular',
            'cnsf_license' => 'cedula CNSF',
            'license_expiry' => 'vencimiento de cedula',
            'commission_rate' => 'tasa de comision',
            'parent_agent_id' => 'agente padre',
            'notes' => 'notas',
            'is_active' => 'estado',
        ];
    }

    /**
     * Preparar datos antes de validacion
     */
    protected function prepareForValidation(): void
    {
        // Limpiar telefonos - remover caracteres no numericos
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

        // Normalizar campos de nombre - trim y capitalizar
        foreach (['first_name', 'paternal_surname', 'maternal_surname'] as $field) {
            if ($this->has($field) && $this->input($field)) {
                $this->merge([
                    $field => mb_convert_case(trim($this->input($field)), MB_CASE_TITLE, 'UTF-8'),
                ]);
            }
        }

        // Normalizar email a minusculas
        if ($this->has('email') && $this->input('email')) {
            $this->merge([
                'email' => strtolower(trim($this->input('email'))),
            ]);
        }

        // Normalizar cedula CNSF a mayusculas
        if ($this->has('cnsf_license') && $this->input('cnsf_license')) {
            $this->merge([
                'cnsf_license' => strtoupper(trim($this->input('cnsf_license'))),
            ]);
        }
    }
}
