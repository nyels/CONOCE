<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Src\Domain\Shared\Enums\UserRole;

class User extends Authenticatable
{
    use HasApiTokens,
        HasFactory,
        Notifiable,
        HasRoles,
        LogsActivity,
        SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'avatar',
        'is_active',
        'last_login_at',
        'two_factor_enabled',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
            'two_factor_enabled' => 'boolean',
            'role' => UserRole::class,
        ];
    }

    /**
     * Configuración de Activity Log
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'role', 'is_active'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Usuario {$eventName}");
    }

    // ==========================================
    // Scopes
    // ==========================================

    /**
     * Scope para usuarios activos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para usuarios por rol
     */
    public function scopeByRole($query, UserRole $role)
    {
        return $query->where('role', $role->value);
    }

    /**
     * Scope para operadores (que manejan cotizaciones)
     */
    public function scopeOperators($query)
    {
        return $query->whereIn('role', [
            UserRole::OPERATOR->value,
            UserRole::MANAGER->value,
            UserRole::ADMIN->value,
            UserRole::SUPER_ADMIN->value,
        ]);
    }

    // ==========================================
    // Relaciones
    // ==========================================

    /**
     * Cotizaciones creadas por este usuario
     */
    public function quotes()
    {
        return $this->hasMany(Quote::class, 'agent_id');
    }

    /**
     * Contactos registrados por este usuario
     */
    public function contacts()
    {
        return $this->hasMany(Contact::class, 'created_by');
    }

    /**
     * Clientes registrados por este usuario
     */
    public function customers()
    {
        return $this->hasMany(Customer::class, 'created_by');
    }

    // ==========================================
    // Métodos de Autorización
    // ==========================================

    /**
     * Verifica si el usuario tiene un rol específico usando nuestro enum
     */
    public function hasUserRole(UserRole $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Verifica si el usuario es administrador o superior
     */
    public function isAdmin(): bool
    {
        return $this->role?->isHigherOrEqualTo(UserRole::ADMIN) ?? false;
    }

    /**
     * Verifica si el usuario es super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === UserRole::SUPER_ADMIN;
    }

    /**
     * Verifica si puede ver todas las cotizaciones
     */
    public function canViewAllQuotes(): bool
    {
        return $this->role?->canViewAllQuotes() ?? false;
    }

    /**
     * Verifica si puede gestionar usuarios
     */
    public function canManageUsers(): bool
    {
        return $this->role?->canManageUsers() ?? false;
    }

    /**
     * Verifica si puede configurar aseguradoras
     */
    public function canConfigureInsurers(): bool
    {
        return $this->role?->canConfigureInsurers() ?? false;
    }

    /**
     * Verifica si puede anular operaciones
     */
    public function canAnnulOperations(): bool
    {
        return $this->role?->canAnnulOperations() ?? false;
    }

    // ==========================================
    // Helpers
    // ==========================================

    /**
     * Obtiene las iniciales del nombre para avatar
     */
    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);
        $initials = '';

        foreach (array_slice($words, 0, 2) as $word) {
            $initials .= mb_strtoupper(mb_substr($word, 0, 1));
        }

        return $initials;
    }

    /**
     * Registra el último login
     */
    public function recordLogin(): void
    {
        $this->update(['last_login_at' => now()]);
    }

    /**
     * Obtiene el nombre del rol para mostrar
     */
    public function getRoleLabelAttribute(): string
    {
        return $this->role?->label() ?? 'Sin rol';
    }

    /**
     * Obtiene el color del rol para badges
     */
    public function getRoleColorAttribute(): string
    {
        return $this->role?->color() ?? 'gray';
    }
}
