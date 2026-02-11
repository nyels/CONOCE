<?php

namespace App\Rules;

use App\Models\PasswordHistory;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Regla de validación para contraseñas con seguridad bancaria
 *
 * Requisitos:
 * - Mínimo 12 caracteres
 * - Al menos una letra mayúscula
 * - Al menos una letra minúscula
 * - Al menos un número
 * - Al menos un carácter especial
 * - No puede repetir contraseñas anteriores (últimas 5)
 * - No puede contener el username
 */
class StrongPassword implements ValidationRule
{
    protected ?int $userId;
    protected ?string $username;

    public function __construct(?int $userId = null, ?string $username = null)
    {
        $this->userId = $userId;
        $this->username = $username;
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Mínimo 12 caracteres
        if (strlen($value) < 12) {
            $fail('La contraseña debe tener al menos 12 caracteres.');
            return;
        }

        // Máximo 128 caracteres (prevenir DoS)
        if (strlen($value) > 128) {
            $fail('La contraseña no puede exceder 128 caracteres.');
            return;
        }

        // Al menos una letra mayúscula
        if (!preg_match('/[A-Z]/', $value)) {
            $fail('La contraseña debe contener al menos una letra mayúscula.');
            return;
        }

        // Al menos una letra minúscula
        if (!preg_match('/[a-z]/', $value)) {
            $fail('La contraseña debe contener al menos una letra minúscula.');
            return;
        }

        // Al menos un número
        if (!preg_match('/[0-9]/', $value)) {
            $fail('La contraseña debe contener al menos un número.');
            return;
        }

        // Al menos un carácter especial
        if (!preg_match('/[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]/', $value)) {
            $fail('La contraseña debe contener al menos un carácter especial (!@#$%^&*()_+-=[]{};\':"|,.<>/?).');
            return;
        }

        // No puede contener el username
        if ($this->username && stripos($value, $this->username) !== false) {
            $fail('La contraseña no puede contener el nombre de usuario.');
            return;
        }

        // No puede ser una contraseña usada recientemente
        if ($this->userId && PasswordHistory::wasUsedRecently($this->userId, $value)) {
            $fail('Esta contraseña ya fue utilizada anteriormente. Por favor elija una diferente.');
            return;
        }
    }

    /**
     * Genera una descripción de los requisitos de la contraseña
     */
    public static function requirements(): array
    {
        return [
            'Mínimo 12 caracteres',
            'Al menos una letra mayúscula (A-Z)',
            'Al menos una letra minúscula (a-z)',
            'Al menos un número (0-9)',
            'Al menos un carácter especial (!@#$%^&*...)',
            'No puede contener el nombre de usuario',
            'No puede repetir las últimas 5 contraseñas',
        ];
    }
}
