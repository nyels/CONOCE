<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validador de CURP mexicana (Clave Única de Registro de Población)
 *
 * Formato: 18 caracteres
 * XXXX000000XYYZZZXX
 *
 * - 4 letras iniciales del nombre
 * - 6 dígitos fecha de nacimiento (AAMMDD)
 * - 1 letra sexo (H/M)
 * - 2 letras entidad federativa
 * - 3 consonantes internas del nombre
 * - 1 caracter diferenciador homonimia
 * - 1 dígito verificador
 */
class MexicanCURP implements ValidationRule
{
    /**
     * Claves de entidades federativas válidas
     */
    private const ENTIDADES = [
        'AS', 'BC', 'BS', 'CC', 'CL', 'CM', 'CS', 'CH', 'DF', 'DG',
        'GT', 'GR', 'HG', 'JC', 'MC', 'MN', 'MS', 'NT', 'NL', 'OC',
        'PL', 'QT', 'QR', 'SP', 'SL', 'SR', 'TC', 'TS', 'TL', 'VZ',
        'YN', 'ZS', 'NE', // NE = Nacido en el Extranjero
    ];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $curp = strtoupper(trim($value));

        // Longitud exacta: 18 caracteres
        if (strlen($curp) !== 18) {
            $fail('La :attribute debe tener exactamente 18 caracteres.');
            return;
        }

        // Patrón general de CURP
        $pattern = '/^[A-Z]{4}[0-9]{6}[HM][A-Z]{5}[A-Z0-9][0-9]$/';

        if (!preg_match($pattern, $curp)) {
            $fail('La :attribute no tiene un formato válido.');
            return;
        }

        // Validar fecha de nacimiento (posiciones 4-9)
        $dateStr = substr($curp, 4, 6);
        $year = (int) substr($dateStr, 0, 2);
        $month = (int) substr($dateStr, 2, 2);
        $day = (int) substr($dateStr, 4, 2);

        if ($month < 1 || $month > 12) {
            $fail('La :attribute contiene un mes de nacimiento inválido.');
            return;
        }

        if ($day < 1 || $day > 31) {
            $fail('La :attribute contiene un día de nacimiento inválido.');
            return;
        }

        // Validar fecha real
        $fullYear = ($year <= 30) ? 2000 + $year : 1900 + $year;
        if (!checkdate($month, $day, $fullYear)) {
            $fail('La :attribute contiene una fecha de nacimiento inválida.');
            return;
        }

        // Validar sexo (posición 10)
        $sexo = substr($curp, 10, 1);
        if (!in_array($sexo, ['H', 'M'])) {
            $fail('La :attribute contiene un indicador de sexo inválido (debe ser H o M).');
            return;
        }

        // Validar entidad federativa (posiciones 11-12)
        $entidad = substr($curp, 11, 2);
        if (!in_array($entidad, self::ENTIDADES)) {
            $fail('La :attribute contiene una clave de entidad federativa inválida.');
            return;
        }

        // Validar dígito verificador (último caracter)
        if (!$this->validateCheckDigit($curp)) {
            $fail('La :attribute tiene un dígito verificador inválido.');
        }
    }

    /**
     * Valida el dígito verificador de la CURP usando el algoritmo oficial
     */
    private function validateCheckDigit(string $curp): bool
    {
        $dictionary = '0123456789ABCDEFGHIJKLMNÑOPQRSTUVWXYZ';
        $sum = 0;

        for ($i = 0; $i < 17; $i++) {
            $char = $curp[$i];
            $value = strpos($dictionary, $char);

            if ($value === false) {
                return false;
            }

            $sum += $value * (18 - $i);
        }

        $checkDigit = 10 - ($sum % 10);
        if ($checkDigit === 10) {
            $checkDigit = 0;
        }

        return (int) $curp[17] === $checkDigit;
    }
}
