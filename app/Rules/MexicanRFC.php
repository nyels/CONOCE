<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validador de RFC mexicano (Registro Federal de Contribuyentes)
 *
 * Persona Física: 13 caracteres (4 letras + 6 dígitos fecha + 3 homoclave)
 * Persona Moral: 12 caracteres (3 letras + 6 dígitos fecha + 3 homoclave)
 *
 * Formato: XXXX000000XXX (física) o XXX000000XXX (moral)
 */
class MexicanRFC implements ValidationRule
{
    private bool $allowGeneric;

    public function __construct(bool $allowGeneric = true)
    {
        $this->allowGeneric = $allowGeneric;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $rfc = strtoupper(trim($value));

        // RFC genéricos permitidos para público en general
        $genericRFCs = ['XAXX010101000', 'XEXX010101000'];

        if ($this->allowGeneric && in_array($rfc, $genericRFCs)) {
            return; // RFC genérico válido
        }

        // Longitud válida: 12 (moral) o 13 (física)
        $length = strlen($rfc);
        if (!in_array($length, [12, 13])) {
            $fail('El :attribute debe tener 12 caracteres (persona moral) o 13 caracteres (persona física).');
            return;
        }

        // Patrón para persona física (13 caracteres)
        $patternFisica = '/^[A-ZÑ&]{4}[0-9]{6}[A-Z0-9]{3}$/';

        // Patrón para persona moral (12 caracteres)
        $patternMoral = '/^[A-ZÑ&]{3}[0-9]{6}[A-Z0-9]{3}$/';

        if ($length === 13 && !preg_match($patternFisica, $rfc)) {
            $fail('El :attribute no tiene un formato válido para persona física (XXXX000000XXX).');
            return;
        }

        if ($length === 12 && !preg_match($patternMoral, $rfc)) {
            $fail('El :attribute no tiene un formato válido para persona moral (XXX000000XXX).');
            return;
        }

        // Validar fecha dentro del RFC
        $dateStart = ($length === 13) ? 4 : 3;
        $dateStr = substr($rfc, $dateStart, 6);

        $year = (int) substr($dateStr, 0, 2);
        $month = (int) substr($dateStr, 2, 2);
        $day = (int) substr($dateStr, 4, 2);

        // Año: 00-99 (asumimos 1900-2099)
        // Mes: 01-12
        // Día: 01-31
        if ($month < 1 || $month > 12) {
            $fail('El :attribute contiene un mes inválido.');
            return;
        }

        if ($day < 1 || $day > 31) {
            $fail('El :attribute contiene un día inválido.');
            return;
        }

        // Validar fecha real
        $fullYear = ($year <= 30) ? 2000 + $year : 1900 + $year;
        if (!checkdate($month, $day, $fullYear)) {
            $fail('El :attribute contiene una fecha inválida.');
        }
    }
}
