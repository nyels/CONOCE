<?php

declare(strict_types=1);

namespace Src\Domain\Shared\ValueObjects;

use InvalidArgumentException;
use JsonSerializable;
use Stringable;

/**
 * Value Object para RFC mexicano
 * 
 * Valida el formato según el tipo de persona (física o moral)
 */
final class RFC implements JsonSerializable, Stringable
{
    private string $value;
    private bool $isPhysicalPerson;

    private function __construct(string $rfc, bool $isPhysicalPerson)
    {
        $this->value = $rfc;
        $this->isPhysicalPerson = $isPhysicalPerson;
    }

    /**
     * Crea un RFC con validación automática del tipo
     */
    public static function fromString(string $rfc): self
    {
        $rfc = self::normalize($rfc);

        if (strlen($rfc) === 13) {
            return self::fromPhysicalPerson($rfc);
        }

        if (strlen($rfc) === 12) {
            return self::fromMoralPerson($rfc);
        }

        throw new InvalidArgumentException(
            "El RFC debe tener 12 caracteres (Persona Moral) o 13 caracteres (Persona Física). Se recibió: " . strlen($rfc)
        );
    }

    /**
     * Crea un RFC para persona física (13 caracteres)
     */
    public static function fromPhysicalPerson(string $rfc): self
    {
        $rfc = self::normalize($rfc);

        // Patrón: 4 letras + 6 dígitos + 3 alfanuméricos
        if (!preg_match('/^[A-ZÑ&]{4}\d{6}[A-Z0-9]{3}$/i', $rfc)) {
            throw new InvalidArgumentException(
                "El RFC de persona física no tiene el formato correcto: {$rfc}"
            );
        }

        if (!self::hasValidDate($rfc, 4)) {
            throw new InvalidArgumentException(
                "El RFC contiene una fecha de nacimiento inválida: {$rfc}"
            );
        }

        return new self(strtoupper($rfc), true);
    }

    /**
     * Crea un RFC para persona moral (12 caracteres)
     */
    public static function fromMoralPerson(string $rfc): self
    {
        $rfc = self::normalize($rfc);

        // Patrón: 3 letras + 6 dígitos + 3 alfanuméricos
        if (!preg_match('/^[A-ZÑ&]{3}\d{6}[A-Z0-9]{3}$/i', $rfc)) {
            throw new InvalidArgumentException(
                "El RFC de persona moral no tiene el formato correcto: {$rfc}"
            );
        }

        if (!self::hasValidDate($rfc, 3)) {
            throw new InvalidArgumentException(
                "El RFC contiene una fecha de constitución inválida: {$rfc}"
            );
        }

        return new self(strtoupper($rfc), false);
    }

    /**
     * Intenta crear un RFC, retorna null si no es válido
     */
    public static function tryFromString(string $rfc): ?self
    {
        try {
            return self::fromString($rfc);
        } catch (InvalidArgumentException) {
            return null;
        }
    }

    /**
     * Normaliza el RFC
     */
    private static function normalize(string $rfc): string
    {
        return strtoupper(preg_replace('/\s+/', '', trim($rfc)));
    }

    /**
     * Valida que la fecha dentro del RFC sea válida
     */
    private static function hasValidDate(string $rfc, int $letterCount): bool
    {
        $dateStr = substr($rfc, $letterCount, 6);

        $year = (int) substr($dateStr, 0, 2);
        $month = (int) substr($dateStr, 2, 2);
        $day = (int) substr($dateStr, 4, 2);

        // Año puede ser 00-99 (se interpreta como 1900-2099)
        if ($month < 1 || $month > 12) {
            return false;
        }

        if ($day < 1 || $day > 31) {
            return false;
        }

        return true;
    }

    /**
     * Obtiene el valor del RFC
     */
    public function value(): string
    {
        return $this->value;
    }

    /**
     * Indica si es persona física
     */
    public function isPhysicalPerson(): bool
    {
        return $this->isPhysicalPerson;
    }

    /**
     * Indica si es persona moral
     */
    public function isMoralPerson(): bool
    {
        return !$this->isPhysicalPerson;
    }

    /**
     * Obtiene las iniciales (sin fecha ni homoclave)
     */
    public function initials(): string
    {
        return substr($this->value, 0, $this->isPhysicalPerson ? 4 : 3);
    }

    /**
     * Obtiene la fecha codificada
     */
    public function dateCode(): string
    {
        $start = $this->isPhysicalPerson ? 4 : 3;
        return substr($this->value, $start, 6);
    }

    /**
     * Obtiene la homoclave
     */
    public function homoclave(): string
    {
        return substr($this->value, -3);
    }

    /**
     * Verifica si es RFC genérico (para público en general)
     */
    public function isGeneric(): bool
    {
        return in_array($this->value, ['XAXX010101000', 'XEXX010101000']);
    }

    /**
     * Verifica si es igual a otro RFC
     */
    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    /**
     * Formato con guiones para mostrar
     */
    public function formatted(): string
    {
        $initials = $this->initials();
        $date = $this->dateCode();
        $homoclave = $this->homoclave();

        return "{$initials}-{$date}-{$homoclave}";
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
