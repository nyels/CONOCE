<?php

declare(strict_types=1);

namespace Src\Domain\Shared\ValueObjects;

use InvalidArgumentException;
use JsonSerializable;
use Stringable;

/**
 * Value Object para representar cantidades monetarias
 * 
 * Utiliza enteros internamente para evitar errores de punto flotante
 * Todas las operaciones retornan nuevas instancias (inmutabilidad)
 */
final class Money implements JsonSerializable, Stringable
{
    private const CURRENCY = 'MXN';
    private const SCALE = 2; // Dos decimales
    private const MULTIPLIER = 100; // Para convertir a centavos

    private int $cents;

    private function __construct(int $cents)
    {
        $this->cents = $cents;
    }

    /**
     * Crea un Money desde un valor decimal (ej: 1234.56)
     */
    public static function fromDecimal(float|int|string $amount): self
    {
        $amount = (float) $amount;

        if (!is_finite($amount)) {
            throw new InvalidArgumentException('El monto debe ser un número finito');
        }

        return new self((int) round($amount * self::MULTIPLIER));
    }

    /**
     * Crea un Money desde centavos (ej: 123456 = $1,234.56)
     */
    public static function fromCents(int $cents): self
    {
        return new self($cents);
    }

    /**
     * Crea un Money con valor cero
     */
    public static function zero(): self
    {
        return new self(0);
    }

    /**
     * Obtiene el valor en centavos
     */
    public function cents(): int
    {
        return $this->cents;
    }

    /**
     * Obtiene el valor decimal
     */
    public function amount(): float
    {
        return $this->cents / self::MULTIPLIER;
    }

    /**
     * Formatea el valor como string con símbolo de moneda
     */
    public function formatted(): string
    {
        return '$' . number_format($this->amount(), self::SCALE, '.', ',');
    }

    /**
     * Formatea sin símbolo de moneda
     */
    public function formattedWithoutSymbol(): string
    {
        return number_format($this->amount(), self::SCALE, '.', ',');
    }

    /**
     * Suma otro Money
     */
    public function add(self $other): self
    {
        return new self($this->cents + $other->cents);
    }

    /**
     * Resta otro Money
     */
    public function subtract(self $other): self
    {
        return new self($this->cents - $other->cents);
    }

    /**
     * Multiplica por un factor
     */
    public function multiply(float|int $factor): self
    {
        return new self((int) round($this->cents * $factor));
    }

    /**
     * Divide por un divisor
     */
    public function divide(float|int $divisor): self
    {
        if ($divisor == 0) {
            throw new InvalidArgumentException('No se puede dividir por cero');
        }

        return new self((int) round($this->cents / $divisor));
    }

    /**
     * Calcula un porcentaje de este monto
     */
    public function percentage(float $percent): self
    {
        return $this->multiply($percent / 100);
    }

    /**
     * Verifica si es igual a otro Money
     */
    public function equals(self $other): bool
    {
        return $this->cents === $other->cents;
    }

    /**
     * Verifica si es mayor que otro Money
     */
    public function greaterThan(self $other): bool
    {
        return $this->cents > $other->cents;
    }

    /**
     * Verifica si es menor que otro Money
     */
    public function lessThan(self $other): bool
    {
        return $this->cents < $other->cents;
    }

    /**
     * Verifica si es mayor o igual que otro Money
     */
    public function greaterThanOrEqual(self $other): bool
    {
        return $this->cents >= $other->cents;
    }

    /**
     * Verifica si es menor o igual que otro Money
     */
    public function lessThanOrEqual(self $other): bool
    {
        return $this->cents <= $other->cents;
    }

    /**
     * Verifica si es cero
     */
    public function isZero(): bool
    {
        return $this->cents === 0;
    }

    /**
     * Verifica si es positivo
     */
    public function isPositive(): bool
    {
        return $this->cents > 0;
    }

    /**
     * Verifica si es negativo
     */
    public function isNegative(): bool
    {
        return $this->cents < 0;
    }

    /**
     * Retorna el valor absoluto
     */
    public function absolute(): self
    {
        return new self(abs($this->cents));
    }

    /**
     * Retorna el negativo de este valor
     */
    public function negate(): self
    {
        return new self(-$this->cents);
    }

    /**
     * Obtiene el código de moneda
     */
    public function currency(): string
    {
        return self::CURRENCY;
    }

    /**
     * Distribuye el monto en n partes iguales (manejando centavos restantes)
     * 
     * @return Money[]
     */
    public function allocate(int $parts): array
    {
        if ($parts <= 0) {
            throw new InvalidArgumentException('El número de partes debe ser positivo');
        }

        $quotient = intdiv($this->cents, $parts);
        $remainder = $this->cents % $parts;

        $results = [];
        for ($i = 0; $i < $parts; $i++) {
            // Los primeros reciben el centavo extra
            $extra = $i < abs($remainder) ? ($remainder > 0 ? 1 : -1) : 0;
            $results[] = new self($quotient + $extra);
        }

        return $results;
    }

    public function jsonSerialize(): array
    {
        return [
            'cents' => $this->cents,
            'amount' => $this->amount(),
            'formatted' => $this->formatted(),
            'currency' => self::CURRENCY,
        ];
    }

    public function __toString(): string
    {
        return $this->formatted();
    }

    /**
     * Crea desde un array (para deserialización)
     */
    public static function fromArray(array $data): self
    {
        if (isset($data['cents'])) {
            return self::fromCents($data['cents']);
        }

        if (isset($data['amount'])) {
            return self::fromDecimal($data['amount']);
        }

        throw new InvalidArgumentException('El array debe contener "cents" o "amount"');
    }
}
