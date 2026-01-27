<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validador de Código Postal mexicano (5 dígitos)
 * Formato: Solo números, exactamente 5 dígitos
 * Rango válido: 01000 - 99999
 */
class MexicanZipCode implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $cleaned = trim($value);

        // Verificar que sean exactamente 5 dígitos numéricos
        if (!preg_match('/^[0-9]{5}$/', $cleaned)) {
            $fail('El :attribute debe tener exactamente 5 dígitos numéricos.');
            return;
        }

        // Verificar rango válido (01000 - 99999)
        $zipInt = (int) $cleaned;
        if ($zipInt < 1000 || $zipInt > 99999) {
            $fail('El :attribute debe estar entre 01000 y 99999.');
        }
    }
}
