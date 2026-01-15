<?php

declare(strict_types=1);

namespace Src\Domain\Quote\Enums;

/**
 * Estados del ciclo de vida de una cotización
 * 
 * Representa el flujo desde borrador hasta emisión o rechazo
 */
enum QuoteStatus: string
{
    /**
     * Borrador - Cotización en proceso de creación
     * Aún no se ha enviado al cliente
     */
    case DRAFT = 'DRAFT';

    /**
     * Enviada - Cotización enviada al cliente
     * Esperando respuesta del prospecto
     */
    case SENT = 'SENT';

    /**
     * Concretada - Cliente aceptó una opción
     * Pendiente de emisión de póliza
     */
    case CONCRETED = 'CONCRETED';

    /**
     * Emitida - Póliza emitida exitosamente
     * Estado final exitoso
     */
    case ISSUED = 'ISSUED';

    /**
     * Rechazada - Cliente rechazó la cotización
     * Estado final negativo
     */
    case REJECTED = 'REJECTED';

    /**
     * Anulada - Cotización anulada por el sistema
     * Solo super_admin puede realizar esta acción
     */
    case ANNULLED = 'ANNULLED';

    /**
     * Expirada - Cotización sin respuesta tras vigencia
     * Se marca automáticamente por el sistema
     */
    case EXPIRED = 'EXPIRED';

    /**
     * Obtiene la etiqueta amigable del estado
     */
    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Borrador',
            self::SENT => 'Enviada',
            self::CONCRETED => 'Concretada',
            self::ISSUED => 'Emitida',
            self::REJECTED => 'Rechazada',
            self::ANNULLED => 'Anulada',
            self::EXPIRED => 'Expirada',
        };
    }

    /**
     * Obtiene el color para UI (TailwindCSS classes)
     */
    public function color(): string
    {
        return match ($this) {
            self::DRAFT => 'slate',
            self::SENT => 'blue',
            self::CONCRETED => 'amber',
            self::ISSUED => 'emerald',
            self::REJECTED => 'red',
            self::ANNULLED => 'gray',
            self::EXPIRED => 'orange',
        };
    }

    /**
     * Obtiene el ícono asociado al estado
     */
    public function icon(): string
    {
        return match ($this) {
            self::DRAFT => 'pencil-square',
            self::SENT => 'paper-airplane',
            self::CONCRETED => 'check-circle',
            self::ISSUED => 'shield-check',
            self::REJECTED => 'x-circle',
            self::ANNULLED => 'ban',
            self::EXPIRED => 'clock',
        };
    }

    /**
     * Determina si el estado permite edición
     */
    public function isEditable(): bool
    {
        return $this === self::DRAFT;
    }

    /**
     * Determina si el estado es final
     */
    public function isFinal(): bool
    {
        return in_array($this, [
            self::ISSUED,
            self::REJECTED,
            self::ANNULLED,
            self::EXPIRED,
        ]);
    }

    /**
     * Determina si es un estado activo (requiere acción)
     */
    public function isActive(): bool
    {
        return in_array($this, [
            self::DRAFT,
            self::SENT,
            self::CONCRETED,
        ]);
    }

    /**
     * Obtiene las transiciones válidas desde este estado
     */
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::DRAFT => [self::SENT, self::ANNULLED],
            self::SENT => [self::CONCRETED, self::REJECTED, self::EXPIRED, self::ANNULLED],
            self::CONCRETED => [self::ISSUED, self::ANNULLED],
            self::ISSUED => [self::ANNULLED],
            self::REJECTED => [],
            self::ANNULLED => [],
            self::EXPIRED => [],
        };
    }

    /**
     * Verifica si una transición a otro estado es válida
     */
    public function canTransitionTo(self $newStatus): bool
    {
        return in_array($newStatus, $this->allowedTransitions());
    }

    /**
     * Obtiene valores para métricas de dashboard
     */
    public static function getActiveStatuses(): array
    {
        return [self::DRAFT, self::SENT, self::CONCRETED];
    }

    /**
     * Obtiene estados finales exitosos
     */
    public static function getSuccessStatuses(): array
    {
        return [self::CONCRETED, self::ISSUED];
    }
}
