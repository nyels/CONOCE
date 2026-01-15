<?php

declare(strict_types=1);

namespace Src\Domain\Shared\ValueObjects;

use InvalidArgumentException;
use JsonSerializable;
use Stringable;

/**
 * Value Object para direcciones de email
 * 
 * Garantiza que el email sea válido y normalizado
 */
final class Email implements JsonSerializable, Stringable
{
    private string $value;

    private function __construct(string $email)
    {
        $this->value = $email;
    }

    /**
     * Crea un Email desde un string, validando el formato
     */
    public static function fromString(string $email): self
    {
        $email = self::normalize($email);

        if (!self::isValid($email)) {
            throw new InvalidArgumentException("El email '{$email}' no es válido");
        }

        return new self($email);
    }

    /**
     * Crea un Email sin validación (para datos de BD que ya están validados)
     */
    public static function fromTrusted(string $email): self
    {
        return new self(self::normalize($email));
    }

    /**
     * Intenta crear un Email, retorna null si no es válido
     */
    public static function tryFromString(string $email): ?self
    {
        try {
            return self::fromString($email);
        } catch (InvalidArgumentException) {
            return null;
        }
    }

    /**
     * Valida si un string es un email válido
     */
    public static function isValid(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Normaliza el email (lowercase, trim)
     */
    private static function normalize(string $email): string
    {
        return strtolower(trim($email));
    }

    /**
     * Obtiene el valor del email
     */
    public function value(): string
    {
        return $this->value;
    }

    /**
     * Obtiene solo la parte del usuario (antes del @)
     */
    public function username(): string
    {
        return explode('@', $this->value)[0];
    }

    /**
     * Obtiene el dominio (después del @)
     */
    public function domain(): string
    {
        return explode('@', $this->value)[1];
    }

    /**
     * Verifica si es igual a otro Email
     */
    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    /**
     * Verifica si el dominio es uno de los especificados
     */
    public function hasDomain(string ...$domains): bool
    {
        $emailDomain = $this->domain();
        foreach ($domains as $domain) {
            if (strtolower($domain) === $emailDomain) {
                return true;
            }
        }
        return false;
    }

    /**
     * Obtiene versión enmascarada para mostrar (ej: j***@gmail.com)
     */
    public function masked(): string
    {
        $username = $this->username();
        $domain = $this->domain();

        if (strlen($username) <= 2) {
            return $username[0] . '***@' . $domain;
        }

        return $username[0] . str_repeat('*', min(strlen($username) - 2, 5)) . substr($username, -1) . '@' . $domain;
    }

    public function jsonSerialize(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
