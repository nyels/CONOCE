<?php

declare(strict_types=1);

namespace Src\Domain\Customer\Enums;

/**
 * Tipo de persona del cliente/prospecto
 */
enum CustomerType: string
{
    /**
     * Persona Física
     */
    case PHYSICAL = 'PHYSICAL';

    /**
     * Persona Moral (Empresa)
     */
    case MORAL = 'MORAL';

    public function label(): string
    {
        return match ($this) {
            self::PHYSICAL => 'Persona Física',
            self::MORAL => 'Persona Moral',
        };
    }

    public function shortLabel(): string
    {
        return match ($this) {
            self::PHYSICAL => 'Física',
            self::MORAL => 'Moral',
        };
    }

    /**
     * Longitud del RFC según el tipo de persona
     */
    public function rfcLength(): int
    {
        return match ($this) {
            self::PHYSICAL => 13,
            self::MORAL => 12,
        };
    }

    /**
     * Patrón de validación de RFC
     */
    public function rfcPattern(): string
    {
        return match ($this) {
            self::PHYSICAL => '/^[A-ZÑ&]{4}\d{6}[A-Z0-9]{3}$/i',
            self::MORAL => '/^[A-ZÑ&]{3}\d{6}[A-Z0-9]{3}$/i',
        };
    }

    /**
     * Indica el campo de nombre principal
     */
    public function nameFieldLabel(): string
    {
        return match ($this) {
            self::PHYSICAL => 'Nombre Completo',
            self::MORAL => 'Razón Social',
        };
    }

    /**
     * Indica si requiere representante legal
     */
    public function requiresLegalRepresentative(): bool
    {
        return $this === self::MORAL;
    }
}
