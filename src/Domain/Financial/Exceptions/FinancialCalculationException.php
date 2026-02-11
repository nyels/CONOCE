<?php

declare(strict_types=1);

namespace Src\Domain\Financial\Exceptions;

use DomainException;

/**
 * Excepción base para errores de cálculo financiero.
 *
 * Cada error de dominio tiene un código string que preserva
 * el contrato legacy (no_derecho, no_recargo, etc.).
 */
final class FinancialCalculationException extends DomainException
{
    private string $errorCode;

    private function __construct(string $errorCode, string $message)
    {
        $this->errorCode = $errorCode;
        parent::__construct($message);
    }

    public function errorCode(): string
    {
        return $this->errorCode;
    }

    /**
     * No existe derecho de póliza configurado para la aseguradora.
     * Legacy: echo 'no_derecho';
     */
    public static function noPolicyFee(int $insurerId): self
    {
        return new self(
            'no_derecho',
            "No existe configuración financiera vigente para la aseguradora ID {$insurerId}."
        );
    }

    /**
     * No existe recargo configurado para la forma de pago solicitada.
     * Legacy: echo 'no_recargo';
     */
    public static function noSurcharge(int $insurerId, string $frequency): self
    {
        return new self(
            'no_recargo',
            "No existe recargo configurado para la aseguradora ID {$insurerId} con frecuencia {$frequency}."
        );
    }

    /**
     * La prima neta resultante es negativa.
     * Legacy: if(parseFloat(data)<0)
     */
    public static function negativeNetPremium(float $computed, float $totalInput): self
    {
        return new self(
            'prima_neta_negativa',
            "La prima neta calculada ({$computed}) es negativa. La prima total ingresada ({$totalInput}) es insuficiente para cubrir derecho y recargo."
        );
    }

    /**
     * El primer pago excede o iguala la prima total anual.
     * Legacy: if(parseFloat(primer_pago)>=parseFloat(prima_total_anual))
     */
    public static function firstPaymentExceedsTotal(float $firstPayment, float $totalAnnual): self
    {
        return new self(
            'primer_pago_excede_total',
            "El primer pago ({$firstPayment}) no puede ser mayor o igual a la prima total anual ({$totalAnnual})."
        );
    }

    /**
     * Frecuencia de pago no reconocida.
     */
    public static function invalidFrequency(string $frequency): self
    {
        return new self(
            'frecuencia_invalida',
            "La frecuencia de pago '{$frequency}' no es válida. Valores aceptados: ANUAL, SEMESTRAL, TRIMESTRAL, MENSUAL."
        );
    }

    /**
     * Total anual es cero o negativo.
     */
    public static function invalidTotalPremium(float $total): self
    {
        return new self(
            'total_invalido',
            "La prima total anual ({$total}) debe ser mayor a cero."
        );
    }
}
