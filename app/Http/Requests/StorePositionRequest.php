<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePositionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:100',
                'unique:positions,name',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\.\-]+$/',
            ],
            'description' => [
                'nullable',
                'string',
                'max:255',
            ],
            'is_active' => [
                'boolean',
            ],
            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
                'max:255',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del puesto es obligatorio.',
            'name.min' => 'El nombre debe tener al menos 2 caracteres.',
            'name.max' => 'El nombre no puede exceder 100 caracteres.',
            'name.unique' => 'Este puesto ya existe.',
            'name.regex' => 'El nombre solo puede contener letras, espacios, puntos y guiones.',
            'description.max' => 'La descripción no puede exceder 255 caracteres.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('name')) {
            $this->merge([
                'name' => trim($this->input('name')),
            ]);
        }

        if ($this->has('description')) {
            $this->merge([
                'description' => trim($this->input('description')) ?: null,
            ]);
        }
    }
}
