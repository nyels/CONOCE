<?php

namespace Src\Domain\Quote\Enums;

/**
 * Tipos de valor para coberturas de Daños Materiales y Robo Total
 * Exactamente como en el sistema legacy
 */
enum CoverageValueType: string
{
    case COMERCIAL = 'V.COMERCIAL';
    case CONVENIDO = 'V.CONVENIDO';
    case FACTURA = 'V.FACTURA';

    /**
     * Obtiene la etiqueta
     */
    public function label(): string
    {
        return match ($this) {
            self::COMERCIAL => 'Valor Comercial',
            self::CONVENIDO => 'Valor Convenido',
            self::FACTURA => 'Valor Factura',
        };
    }

    /**
     * Obtiene la etiqueta corta (como en legacy)
     */
    public function shortLabel(): string
    {
        return $this->value;
    }

    /**
     * Descripción del tipo de valor
     */
    public function description(): string
    {
        return match ($this) {
            self::COMERCIAL => 'Se basa en el valor de mercado del vehículo al momento del siniestro',
            self::CONVENIDO => 'Valor acordado entre aseguradora y asegurado al contratar',
            self::FACTURA => 'Se basa en el valor de factura original del vehículo',
        };
    }

    /**
     * Obtiene todos los tipos para select
     */
    public static function forSelect(): array
    {
        return array_map(fn($case) => [
            'value' => $case->value,
            'label' => $case->shortLabel(),
        ], self::cases());
    }
}
