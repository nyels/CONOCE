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
        return [
            'type' => ['required', 'string', 'in:physical,moral'],
            'name' => ['required', 'string', 'max:255'],
            'rfc' => ['nullable', 'string', new MexicanRFC()],
            'curp' => ['nullable', 'string', new MexicanCURP()],
            'email' => ['nullable', 'email:rfc,dns', 'max:255'],
            'phone' => ['nullable', 'string', new MexicanPhone()],
            'mobile' => ['nullable', 'string', new MexicanPhone()],
            'street' => ['nullable', 'string', 'max:255'],
            'exterior_number' => ['nullable', 'string', 'max:20'],
            'interior_number' => ['nullable', 'string', 'max:20'],
            'neighborhood' => ['nullable', 'string', 'max:100'],
            'zip_code' => ['nullable', 'string', new MexicanZipCode()],
            'city' => ['nullable', 'string', 'max:100'],
            'municipality' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'legal_representative' => ['nullable', 'string', 'max:255'],
            'legal_representative_rfc' => ['nullable', 'string', new MexicanRFC()],
            'source' => ['nullable', 'string', 'max:100'],
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
            'name.required' => 'El nombre es obligatorio.',
            'name.max' => 'El nombre no puede exceder 255 caracteres.',
            'email.email' => 'El correo electrónico no tiene un formato válido.',
            'contact_id.exists' => 'El contacto seleccionado no existe.',
        ];
    }

    public function attributes(): array
    {
        return [
            'type' => 'tipo de persona',
            'name' => 'nombre',
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
}
