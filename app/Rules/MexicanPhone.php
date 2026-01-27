<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validador de teléfono mexicano (10 dígitos)
 * Formato: Solo números, exactamente 10 dígitos
 * Ejemplo válido: 9991234567
 */
class MexicanPhone implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Remover espacios, guiones y paréntesis
        $cleaned = preg_replace('/[\s\-\(\)\.]+/', '', $value);

        // Verificar que sean exactamente 10 dígitos numéricos
        if (!preg_match('/^[0-9]{10}$/', $cleaned)) {
            $fail('El :attribute debe tener exactamente 10 dígitos numéricos.');
            return;
        }

        // Verificar que no sean todos el mismo dígito (ej: 0000000000)
        if (preg_match('/^(\d)\1{9}$/', $cleaned)) {
            $fail('El :attribute no puede ser un número repetido.');
            return;
        }

        // Verificar que inicie con un dígito válido para México (no 0, no 1)
        $firstDigit = substr($cleaned, 0, 1);
        if (in_array($firstDigit, ['0', '1'])) {
            $fail('El :attribute debe iniciar con un código de área válido (2-9).');
        }
    }
}
