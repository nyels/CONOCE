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
    /**
     * Días de validez de la contraseña
     */
    public const PASSWORD_EXPIRY_DAYS = 90;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'phone',
        'avatar',
        'is_active',
        'staff_id',
        'last_login_at',
        'password_expires_at',
        'password_changed_at',
        'failed_login_attempts',
        'locked_until',
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
            'password_expires_at' => 'datetime',
            'password_changed_at' => 'datetime',
            'locked_until' => 'datetime',
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

    /**
     * Personal asociado a este usuario
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Historial de contraseñas
     */
    public function passwordHistories()
    {
        return $this->hasMany(PasswordHistory::class);
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

    // ==========================================
    // Seguridad de Contraseña
    // ==========================================

    /**
     * Verifica si la contraseña ha expirado
     */
    public function isPasswordExpired(): bool
    {
        if (!$this->password_expires_at) {
            return false;
        }

        return $this->password_expires_at->isPast();
    }

    /**
     * Verifica si la cuenta está bloqueada
     */
    public function isLocked(): bool
    {
        if (!$this->locked_until) {
            return false;
        }

        return $this->locked_until->isFuture();
    }

    /**
     * Actualiza la contraseña con todas las medidas de seguridad
     */
    public function updatePassword(string $newPassword): void
    {
        // Registrar la contraseña actual en el historial antes de cambiar
        if ($this->password) {
            PasswordHistory::recordPassword($this->id, $this->password);
        }

        // Actualizar la contraseña y resetear campos de seguridad
        $this->update([
            'password' => $newPassword,
            'password_changed_at' => now(),
            'password_expires_at' => now()->addDays(self::PASSWORD_EXPIRY_DAYS),
            'failed_login_attempts' => 0,
            'locked_until' => null,
        ]);
    }

    /**
     * Días restantes hasta que expire la contraseña
     */
    public function getPasswordDaysRemainingAttribute(): ?int
    {
        if (!$this->password_expires_at) {
            return null;
        }

        return max(0, now()->diffInDays($this->password_expires_at, false));
    }

    /**
     * Registra un intento de login fallido
     */
    public function recordFailedLogin(): void
    {
        $this->increment('failed_login_attempts');
    }

    /**
     * Resetea los intentos de login fallidos
     */
    public function resetFailedLogins(): void
    {
        $this->update([
            'failed_login_attempts' => 0,
            'locked_until' => null,
        ]);
    }
}
