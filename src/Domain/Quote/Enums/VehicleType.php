<?php

namespace Src\Domain\Quote\Enums;

/**
 * Tipos de vehículo para cotizaciones de seguro
 * Basado en clasificación del sistema legacy
 */
enum VehicleType: string
{
    case AUTO = 'AUTO';
    case MOTO = 'MOTO';
    case PICK_UP = 'PICK UP';
    case CAMION = 'CAMION';

    /**
     * Obtiene la etiqueta en español
     */
    public function label(): string
    {
        return match ($this) {
            self::AUTO => 'Automóvil',
            self::MOTO => 'Motocicleta',
            self::PICK_UP => 'Pick Up',
            self::CAMION => 'Camión',
        };
    }

    /**
     * Determina si el vehículo requiere descripción de carga
     * Solo PICK UP y CAMION requieren este campo
     */
    public function requiresCargoDescription(): bool
    {
        return match ($this) {
            self::PICK_UP, self::CAMION => true,
            default => false,
        };
    }

    /**
     * Obtiene el ícono para UI
     */
    public function icon(): string
    {
        return match ($this) {
            self::AUTO => 'car',
            self::MOTO => 'motorcycle',
            self::PICK_UP => 'truck',
            self::CAMION => 'truck-loading',
        };
    }

    /**
     * Factor de riesgo base por tipo de vehículo
     */
    public function riskFactor(): float
    {
        return match ($this) {
            self::AUTO => 1.0,
            self::MOTO => 1.25,
            self::PICK_UP => 1.15,
            self::CAMION => 1.30,
        };
    }

    /**
     * Obtiene todos los tipos para select
     */
    public static function forSelect(): array
    {
        return array_map(fn($case) => [
            'value' => $case->value,
            'label' => $case->label(),
            'requires_cargo' => $case->requiresCargoDescription(),
        ], self::cases());
    }
}
