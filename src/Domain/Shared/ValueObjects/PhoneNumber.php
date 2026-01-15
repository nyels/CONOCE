<?php

declare(strict_types=1);

namespace Src\Domain\Shared\ValueObjects;

use InvalidArgumentException;
use JsonSerializable;
use Stringable;

/**
 * Value Object para números telefónicos mexicanos
 */
final class PhoneNumber implements JsonSerializable, Stringable
{
    private string $value;
    private string $countryCode;

    private function __construct(string $number, string $countryCode = '52')
    {
        $this->value = $number;
        $this->countryCode = $countryCode;
    }

    /**
     * Crea un PhoneNumber desde un string, normalizando el formato
     */
    public static function fromString(string $phone): self
    {
        $normalized = self::normalize($phone);

        if (!self::isValid($normalized)) {
            throw new InvalidArgumentException(
                "El número telefónico '{$phone}' no es válido. Debe contener 10 dígitos."
            );
        }

        return new self($normalized);
    }

    /**
     * Intenta crear un PhoneNumber, retorna null si no es válido
     */
    public static function tryFromString(string $phone): ?self
    {
        try {
            return self::fromString($phone);
        } catch (InvalidArgumentException) {
            return null;
        }
    }

    /**
     * Normaliza el número telefónico
     */
    private static function normalize(string $phone): string
    {
        // Remover todo excepto dígitos
        $digits = preg_replace('/\D/', '', $phone);

        // Si empieza con 52 (código de país México) y tiene 12 dígitos, remover el código
        if (strlen($digits) === 12 && str_starts_with($digits, '52')) {
            $digits = substr($digits, 2);
        }

        // Si empieza con 1 (lada internacional) y tiene 11 dígitos, remover
        if (strlen($digits) === 11 && str_starts_with($digits, '1')) {
            $digits = substr($digits, 1);
        }

        return $digits;
    }

    /**
     * Valida si un número normalizado es válido (10 dígitos para México)
     */
    public static function isValid(string $normalizedNumber): bool
    {
        return strlen($normalizedNumber) === 10 && ctype_digit($normalizedNumber);
    }

    /**
     * Obtiene el número sin formato
     */
    public function value(): string
    {
        return $this->value;
    }

    /**
     * Obtiene el código de lada (primeros 2-3 dígitos)
     */
    public function areaCode(): string
    {
        // En México las ladas son de 2 o 3 dígitos
        // Ladas de 2 dígitos: CDMX (55), Guadalajara (33), Monterrey (81)
        $twoDigitCodes = ['55', '33', '81'];

        $firstTwo = substr($this->value, 0, 2);
        if (in_array($firstTwo, $twoDigitCodes)) {
            return $firstTwo;
        }

        return substr($this->value, 0, 3);
    }

    /**
     * Obtiene el número local (sin lada)
     */
    public function localNumber(): string
    {
        $areaCodeLength = strlen($this->areaCode());
        return substr($this->value, $areaCodeLength);
    }

    /**
     * Formato nacional: (55) 1234-5678
     */
    public function formatted(): string
    {
        $areaCode = $this->areaCode();
        $local = $this->localNumber();

        // Dividir el número local
        if (strlen($local) === 8) {
            return sprintf('(%s) %s-%s', $areaCode, substr($local, 0, 4), substr($local, 4));
        }

        if (strlen($local) === 7) {
            return sprintf('(%s) %s-%s', $areaCode, substr($local, 0, 3), substr($local, 3));
        }

        return sprintf('(%s) %s', $areaCode, $local);
    }

    /**
     * Formato internacional: +52 55 1234 5678
     */
    public function international(): string
    {
        $areaCode = $this->areaCode();
        $local = $this->localNumber();

        if (strlen($local) === 8) {
            return sprintf('+%s %s %s %s', $this->countryCode, $areaCode, substr($local, 0, 4), substr($local, 4));
        }

        return sprintf('+%s %s %s', $this->countryCode, $areaCode, $local);
    }

    /**
     * Formato para WhatsApp: 52XXXXXXXXXX
     */
    public function whatsapp(): string
    {
        return $this->countryCode . $this->value;
    }

    /**
     * Formato tel: para enlaces
     */
    public function telLink(): string
    {
        return 'tel:+' . $this->whatsapp();
    }

    /**
     * Verifica si es igual a otro PhoneNumber
     */
    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function jsonSerialize(): array
    {
        return [
            'number' => $this->value,
            'formatted' => $this->formatted(),
            'international' => $this->international(),
        ];
    }

    public function __toString(): string
    {
        return $this->formatted();
    }
}
