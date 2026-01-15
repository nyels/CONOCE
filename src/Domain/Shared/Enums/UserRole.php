<?php

declare(strict_types=1);

namespace Src\Domain\Shared\Enums;

/**
 * Roles del sistema de cotización de seguros
 * 
 * Define los niveles de acceso y permisos dentro del sistema,
 * siguiendo el principio de mínimo privilegio.
 */
enum UserRole: string
{
    /**
     * Super Administrador - Acceso total al sistema
     * Puede realizar cualquier operación incluyendo:
     * - Configuración del sistema
     * - Gestión de todos los usuarios
     * - Anulación de operaciones concretadas
     * - Acceso a logs y auditoría
     */
    case SUPER_ADMIN = 'super_admin';

    /**
     * Administrador - Acceso administrativo completo
     * Puede realizar:
     * - Gestión de usuarios (excepto super_admin)
     * - Configuración de aseguradoras
     * - Reportes completos
     * - Anulación de cotizaciones
     */
    case ADMIN = 'admin';

    /**
     * Gerente - Supervisión y reportes
     * Puede realizar:
     * - Ver todas las cotizaciones
     * - Generar reportes
     * - Asignar cotizaciones
     * - Aprobar operaciones especiales
     */
    case MANAGER = 'manager';

    /**
     * Operador - Gestión de cotizaciones propias
     * Puede realizar:
     * - CRUD de sus propias cotizaciones
     * - CRUD de clientes
     * - Generar y enviar PDFs
     * - Concretar/Rechazar cotizaciones propias
     */
    case OPERATOR = 'operator';

    /**
     * Visor - Solo lectura
     * Puede realizar:
     * - Ver cotizaciones asignadas
     * - Descargar PDFs
     */
    case VIEWER = 'viewer';

    /**
     * Obtiene la etiqueta amigable del rol en español
     */
    public function label(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => 'Super Administrador',
            self::ADMIN => 'Administrador',
            self::MANAGER => 'Gerente',
            self::OPERATOR => 'Operador',
            self::VIEWER => 'Visor',
        };
    }

    /**
     * Obtiene el color asociado al rol para UI
     */
    public function color(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => 'red',
            self::ADMIN => 'purple',
            self::MANAGER => 'blue',
            self::OPERATOR => 'green',
            self::VIEWER => 'gray',
        };
    }

    /**
     * Determina si el rol puede gestionar otros usuarios
     */
    public function canManageUsers(): bool
    {
        return in_array($this, [self::SUPER_ADMIN, self::ADMIN]);
    }

    /**
     * Determina si el rol puede ver todas las cotizaciones
     */
    public function canViewAllQuotes(): bool
    {
        return in_array($this, [self::SUPER_ADMIN, self::ADMIN, self::MANAGER]);
    }

    /**
     * Determina si el rol puede configurar aseguradoras
     */
    public function canConfigureInsurers(): bool
    {
        return in_array($this, [self::SUPER_ADMIN, self::ADMIN]);
    }

    /**
     * Determina si el rol puede anular operaciones
     */
    public function canAnnulOperations(): bool
    {
        return $this === self::SUPER_ADMIN;
    }

    /**
     * Obtiene el nivel jerárquico del rol (mayor = más privilegios)
     */
    public function hierarchyLevel(): int
    {
        return match ($this) {
            self::SUPER_ADMIN => 100,
            self::ADMIN => 80,
            self::MANAGER => 60,
            self::OPERATOR => 40,
            self::VIEWER => 20,
        };
    }

    /**
     * Verifica si este rol tiene más o igual jerarquía que otro
     */
    public function isHigherOrEqualTo(self $other): bool
    {
        return $this->hierarchyLevel() >= $other->hierarchyLevel();
    }

    /**
     * Obtiene todos los roles como array para selects
     */
    public static function toSelectArray(): array
    {
        return array_map(
            fn(self $role) => ['value' => $role->value, 'label' => $role->label()],
            self::cases()
        );
    }
}
