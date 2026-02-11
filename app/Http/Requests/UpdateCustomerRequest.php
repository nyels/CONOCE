<?php

namespace App\Http\Requests;

use App\Rules\MexicanCURP;
use App\Rules\MexicanPhone;
use App\Rules\MexicanRFC;
use App\Rules\MexicanZipCode;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $nameRegex = '/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\.]+$/';

        return [
            'type' => ['required', 'string', 'in:physical,moral'],

            // Campos para persona física
            'first_name' => [
                'required_if:type,physical',
                'nullable',
                'string',
                'min:2',
                'max:100',
                "regex:{$nameRegex}",
            ],
            'paternal_surname' => [
                'required_if:type,physical',
                'nullable',
                'string',
                'min:2',
                'max:100',
                "regex:{$nameRegex}",
            ],
            'maternal_surname' => [
                'required_if:type,physical',
                'nullable',
                'string',
                'min:2',
                'max:100',
                "regex:{$nameRegex}",
            ],

            // Campo para persona moral
            'business_name' => [
                'required_if:type,moral',
                'nullable',
                'string',
                'min:3',
                'max:255',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ0-9\s\.\,\&\-]+$/',
            ],

            'rfc' => ['required', 'string', new MexicanRFC()],
            'curp' => ['nullable', 'string', new MexicanCURP()],
            'email' => ['required', 'email:rfc,dns', 'max:255'],
            'mobile' => ['required', 'string', new MexicanPhone()],
            'street' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ0-9\s\.\,\#\-]+$/'],
            'exterior_number' => ['nullable', 'string', 'max:20', 'regex:/^[a-zA-Z0-9\s\-]+$/'],
            'interior_number' => ['nullable', 'string', 'max:20', 'regex:/^[a-zA-Z0-9\s\-]+$/'],
            'neighborhood' => ['required', 'string', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ0-9\s\.\#\-]+$/'],
            'zip_code' => ['nullable', 'string', new MexicanZipCode()],
            'city' => ['nullable', 'string', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\.\-]+$/'],
            'municipality' => ['nullable', 'string', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\.\-]+$/'],
            'state' => ['nullable', 'string', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\.\-]+$/'],
            'legal_representative' => ['nullable', 'string', 'max:255', "regex:{$nameRegex}"],
            'legal_representative_rfc' => ['nullable', 'string', new MexicanRFC()],
            'source' => ['nullable', 'string', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ0-9\s\.\-]+$/'],
            'contact_id' => ['nullable', 'exists:contacts,id'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'El tipo de persona es obligatorio.',
            'type.in' => 'El tipo de persona debe ser física o moral.',

            'first_name.required_if' => 'El nombre es obligatorio para persona física.',
            'first_name.min' => 'El nombre debe tener al menos 2 caracteres.',
            'first_name.max' => 'El nombre no puede exceder 100 caracteres.',
            'first_name.regex' => 'El nombre solo puede contener letras, espacios y puntos.',

            'paternal_surname.required_if' => 'El apellido paterno es obligatorio para persona física.',
            'paternal_surname.min' => 'El apellido paterno debe tener al menos 2 caracteres.',
            'paternal_surname.max' => 'El apellido paterno no puede exceder 100 caracteres.',
            'paternal_surname.regex' => 'El apellido paterno solo puede contener letras, espacios y puntos.',

            'maternal_surname.required_if' => 'El apellido materno es obligatorio para persona física.',
            'maternal_surname.min' => 'El apellido materno debe tener al menos 2 caracteres.',
            'maternal_surname.max' => 'El apellido materno no puede exceder 100 caracteres.',
            'maternal_surname.regex' => 'El apellido materno solo puede contener letras, espacios y puntos.',

            'business_name.required_if' => 'La razón social es obligatoria para persona moral.',
            'business_name.min' => 'La razón social debe tener al menos 3 caracteres.',
            'business_name.max' => 'La razón social no puede exceder 255 caracteres.',
            'business_name.regex' => 'La razón social contiene caracteres no permitidos.',

            'rfc.required' => 'El RFC es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico no tiene un formato válido.',
            'mobile.required' => 'El celular es obligatorio.',
            'street.regex' => 'La calle contiene caracteres no permitidos.',
            'exterior_number.regex' => 'El número exterior contiene caracteres no permitidos.',
            'interior_number.regex' => 'El número interior contiene caracteres no permitidos.',
            'neighborhood.required' => 'La colonia es obligatoria.',
            'neighborhood.regex' => 'La colonia solo puede contener letras, números, espacios, #, - y puntos.',
            'city.regex' => 'La ciudad contiene caracteres no permitidos.',
            'municipality.regex' => 'El municipio contiene caracteres no permitidos.',
            'state.regex' => 'El estado contiene caracteres no permitidos.',
            'legal_representative.regex' => 'El representante legal solo puede contener letras, espacios y puntos.',
            'source.regex' => 'El origen contiene caracteres no permitidos.',
            'contact_id.exists' => 'El contacto seleccionado no existe.',
        ];
    }

    public function attributes(): array
    {
        return [
            'type' => 'tipo de persona',
            'first_name' => 'nombre(s)',
            'paternal_surname' => 'apellido paterno',
            'maternal_surname' => 'apellido materno',
            'business_name' => 'razón social',
            'rfc' => 'RFC',
            'curp' => 'CURP',
            'email' => 'correo electrónico',
            'phone' => 'teléfono',
            'mobile' => 'celular',
            'street' => 'calle',
            'exterior_number' => 'número exterior',
            'interior_number' => 'número interior',
            'neighborhood' => 'colonia',
            'zip_code' => 'código postal',
            'city' => 'ciudad',
            'municipality' => 'municipio',
            'state' => 'estado',
            'legal_representative' => 'representante legal',
            'legal_representative_rfc' => 'RFC del representante legal',
            'source' => 'origen',
            'contact_id' => 'contacto',
            'notes' => 'notas',
            'is_active' => 'estado activo',
        ];
    }

    /**
     * Preparar datos antes de validación
     */
    protected function prepareForValidation(): void
    {
        // Limpiar teléfonos - remover caracteres no numéricos
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

        // Normalizar razón social - trim
        if ($this->has('business_name') && $this->input('business_name')) {
            $this->merge([
                'business_name' => trim($this->input('business_name')),
            ]);
        }

        // Normalizar email a minúsculas
        if ($this->has('email') && $this->input('email')) {
            $this->merge([
                'email' => strtolower(trim($this->input('email'))),
            ]);
        }

        // Normalizar RFC y CURP a mayúsculas
        if ($this->has('rfc') && $this->input('rfc')) {
            $this->merge([
                'rfc' => strtoupper(trim($this->input('rfc'))),
            ]);
        }

        if ($this->has('curp') && $this->input('curp')) {
            $this->merge([
                'curp' => strtoupper(trim($this->input('curp'))),
            ]);
        }
    }
}
